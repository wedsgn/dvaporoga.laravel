import IMask from "imask";
import { accordition } from "./modules/accordition";
import { sliders } from "./modules/sliders";
import { burger } from "./modules/burger";
import { tabs } from "./modules/tabs";
import MicroModal from "micromodal";
import Swiper from "swiper/bundle";
import { Fancybox } from "@fancyapps/ui";
import Choices from "choices.js";

window.addEventListener("load", () => {
  const markSelect = new Choices("#choose-make", {
    shouldSort: true,
    sorter: (a, b) => {
      const labelA = a.label || a.value;
      const labelB = b.label || b.value;

      const isLatin = (s) => /^[A-Za-z]/.test(s);
      const isCyrillic = (s) => /^[А-Яа-яЁё]/.test(s);

      const aLatin = isLatin(labelA);
      const bLatin = isLatin(labelB);
      const aCyr = isCyrillic(labelA);
      const bCyr = isCyrillic(labelB);

      if (aLatin && !bLatin) {
        return -1; // A (латиница) выше B (не латиница)
      }
      if (!aLatin && bLatin) {
        return 1; // B (латиница) выше A
      }
      // оба латиница или оба не латиница — тогда обычное сравнение
      return labelA.localeCompare(labelB, undefined, { sensitivity: "base" });
    },
  });
  const modelSelect = new Choices("#choose-model");
  var route = modelSelect.passedElement.element.dataset.modelsUrl;
  modelSelect.disable();

  async function loadModels(makeId, preselect) {
    var res = await fetch(route + "?make_id=" + encodeURIComponent(makeId), {
      headers: { Accept: "application/json" },
      cache: "no-store",
      credentials: "same-origin",
    });
    if (!res.ok) throw new Error("HTTP " + res.status);
    var data = await res.json();

    modelSelect.clearChoices();
    modelSelect.setChoices(
      data.map((item) => ({ value: item.id, label: item.title }))
    );

    modelSelect.enable();
    modelSelect.setChoiceByValue(data[0].id);
  }

  markSelect.passedElement.element.addEventListener("choice", (e) => {
    loadModels(e.detail.value);
  });

  const form = document.getElementById("choose-car-form");
  const statusEl = form.querySelector(".form-status");
  const submitBtn = form.querySelector('button[type="submit"]');

  function showFieldError(name, message) {
    const holder = form.querySelector(`.field-error[data-error-for="${name}"]`);
    if (holder) holder.textContent = message;
    const field = form.querySelector(`[name="${name}"]`);
    if (field) {
      field.classList.add("is-invalid");
      const next = field.nextElementSibling;
      const wrap =
        next && next.classList && next.classList.contains("choices")
          ? next
          : field.closest(".choices");
      if (wrap && wrap.classList) wrap.classList.add("is-invalid");
    }
  }

  function setStatus(msg, ok = false) {
    if (!statusEl) return;
    statusEl.textContent = msg || "";
    statusEl.style.color = ok ? "#0a7b28" : "#d00";
  }

  function clearErrors() {
    form
      .querySelectorAll(".field-error")
      .forEach((el) => (el.textContent = ""));
    form
      .querySelectorAll(".is-invalid")
      .forEach((el) => el.classList.remove("is-invalid"));
    form
      .querySelectorAll(".choices.is-invalid")
      .forEach((el) => el.classList.remove("is-invalid"));
  }

  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      clearErrors();
      setStatus("");
      submitBtn.disabled = true;

      // Затемнение формы
      let overlay = form.querySelector(".form-overlay-submit");
      if (!overlay) {
        overlay = document.createElement("div");
        overlay.className = "form-overlay-submit";
        // простейший dimming стиль, можно вынести в CSS
        overlay.style.position = "absolute";
        overlay.style.top = 0;
        overlay.style.left = 0;
        overlay.style.width = "100%";
        overlay.style.height = "100%";
        overlay.style.background = "rgba(255,255,255,0.65)";
        overlay.style.zIndex = 101;
        overlay.style.pointerEvents = "all";
        overlay.style.display = "flex";
        overlay.style.alignItems = "center";
        overlay.style.justifyContent = "center";
        overlay.innerHTML =
          '<div class="form-spinner" style="border: 3px solid #CCC;border-top: 3px solid #888;border-radius: 50%;width: 32px;height: 32px;animation: spin 1s linear infinite;"></div>';
        // Добавить спиннер анимацию инлайном
        let style = document.createElement("style");
        style.innerHTML = `@keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}`;
        overlay.appendChild(style);
        form.style.position = "relative";
        form.appendChild(overlay);
      } else {
        overlay.style.display = "flex";
      }

      try {
        const fd = new FormData(form);
        const res = await fetch(form.action, {
          method: "POST",
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN":
              document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content") || "",
          },
          body: fd,
          credentials: "same-origin",
          cache: "no-store",
        });

        if (res.status === 201 || res.ok) {
          if (window.YMGoals && typeof window.YMGoals.fire === "function") {
            window.YMGoals.fire(form, { trigger: "success" });
          } else {
            console.log(
              "[YMGoals] fire skipped: YMGoals или ym не доступны",
              window.YMGoals,
              typeof window.ym
            );
          }

          MicroModal.show("modal-2");

          form.reset();

          // markSelect.clearChoices();
          modelSelect.clearChoices();
          modelSelect.disable();

          // вернуть модель к плейсхолдеру
          const modelEl = document.getElementById("choose-model");
          if (modelEl) {
            try {
              modelEl.choices?.destroy?.();
            } catch (e) {}
            const wrap = modelEl.nextElementSibling;
            if (wrap && wrap.classList?.contains("choices")) wrap.remove();
            modelEl.innerHTML =
              '<option value="" disabled selected>Сначала выберите марку</option>';
            modelEl.disabled = true;
          }
          return;
        }

        if (res.status === 422) {
          const data = await res.json();
          const errors = data.errors || {};

          console.log(errors);

          Object.keys(errors).forEach((name) => {
            const msg = Array.isArray(errors[name])
              ? errors[name][0]
              : String(errors[name]);
            showFieldError(name, msg);
          });
          return;
        }

        setStatus("Ошибка при отправке. Попробуйте позже.");
      } catch (err) {
        console.error("[choose-car] submit failed:", err);
        setStatus("Сетевая ошибка. Проверьте соединение.");
      } finally {
        submitBtn.disabled = false;
        // Убрать затемнение
        if (overlay) overlay.style.display = "none";
      }
    });
  }

  MicroModal.init({
    disableScroll: true,
  });

  setTimeout(() => {
    MicroModal.show("modal-3");
  }, 60000);

  accordition();
  sliders();
  burger();
  tabs();

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
  });

  var phones = document.querySelectorAll('input[type="tel"]');
  var maskOptions = {
    mask: "+7 (000) 000 00 00",
  };

  phones.forEach((element) => {
    var mask = new IMask(element, maskOptions);
  });

  const products = document.querySelectorAll(".product");

  if (products) {
    products.forEach((product) => {
      const priceDeiv = product.querySelector(".product-price span");
      const data = product.getAttribute("data-item");
      const dataRes = JSON.parse(data);
      const steelSelector = product.querySelector(".steel-select");
      const priceInput = product.querySelector("#productPriceInput");
      const priceIdInput = product.querySelector("#productPriceId");
      const thicknessSelector = product.querySelector(".thickness_select");
      const typeSelector = product.querySelector(".type-selector");
      const sizeSelector = product.querySelector(".size-selector");

      let options = {
        size_id: sizeSelector.value,
        steel_type_id: steelSelector.value,
        thickness_id: thicknessSelector.value,
        type_id: typeSelector.value,
      };

      const getPrice = () => {
        const price = dataRes.prices.find((item) => {
          return (
            item.size_id == options.size_id &&
            item.steel_type_id == options.steel_type_id &&
            item.thickness_id == options.thickness_id &&
            item.type_id == options.type_id
          );
        });

        if (price) {
          priceDeiv.innerHTML = price.one_side;
          priceInput.value = price;
          priceIdInput.value = price.id;
        }
      };

      steelSelector.addEventListener("change", function (e) {
        options.steel_type_id = +e.target.value;
        getPrice();
      });

      thicknessSelector.addEventListener("change", function (e) {
        options.thickness_id = +e.target.value;
        getPrice("thickness");
      });

      typeSelector.addEventListener("change", function (e) {
        options.type_id = +e.target.value;
        getPrice();
      });
      sizeSelector.addEventListener("change", function (e) {
        options.size_id = +e.target.value;
        getPrice();
      });

      priceDeiv.innerHTML = "";
      getPrice();
    });
  }
  // Корзина

 // hero – десктопный слайдер
  const desktopSwiperEl = document.querySelector(".swiper-banner-desktop");
  if (desktopSwiperEl) {
    new Swiper(desktopSwiperEl, {
      loop: true,
      autoplay: {
        delay: 5000,
      },
      slidesPerView: 1,
      spaceBetween: 32,
      pagination: {
        el: ".hero-pag-desktop",
        clickable: true,
      },
      navigation: {
        nextEl: ".hero-banner-arrow-next-desktop",
        prevEl: ".hero-banner-arrow-prev-desktop",
      },
    });
  }

  // hero – мобильный слайдер
  const mobileSwiperEl = document.querySelector(".swiper-banner-mobile");
  if (mobileSwiperEl) {
    new Swiper(mobileSwiperEl, {
      loop: true,
      autoplay: {
        delay: 5000,
      },
      slidesPerView: 1,
      spaceBetween: 32,
      pagination: {
        el: ".hero-pag-mobile",
        clickable: true,
      },
      navigation: {
        nextEl: ".hero-banner-arrow-next-mobile",
        prevEl: ".hero-banner-arrow-prev-mobile",
      },
    });
  }

  // галерея оставляем как было
  const swiperGallery = new Swiper(".gallery-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: true,
    pagination: {
      el: ".gallery-pagination",
      type: "fraction",
      renderFraction: function (currentClass, totalClass) {
        return (
          '<span class="' +
          currentClass +
          '"></span>' +
          "/" +
          '<span class="' +
          totalClass +
          '"></span>'
        );
      },
    },
    navigation: {
      nextEl: ".gallery-arrow-next",
      prevEl: ".gallery-arrow-prev",
    },
  });
});
