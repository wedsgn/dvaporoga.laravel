(() => {
  "use strict";

  const SEL  = "form.index-hero-form, form.footer-form, form.modal-form";
  const BUSY = new WeakSet();

  // --- helpers ---
  const urlOf = (form) =>
    (form.getAttribute("data-action") || form.getAttribute("action") || "").trim();

  const csrfOf = (form) =>
    form.querySelector('input[name="_token"]')?.value ||
    document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ||
    "";

  const blurInside = (node) => {
    const ae = document.activeElement;
    if (ae && node?.contains(ae)) { try { ae.blur(); } catch {} }
  };

  const closeParentModalIfAny = (form) => {
    const m = form.closest(".modal");
    if (!m) return;
    blurInside(m);
    const id = m.id;
    if (id && window.MicroModal?.close) { try { MicroModal.close(id); } catch {} }
    else m.setAttribute("aria-hidden", "true");
  };

  const unlockScroll = () => {
    document.body.classList.remove("micromodal-open", "modal-open", "is-open");
    document.body.style.removeProperty("overflow");
    document.documentElement.style.removeProperty("overflow");
  };

  const openThanks = () => {
    if (window.MicroModal?.show) {
      try { MicroModal.show("modal-2"); } catch {}
      setTimeout(() => {
        const m2 = document.getElementById("modal-2");
        if (m2) blurInside(m2);
        try { MicroModal.close("modal-2"); } catch {}
        unlockScroll();
      }, 5000);
    } else {
      alert("Заявка успешно отправлена");
    }
  };

  // --- вывод ошибок у полей ---
  const clearErrors = (form) => {
    form.querySelectorAll(".field-error").forEach((n) => n.remove());
    form.querySelectorAll(".is-invalid").forEach((el) => {
      el.classList.remove("is-invalid");
      el.removeAttribute("aria-invalid");
      if (el.hasAttribute("data-added-describedby")) {
        const prev = el.getAttribute("data-added-describedby");
        if (prev) el.setAttribute("aria-describedby", prev);
        else el.removeAttribute("aria-describedby");
        el.removeAttribute("data-added-describedby");
      }
    });
    form.classList.remove("has-errors");
  };

  const ensureErrorBox = (input) => {
    let box = input.nextElementSibling;
    if (!(box && box.classList && box.classList.contains("field-error"))) {
      box = document.createElement("div");
      box.className = "field-error";
      input.insertAdjacentElement("afterend", box);
    }
    if (!box.id) {
      const base = input.id || input.name || "field";
      box.id = `${base}-error`;
    }
    return box;
  };

  const showFieldError = (form, field, message) => {
    const input = form.querySelector(`[name="${CSS.escape(field)}"]`);
    if (!input) return;
    input.classList.add("is-invalid");
    input.setAttribute("aria-invalid", "true");
    const box = ensureErrorBox(input);
    box.textContent = Array.isArray(message) ? message[0] : (message || "Некорректное значение");
    const prev = input.getAttribute("aria-describedby");
    if (prev) input.setAttribute("data-added-describedby", prev);
    input.setAttribute("aria-describedby", box.id);
  };

  const showErrors = (form, errors) => {
    form.classList.add("has-errors");
    if (errors && typeof errors === "object") {
      Object.entries(errors).forEach(([field, msg]) => showFieldError(form, field, msg));
      const firstInvalid = form.querySelector(".is-invalid");
      if (firstInvalid) { try { firstInvalid.focus({ preventScroll: false }); } catch {} }
    }
  };

  // --- лоадер на кнопке ---
  const setLoading = (form, on) => {
    const btn = form.querySelector('[type="submit"]');
    if (!btn) return;
    btn.disabled = !!on;
    if (!btn.querySelector("img")) {
      if (on) {
        if (!btn.dataset._t) btn.dataset._t = btn.textContent;
        btn.textContent = "Отправка...";
      } else if (btn.dataset._t) {
        btn.textContent = btn.dataset._t;
      }
    }
  };

  // --- основной обработчик ---
  const handle = async (e) => {
    const form = e.target instanceof HTMLFormElement ? e.target : null;
    if (!form || !form.matches(SEL)) return;

    e.preventDefault();
    e.stopPropagation();
    if (BUSY.has(form)) return;
    BUSY.add(form);

    clearErrors(form);

    const url = urlOf(form);
    if (!url) {
      BUSY.delete(form);
      return;
    }

    const fd   = new FormData(form);
    const csrf = csrfOf(form);

    setLoading(form, true);
    try {
      const res = await fetch(url, {
        method: form.getAttribute("method") || "POST",
        headers: {
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          ...(csrf ? { "X-CSRF-TOKEN": csrf } : {}),
        },
        body: fd,
        credentials: "same-origin",
      });

      let data = null;
      try { data = await res.clone().json(); } catch {}

      if (res.ok) {
        form.reset();
        closeParentModalIfAny(form);
        openThanks();
      } else if (res.status === 422) {
        // используем только серверные сообщения валидации
        const errs = (data && (data.errors || data)) || {};
        showErrors(form, errs);
      } else if (res.status === 419 || res.status === 401) {
        alert("Сессия истекла. Обновите страницу и попробуйте снова.");
      } else {
        const msg = (data && (data.message || data.error)) || `Ошибка (${res.status}). Попробуйте позже.`;
        alert(msg);
      }
    } catch (_) {
      alert("Сеть недоступна или сервер не ответил.");
    } finally {
      setLoading(form, false);
      BUSY.delete(form);
    }
  };

  // --- wiring ---
  document.addEventListener("submit", handle, true);

  const attachDirect = () => {
    document.querySelectorAll(SEL).forEach((f) => {
      if (f.dataset._ajaxBound) return;
      f.addEventListener("submit", handle, { capture: true });
      f.dataset._ajaxBound = "1";
      if (!f.hasAttribute("action") && !f.hasAttribute("data-action")) f.setAttribute("action", "#");
      f.setAttribute("novalidate", "novalidate");
    });
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", attachDirect, { once: true });
  } else {
    attachDirect();
  }

  // чистим фокус/скролл при ручном закрытии «спасибо»
  document.addEventListener("click", (e) => {
    const t = e.target;
    if (!(t instanceof Element)) return;
    if (t.hasAttribute("data-micromodal-close")) {
      const m = t.closest(".modal");
      if (m) blurInside(m);
      if (m && m.id === "modal-2") setTimeout(unlockScroll, 0);
    }
  }, true);
})();
