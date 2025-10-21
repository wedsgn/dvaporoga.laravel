(() => {
  'use strict';

  const cartForm         = document.getElementById('cart-form');
  const products         = document.querySelectorAll('.product-part');

  const cartItemsPlace   = document.querySelector('.total-form__parts');
  const totalPriceInput  = document.querySelector('.product-form__price'); // должен иметь name="total_price"
  const arrayFormInput   = document.querySelector('.product-form__array'); // должен иметь name="data"
  const clearCart        = document.querySelector('.product-parts__total_clear');
  const totalPriceDiv    = document.querySelector('.total-form__total_value span');

  let selectedItems = [];
  let totalPrice    = 0;

  // ---------- helpers ----------
  const getEndpoint = (form) =>
    (form.getAttribute('data-action') || form.getAttribute('action') || '').trim();

  const getCsrf = (form) =>
    form.querySelector('input[name="_token"]')?.value ||
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  const blurIfInside = (root) => {
    const ae = document.activeElement;
    if (ae && root && root.contains(ae)) { try { ae.blur(); } catch {} }
  };

  const unlockScrollIfStuck = () => {
    document.body.classList.remove('micromodal-open','modal-open','is-open');
    document.body.style.removeProperty('overflow');
    document.documentElement.style.removeProperty('overflow');
  };

  let thanksTimer = null;
  const openThanks = () => {
    if (window.MicroModal?.show) {
      try { MicroModal.show('modal-2'); } catch {}
      if (thanksTimer) clearTimeout(thanksTimer);
      thanksTimer = setTimeout(() => {
        const m2 = document.getElementById('modal-2');
        if (m2) { blurIfInside(m2); try { MicroModal.close('modal-2'); } catch {} }
        unlockScrollIfStuck();
      }, 5000);
    } else {
      alert('Заявка успешно отправлена');
    }
  };

  const syncMasks = (root) => {
    root.querySelectorAll('input[type="tel"], [data-mask], .js-mask').forEach((input) => {
      if (input._imask && typeof input._imask.updateValue === 'function') {
        try { input._imask.updateValue(); return; } catch {}
      }
      if (input.inputmask) {
        try {
          if (typeof input.inputmask.refreshValue === 'function') input.inputmask.refreshValue();
          else if (typeof input.inputmask.setValue === 'function') input.inputmask.setValue(input.value || '');
          return;
        } catch {}
      }
      input.dispatchEvent(new Event('input', { bubbles: true }));
    });
  };

  // ---------- вывод ошибок (только серверные) ----------
  const clearErrors = (form) => {
    form.querySelectorAll('.field-error').forEach(n => n.remove());
    form.querySelectorAll('.is-invalid').forEach(el => {
      el.classList.remove('is-invalid');
      el.removeAttribute('aria-invalid');
      if (el.hasAttribute('data-added-describedby')) {
        const prev = el.getAttribute('data-added-describedby');
        if (prev) el.setAttribute('aria-describedby', prev);
        else el.removeAttribute('aria-describedby');
        el.removeAttribute('data-added-describedby');
      }
    });
    form.classList.remove('has-errors');
  };

  const ensureErrorBox = (input) => {
    let box = input.nextElementSibling;
    if (!(box && box.classList && box.classList.contains('field-error'))) {
      box = document.createElement('div');
      box.className = 'field-error';
      input.insertAdjacentElement('afterend', box);
    }
    if (!box.id) {
      const base = input.id || input.name || 'field';
      box.id = `${base}-error`;
    }
    return box;
  };

  const showFieldError = (form, field, message) => {
    const input = form.querySelector(`[name="${CSS.escape(field)}"]`);
    if (!input) return;
    input.classList.add('is-invalid');
    input.setAttribute('aria-invalid', 'true');
    const box = ensureErrorBox(input);
    box.textContent = Array.isArray(message) ? message[0] : (message || 'Некорректное значение');
    const prev = input.getAttribute('aria-describedby');
    if (prev) input.setAttribute('data-added-describedby', prev);
    input.setAttribute('aria-describedby', box.id);
  };

  const showErrors = (form, errors) => {
    form.classList.add('has-errors');
    if (errors && typeof errors === 'object') {
      Object.entries(errors).forEach(([field, msg]) => showFieldError(form, field, msg));
      const firstInvalid = form.querySelector('.is-invalid');
      if (firstInvalid) { try { firstInvalid.focus({ preventScroll: false }); } catch {} }
    }
  };

  const setLoading = (form, on) => {
    const btn = form.querySelector('[type="submit"]');
    if (!btn) return;
    btn.disabled = !!on;
    if (!btn.querySelector('img')) {
      if (on) {
        if (!btn.dataset._t) btn.dataset._t = btn.textContent;
        btn.textContent = 'Отправка...';
      } else if (btn.dataset._t) {
        btn.textContent = btn.dataset._t;
      }
    }
  };

  // ---------- корзина ----------
  const checkSum = () => {
    if (totalPriceDiv)   totalPriceDiv.innerHTML = totalPrice;
    if (totalPriceInput) totalPriceInput.value   = totalPrice;                 // name="total_price"
    if (arrayFormInput)  arrayFormInput.value    = JSON.stringify(selectedItems); // name="data"
  };

  const pushArray = () => {
    if (!cartItemsPlace) return;
    cartItemsPlace.innerHTML = '';
    if (!selectedItems.length) {
      cartItemsPlace.insertAdjacentHTML('afterbegin',
        `<div class="total-form__empty">Добавьте запчасти в заказ</div>`);
      return;
    }
    selectedItems.forEach((item) => {
      cartItemsPlace.insertAdjacentHTML(
        'beforeend',
        `<div class="total-form__part">
           <p class="total-form__part_title">${item.title}</p>
           <p class="total-form__part_price">${item.price} р.</p>
         </div>`
      );
    });
  };

  clearCart?.addEventListener('click', () => {
    selectedItems = [];
    totalPrice = 0;
    checkSum();
    pushArray();
  });

  products.forEach((item) => {
    const addBtn = item.querySelector('.btn');
    if (!addBtn) return;

    addBtn.addEventListener('click', () => {
      const title  = item.querySelector('.product-part__title')?.innerHTML ?? '';
      const itemId = item.querySelector('.product-part__id')?.value ?? '';
      const price  = item.querySelector('.product-part__price_num span')?.innerHTML ?? '0';

      const idx = selectedItems.findIndex((x) => x.id === itemId);
      if (idx === -1) {
        selectedItems.push({
          id: itemId,
          data: item.getAttribute('data-item') || '',
          title,
          price,
        });
        totalPrice += +price;
        addBtn.innerHTML = 'Убрать из заказа';
      } else {
        totalPrice -= +selectedItems[idx].price;
        selectedItems.splice(idx, 1);
        addBtn.innerHTML = 'Добавить в заказ';
      }
      checkSum();
      pushArray();
    });

    // динамическая цена (оставил твою логику)
    const data = item.getAttribute('data-item');
    if (!data) return;
    let res; try { res = JSON.parse(data); } catch { return; }

    const steelSelector     = item.querySelector('.steel-select');
    const thicknessSelector = item.querySelector('.thickness_select');
    const typeSelector      = item.querySelector('.type-selector');
    const sizeSelector      = item.querySelector('.size-selector');
    const priceDeiv         = item.querySelector('.product-price span');

    if (!(steelSelector && thicknessSelector && typeSelector && sizeSelector && priceDeiv)) return;

    const options = {
      get size_id()       { return sizeSelector.value;      },
      get steel_type_id() { return steelSelector.value;     },
      get thickness_id()  { return thicknessSelector.value; },
      get type_id()       { return typeSelector.value;      },
    };

    const getPrice = () => {
      const price = (res.prices || []).find((it) =>
        it.size_id == options.size_id &&
        it.steel_type_id == options.steel_type_id &&
        it.thickness_id == options.thickness_id &&
        it.type_id == options.type_id
      );
      if (price) priceDeiv.innerHTML = price.one_side;
    };

    steelSelector.addEventListener('change', getPrice);
    thicknessSelector.addEventListener('change', getPrice);
    typeSelector.addEventListener('change', getPrice);
    sizeSelector.addEventListener('change', getPrice);

    priceDeiv.innerHTML = '';
    getPrice();
  });

  // ---------- отправка формы (только серверная валидация) ----------
  if (cartForm) {
    cartForm.setAttribute('novalidate', 'novalidate');

    cartForm.addEventListener('submit', async (event) => {
      event.preventDefault();

      clearErrors(cartForm);

      // перед отправкой актуализируем скрытые поля
      checkSum();

      const url = getEndpoint(cartForm);
      if (!url) return;

      const csrf = getCsrf(cartForm);
      const formData = new FormData(cartForm);

      setLoading(cartForm, true);
      try {
        const response = await fetch(url, {
          method: cartForm.getAttribute('method') || 'POST',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
          },
          body: formData,
          credentials: 'same-origin'
        });

        let payload = null;
        try { payload = await response.clone().json(); } catch {}

        if (response.ok) {
          cartForm.reset();
          selectedItems = [];
          totalPrice = 0;
          checkSum();
          pushArray();
          syncMasks(cartForm);
          openThanks();
        } else if (response.status === 422) {
          // показываем только то, что прислал сервер
          const errs = (payload && (payload.errors || payload)) || {};
          showErrors(cartForm, errs);
        } else if (response.status === 419 || response.status === 401) {
          alert('Сессия истекла. Обновите страницу и попробуйте снова.');
        } else {
          const msg = (payload && (payload.message || payload.error)) || `Ошибка (${response.status}). Попробуйте позже.`;
          alert(msg);
        }
      } catch (_) {
        alert('Сеть недоступна или сервер не ответил.');
      } finally {
        setLoading(cartForm, false);
      }
    });

    // маски: тихо обновляем при автозаполнении
    const maskGuard = (e) => {
      const t = e.target;
      if (!(t instanceof HTMLInputElement)) return;
      if (t.matches('input[type="tel"], [data-mask], .js-mask')) {
        if (t._imask?.updateValue) t._imask.updateValue();
        if (t.inputmask?.refreshValue) t.inputmask.refreshValue();
      }
    };
    document.addEventListener('focusin', maskGuard, true);
    document.addEventListener('input',   maskGuard, true);
    document.addEventListener('change',  maskGuard, true);
    document.addEventListener('click',   maskGuard, true);
    document.addEventListener('keydown', maskGuard, true);

    // ручное закрытие «спасибо»
    document.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof Element)) return;
      if (t.hasAttribute('data-micromodal-close')) {
        const m = t.closest('#modal-2');
        if (m) { blurIfInside(m); setTimeout(unlockScrollIfStuck, 0); }
      }
    }, true);
  }
})();
