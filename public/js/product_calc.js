(() => {
  'use strict';

  const cartForm = document.getElementById('cart-form');
  const products = document.querySelectorAll('.product-part');

  const cartItemsPlace  = document.querySelector('.total-form__parts');
  const totalPriceInput = document.querySelector('.product-form__price');
  const arrayFormInput  = document.querySelector('.product-form__array');
  const clearCart       = document.querySelector('.product-parts__total_clear');
  const totalPriceDiv   = document.querySelector('.total-form__total_value span');

  let selectedItems = [];
  let totalPrice = 0;
  let checkedProducts = []; // как было — не трогаю

  // ---------- helpers ----------
  const getEndpoint = (form) =>
    (form.getAttribute('data-action') || form.getAttribute('action') || '').trim();

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
      }, 5000); // автозакрытие через 5с
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

  // ---------- корзина ----------
  clearCart?.addEventListener('click', () => {
    selectedItems = [];
    totalPrice = 0;
    checkSum();
    pushArray();
    cartItemsPlace?.insertAdjacentHTML(
      'afterbegin',
      `<div class="total-form__empty">Добавьте запчасти в заказ</div>`
    );
  });

  const checkSum = () => {
    if (totalPriceDiv) totalPriceDiv.innerHTML = totalPrice;
    if (totalPriceInput) totalPriceInput.value = totalPrice;
    if (arrayFormInput) arrayFormInput.value = JSON.stringify(selectedItems);
  };

  const pushArray = () => {
    if (!cartItemsPlace) return;
    cartItemsPlace.innerHTML = '';
    selectedItems.forEach((item) => {
      cartItemsPlace.insertAdjacentHTML(
        'afterbegin',
        `<div class="total-form__part">
          <p class="total-form__part_title">${item.title}</p>
          <p class="total-form__part_price">${item.price} р.</p>
          <p class="total-form__part_price" style="display: none">${item}</p>
        </div>`
      );
    });
  };

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
          data: item.getAttribute('data-item'),
          title: title,
          price: price,
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
  });

  if (products && products.length) {
    products.forEach((product) => {
      const data = product.getAttribute('data-item');
      if (!data) return;
      let res;
      try { res = JSON.parse(data); } catch { return; }

      const steelSelector     = product.querySelector('.steel-select');
      const thicknessSelector = product.querySelector('.thickness_select');
      const typeSelector      = product.querySelector('.type-selector');
      const sizeSelector      = product.querySelector('.size-selector');
      const priceDeiv         = product.querySelector('.product-price span');

      if (!(steelSelector && thicknessSelector && typeSelector && sizeSelector && priceDeiv)) return;

      let options = {
        size_id:         sizeSelector.value,
        steel_type_id:   steelSelector.value,
        thickness_id:    thicknessSelector.value,
        type_id:         typeSelector.value,
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

      steelSelector.addEventListener('change', (e) => {
        options.steel_type_id = +e.target.value;
        getPrice();
      });
      thicknessSelector.addEventListener('change', (e) => {
        options.thickness_id = +e.target.value;
        getPrice();
      });
      typeSelector.addEventListener('change', (e) => {
        options.type_id = +e.target.value;
        getPrice();
      });
      sizeSelector.addEventListener('change', (e) => {
        options.size_id = +e.target.value;
        getPrice();
      });

      priceDeiv.innerHTML = '';
      getPrice();
    });
  }

  // ---------- отправка формы ----------
  if (cartForm) {
    cartForm.addEventListener('submit', async (event) => {
      event.preventDefault();

      // ещё раз актуализируем скрытые поля перед отправкой
      checkSum();

      const url = getEndpoint(cartForm);
      if (!url) {
        console.error('[cart-form] Нет action/data-action у формы');
        return;
      }

      const csrf =
        cartForm.querySelector('input[name="_token"]')?.value ||
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      const formData = new FormData(cartForm);

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

        if (response.ok) {
          // сброс формы и локального состояния корзины
          cartForm.reset();
          selectedItems = [];
          totalPrice = 0;
          checkSum();
          pushArray();

          // синхронизируем маску и показываем «спасибо»
          syncMasks(cartForm);
          openThanks();
        } else {
          let msg = 'Ошибка отправки';
          try { msg = (await response.json())?.message || msg; } catch {}
          alert(msg);
        }
      } catch (err) {
        console.error('[cart-form] network error:', err);
        alert('Сеть недоступна или сервер не ответил.');
      }
    });

    // маска — тишина при автозаполнении
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

    // ручное закрытие «спасибо» — снимаем фокус и разблокируем скролл
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
