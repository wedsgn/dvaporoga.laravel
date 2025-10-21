(() => {
  'use strict';

  // ===== helpers =====
  const q  = (sel, root=document) => root.querySelector(sel);
  const qa = (sel, root=document) => Array.from(root.querySelectorAll(sel));

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

  const unlockScrollIfStuck = () => {
    document.body.classList.remove('micromodal-open', 'modal-open', 'is-open');
    document.body.style.removeProperty('overflow');
    document.documentElement.style.removeProperty('overflow');
  };

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

  const syncMasks = (form) => {
    qa('input[type="tel"], [data-mask], .js-mask', form).forEach((inp) => {
      syncMaskInput(inp);
      inp.dispatchEvent(new Event('input', { bubbles: true }));
    });
  };

  const closeFormModalIfAny = (form) => {
    const modal = form.closest('.modal');
    if (!modal) return;
    blurIfInside(modal);
    const id = modal.id;
    if (id && window.MicroModal?.close) { try { MicroModal.close(id); } catch {} }
    else modal.setAttribute('aria-hidden', 'true');
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

  // ===== ошибки формы =====
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

  const clearErrors = (form) => {
    qa('.field-error', form).forEach(n => { n.textContent = ''; });
    qa('.input.is-invalid, input.is-invalid, select.is-invalid, textarea.is-invalid', form)
      .forEach(el => { el.classList.remove('is-invalid'); el.removeAttribute('aria-invalid'); });
    qa('[data-added-describedby]', form).forEach(el => {
      const orig = el.getAttribute('data-added-describedby');
      if (orig) el.setAttribute('aria-describedby', orig);
      else el.removeAttribute('aria-describedby');
      el.removeAttribute('data-added-describedby');
    });
  };

  const showFieldError = (form, field, message) => {
    const input = q(`[name="${CSS.escape(field)}"]`, form);
    if (!input) return false;
    input.classList.add('is-invalid');
    input.setAttribute('aria-invalid', 'true');
    const box = ensureErrorBox(input);
    box.textContent = message || 'Поле заполнено некорректно';
    const prev = input.getAttribute('aria-describedby');
    if (prev) input.setAttribute('data-added-describedby', prev);
    input.setAttribute('aria-describedby', box.id);
    return true;
  };

  const focusFirstInvalid = (form) => {
    const invalid = q('.is-invalid', form);
    if (invalid) { try { invalid.focus({ preventScroll: false }); } catch {} }
  };

  // ===== отправка =====
  const setSubmitting = (form, on) => {
    const btn = q('button[type="submit"], .submit-modal', form);
    if (btn) {
      btn.disabled = !!on;
      btn.classList.toggle('is-loading', !!on);
      if (on) btn.setAttribute('aria-busy', 'true'); else btn.removeAttribute('aria-busy');
    }
    form.dataset.submitting = on ? '1' : '';
  };

  const handleResponseErrors = async (form, res) => {
    let payload = null;
    try { payload = await res.clone().json(); } catch {}
    if (res.status === 422 && payload && payload.errors && typeof payload.errors === 'object') {
      clearErrors(form);
      Object.keys(payload.errors).forEach((field) => {
        const msg = Array.isArray(payload.errors[field]) ? payload.errors[field][0] : (payload.errors[field] || '');
        showFieldError(form, field, msg);
      });
      focusFirstInvalid(form);
      return;
    }
    if (res.status === 419 || res.status === 401) {
      alert('Сессия истекла. Обновите страницу и попробуйте снова.');
      return;
    }
    const msg = (payload && (payload.message || payload.error)) || `Ошибка (${res.status})`;
    alert(msg);
  };

  const onSubmit = async (e) => {
    const form = e.target instanceof HTMLFormElement ? e.target : null;
    if (!form || !form.matches('.modal-form-product')) return;

    e.preventDefault();
    e.stopPropagation();

    if (form.dataset.submitting === '1') return;

    const url  = getUrl(form);
    if (!url) return;

    const fd   = new FormData(form);
    const csrf = getCsrf(form);

    clearErrors(form);
    setSubmitting(form, true);

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
        form.reset();
        syncMasks(form);
        closeFormModalIfAny(form);
        moveFocusToPage();
        setTimeout(openThanks, 0);
      } else {
        await handleResponseErrors(form, res);
      }
    } catch (err) {
      alert('Сеть недоступна или сервер не ответил.');
    } finally {
      setSubmitting(form, false);
    }
  };

  // ===== улучшатели =====
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

  const attachModalCloseFix = () => {
    document.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof Element)) return;
      if (t.hasAttribute('data-micromodal-close')) {
        const modal = t.closest('.modal');
        if (modal) blurIfInside(modal);
        if (modal && modal.id === 'modal-2') {
          setTimeout(unlockScrollIfStuck, 0);
        }
      }
    }, true);
  };

  // ===== init =====
  const init = () => {
    document.addEventListener('submit', onSubmit, true);
    attachMaskGuards();
    attachModalCloseFix();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
})();
