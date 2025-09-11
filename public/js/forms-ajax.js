(() => {
  'use strict';

  const SEL = 'form.index-hero-form, form.footer-form, form.modal-form';
  const BUSY = new WeakSet();

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

  // --- маски телефонов ---
  const syncMaskInput = (input) => {
    if (!input) return;
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
  };

  const syncMasksInForm = (form) => {
    form.querySelectorAll('input[type="tel"], [data-mask], .js-mask').forEach((inp) => {
      syncMaskInput(inp);
      inp.dispatchEvent(new Event('input', { bubbles: true }));
    });
  };

  // --- ошибки/лоадер (как у тебя) ---
  const setLoading = (form, on) => {
    const btn = form.querySelector('[type="submit"]');
    if (btn) {
      btn.disabled = on;
      if (!btn.querySelector('img')) {
        if (on) { if (!btn.dataset._t) btn.dataset._t = btn.textContent; btn.textContent = 'Отправка...'; }
        else if (btn.dataset._t) { btn.textContent = btn.dataset._t; }
      }
    }
    form.classList.toggle('is-loading', on);
  };

  const clearErrors = (form) => {
    form.classList.remove('has-errors');
    form.querySelectorAll('.field-error').forEach(n => n.remove());
  };

  const showErrors = (form, errors) => {
    form.classList.add('has-errors');
    if (!errors || typeof errors !== 'object') return;
    Object.entries(errors).forEach(([field, msgs]) => {
      const input = form.querySelector(`[name="${field}"]`);
      if (!input) return;
      const el = document.createElement('div');
      el.className = 'field-error';
      el.style.cssText = 'color:#d00;font-size:12px;margin-top:6px;';
      el.textContent = Array.isArray(msgs) ? msgs.join(' ') : String(msgs);
      input.insertAdjacentElement('afterend', el);
    });
  };

  const parseJsonIfAny = async (res) => {
    const ct = res.headers.get('content-type') || '';
    if (ct.includes('application/json')) { try { return await res.json(); } catch {} }
    return null;
  };

  // --- модалки ---
  const unlockScrollIfStuck = () => {
    document.body.classList.remove('micromodal-open', 'modal-open', 'is-open');
    document.body.style.removeProperty('overflow');
    document.documentElement.style.removeProperty('overflow');
  };

  let thanksTimer = null;
  const openSuccess = () => {
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

  const closeModal1IfNeeded = (form) => {
    const m1 = document.getElementById('modal-1');
    if (!m1 || !form.closest('#modal-1')) return;
    blurIfInside(m1);
    if (window.MicroModal?.close) { try { MicroModal.close('modal-1'); } catch {} }
    else m1.setAttribute('aria-hidden', 'true');
    unlockScrollIfStuck();
  };

  // --- submit handler ---
  const handle = async (e) => {
    const form = e.target?.closest?.('form');
    if (!form || !form.matches(SEL)) return;

    e.preventDefault();
    e.stopPropagation();
    if (BUSY.has(form)) return;
    BUSY.add(form);

    clearErrors(form);

    const url = getUrl(form);
    if (!url) { BUSY.delete(form); return; }

    const fd   = new FormData(form);
    const csrf = getCsrf(form);

    setLoading(form, true);

    try {
      const res  = await fetch(url, {
        method: form.getAttribute('method') || 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
        },
        body: fd,
        credentials: 'same-origin'
      });
      const data = await parseJsonIfAny(res);

      if (res.ok) {
        // сбросить поля и синхронизировать маски (чтобы не ругались)
        form.reset();
        syncMasksInForm(form);

        // закрыть #modal-1, если форма оттуда
        closeModal1IfNeeded(form);

        // безопасно перевести фокус и показать «спасибо»
        moveFocusToPage();
        setTimeout(openSuccess, 0);
      } else if (res.status === 422 && data) {
        showErrors(form, data.errors || data);
      } else {
        const msg = (data && (data.message || data.error)) || `Ошибка (${res.status}). Попробуйте позже.`;
        alert(msg);
      }
    } catch (err) {
      console.error('[common-forms] submit error:', err);
      alert('Сеть недоступна или сервер не ответил.');
    } finally {
      setLoading(form, false);
      BUSY.delete(form);
    }
  };

  // --- крепления ---
  const attachDirect = () => {
    document.querySelectorAll(SEL).forEach(form => {
      if (form.dataset._ajaxBound) return;
      form.addEventListener('submit', handle, { capture: true });
      form.dataset._ajaxBound = '1';
      if (!form.hasAttribute('action') && !form.hasAttribute('data-action')) form.setAttribute('action', '#');
      form.setAttribute('novalidate', 'novalidate');
    });
  };

  // глобальный перехват для динамики
  document.addEventListener('submit', handle, true);

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachDirect, { once: true });
  } else {
    attachDirect();
  }

  // чин фокуса при ручном закрытии модалок
  document.addEventListener('click', (e) => {
    const t = e.target;
    if (!(t instanceof Element)) return;
    if (t.hasAttribute('data-micromodal-close')) {
      const m = t.closest('.modal');
      if (m) blurIfInside(m);
      if (m && m.id === 'modal-2') setTimeout(unlockScrollIfStuck, 0);
    }
  }, true);

  // тихая инициализация MicroModal, если есть
  if (window.MicroModal?.init) {
    try { MicroModal.init({ awaitCloseAnimation: true }); } catch {}
  }

  // синхронизация маски при автозаполнении
  const maskGuard = (e) => {
    const t = e.target;
    if (!(t instanceof HTMLInputElement)) return;
    if (t.matches('input[type="tel"], [data-mask], .js-mask')) syncMaskInput(t);
  };
  document.addEventListener('focusin', maskGuard, true);
  document.addEventListener('input', maskGuard, true);
  document.addEventListener('change', maskGuard, true);
  document.addEventListener('click', maskGuard, true);
  document.addEventListener('keydown', maskGuard, true);
})();
