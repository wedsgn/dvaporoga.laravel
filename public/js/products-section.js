(() => {
  'use strict';

  // --- helpers ---
  const getUrl = (form) =>
    (form.getAttribute('data-action') || form.getAttribute('action') || '').trim();

  const getCsrf = (form) =>
    form.querySelector('input[name="_token"]')?.value ||
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  const blurIfInside = (root) => {
    const ae = document.activeElement;
    if (ae && root && root.contains(ae)) { try { ae.blur(); } catch {} }
  };

  const moveFocusToPage = () => {
    const tgt = document.querySelector('main,[role="main"]') || document.body;
    const had = tgt.hasAttribute('tabindex');
    if (!had) tgt.setAttribute('tabindex', '-1');
    try { tgt.focus({ preventScroll: true }); } catch {}
    if (!had) tgt.removeAttribute('tabindex');
  };

  // синхронизация маски одного input
  const syncMaskInput = (input) => {
    if (!input) return;
    // iMask
    if (input._imask && typeof input._imask.updateValue === 'function') {
      try { input._imask.updateValue(); return; } catch {}
    }
    // Inputmask
    if (input.inputmask) {
      try {
        if (typeof input.inputmask.refreshValue === 'function') input.inputmask.refreshValue();
        else if (typeof input.inputmask.setValue === 'function') input.inputmask.setValue(input.value || '');
        return;
      } catch {}
    }
  };

  // синхронизация масок всех полей формы (после reset/автозаполнения)
  const syncMasks = (form) => {
    form.querySelectorAll('input[type="tel"], [data-mask], .js-mask').forEach((inp) => {
      syncMaskInput(inp);
      // на крайний случай дёрнем событие
      inp.dispatchEvent(new Event('input', { bubbles: true }));
    });
  };

  // закрыть модал, в котором находится форма
  const closeFormModalIfAny = (form) => {
    const modal = form.closest('.modal');
    if (!modal) return;
    blurIfInside(modal);
    const id = modal.id;
    if (id && window.MicroModal?.close) { try { MicroModal.close(id); } catch {} }
    else modal.setAttribute('aria-hidden', 'true');
  };

  // разблокировать скролл, если библиотека вдруг оставила блокировку
  const unlockScrollIfStuck = () => {
    document.body.classList.remove('micromodal-open', 'modal-open', 'is-open');
    document.body.style.removeProperty('overflow');
    document.documentElement.style.removeProperty('overflow');
  };

  let thanksTimer = null;
  const openThanks = () => {
    if (window.MicroModal?.show) {
      try { MicroModal.show('modal-2'); } catch {}
      // автозакрытие через 5 секунд
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

  // --- отправка формы карточки товара ---
  const onSubmit = async (e) => {
    const form = e.target instanceof HTMLFormElement ? e.target : null;
    if (!form || !form.matches('.modal-form-product')) return;

    e.preventDefault();
    e.stopPropagation();

    const url  = getUrl(form);
    if (!url) { console.error('[product-forms] Нет action/data-action'); return; }

    const fd   = new FormData(form);
    const csrf = getCsrf(form);

    try {
      const res = await fetch(url, {
        method: form.getAttribute('method') || 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
        },
        body: fd,
        credentials: 'same-origin'
      });

      if (res.ok) {
        // сбросить поля и синхронизировать маски
        form.reset();
        syncMasks(form);

        // закрыть модал с формой
        closeFormModalIfAny(form);

        // безопасно перевести фокус и показать «спасибо»
        moveFocusToPage();
        setTimeout(openThanks, 0);
      } else {
        let msg = 'Ошибка отправки';
        try { msg = (await res.json())?.message || msg; } catch {}
        alert(msg);
      }
    } catch (err) {
      console.error('[product-forms] network error:', err);
      alert('Сеть недоступна или сервер не ответил.');
    }
  };

  // делегированный перехват submit (в capture, чтобы не ушёл обычный сабмит)
  const attachSubmit = () => {
    document.addEventListener('submit', onSubmit, true);
  };

  // синхронизируем маску при автозаполнении/фокусе/клике
  const attachMaskGuards = () => {
    const handler = (e) => {
      const t = e.target;
      if (!(t instanceof HTMLInputElement)) return;
      if (t.matches('input[type="tel"], [data-mask], .js-mask')) syncMaskInput(t);
    };
    document.addEventListener('focusin', handler, true);
    document.addEventListener('input', handler, true);
    document.addEventListener('change', handler, true);
    document.addEventListener('click', handler, true);
    document.addEventListener('keydown', handler, true);
  };

  // когда пользователь закрывает модал по крестику/оверлею — снять фокус, разблокировать скролл
  const attachModalCloseFix = () => {
    document.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof Element)) return;
      if (t.hasAttribute('data-micromodal-close')) {
        const modal = t.closest('.modal');
        if (modal) blurIfInside(modal);
        // если это «спасибо», подчистим блокировки
        if (modal && modal.id === 'modal-2') {
          setTimeout(unlockScrollIfStuck, 0);
        }
      }
    }, true);
  };

  // init
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      attachSubmit();
      attachMaskGuards();
      attachModalCloseFix();
    }, { once: true });
  } else {
    attachSubmit();
    attachMaskGuards();
    attachModalCloseFix();
  }
})();
