import IMask from "imask";
import { accordition } from "./modules/accordition";
import { sliders } from "./modules/sliders";
import { burger } from "./modules/burger";
import { tabs } from "./modules/tabs";
import MicroModal from "micromodal";
// import Swiper bundle with all modules installed
import Swiper from "swiper/bundle";
import { Fancybox } from "@fancyapps/ui";
import Choices from "choices.js";

// import styles bundle

console.log(Swiper);

MicroModal.init({
  disableScroll: true,
});

setTimeout(() => {
  MicroModal.show("modal-3");
}, 60000);

window.addEventListener("load", () => {
  const selects = document.querySelectorAll(".js-choice");
  selects.forEach((item) => {
    const choices = new Choices(item, {
      loadingText: "Загрузка...",
      noResultsText: "Ничего не найдено",
      noChoicesText: "No choices to choose from",
      itemSelectText: "",
      uniqueItemText: "Only unique values can be added",
      customAddItemText:
        "Only values matching specific conditions can be added",
    });
  });
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

  const swiper = new Swiper(".swiper-banner", {
    // Optional parameters
    loop: true,
    speed: 300,
    spaceBetween: 32,

    // If we need pagination
    pagination: {
      el: ".banners-pagination",
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

    // Navigation arrows
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

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
