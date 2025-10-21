/* ===== 1) Марка → Модель (AJAX) ===== */
(function chooseCarInit(){
  var makeEl  = document.getElementById('choose-make');
  var modelEl = document.getElementById('choose-model');
  if (!makeEl || !modelEl) return;

  var route = modelEl.dataset.modelsUrl; // из data-models-url
  if (!route) { console.warn('[choose-car] route empty'); return; }

  function ensureChoices(el){
    if (!window.Choices) return null;
    if (el.choices && typeof el.choices.setChoices === 'function') return el.choices;
    return new Choices(el, { shouldSort:false, searchEnabled:true, placeholder:true, itemSelectText:'' });
  }

  function destroyModelChoices(){
    try { if (modelEl.choices && typeof modelEl.choices.destroy === 'function') modelEl.choices.destroy(); } catch(e){}
    var wrap = modelEl.nextElementSibling;
    if (wrap && wrap.classList && wrap.classList.contains('choices')) wrap.remove();
    modelEl.disabled = false;
    modelEl.removeAttribute('aria-disabled');
    modelEl.classList.remove('is-disabled');
  }

  function setModelPlaceholder(text){
    destroyModelChoices();
    modelEl.innerHTML = '';
    var o = document.createElement('option');
    o.value = ''; o.textContent = text || 'Модель';
    o.disabled = true; o.selected = true;
    modelEl.appendChild(o);
    modelEl.disabled = true;
  }
  function setModelLoading(){ setModelPlaceholder('Загрузка…'); }
  function setModelError(){ setModelPlaceholder('Ошибка загрузки'); }

  function buildModelOptions(list, preselect){
    destroyModelChoices();
    modelEl.innerHTML = '';
    var ph = document.createElement('option');
    ph.value=''; ph.textContent='Модель'; ph.disabled=true; ph.selected=true;
    modelEl.appendChild(ph);

    (list||[]).forEach(function(m){
      var opt = document.createElement('option');
      opt.value = String(m.id);
      opt.textContent = m.title;
      if (preselect && String(preselect)===String(m.id)) opt.selected = true;
      modelEl.appendChild(opt);
    });

    var inst = ensureChoices(modelEl);
    if (inst && preselect) inst.setChoiceByValue(String(preselect));
    modelEl.disabled = false;

    var wrap = modelEl.closest('.choices');
    if (wrap){ wrap.classList.remove('is-disabled'); wrap.removeAttribute('aria-disabled'); }
  }

  async function loadModels(makeId, preselect){
    try{
      setModelLoading();
      var res = await fetch(route + '?make_id=' + encodeURIComponent(makeId), {
        headers:{ 'Accept':'application/json' }, cache:'no-store', credentials:'same-origin'
      });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      var data = await res.json();
      buildModelOptions(data, preselect || null);
    }catch(e){
      console.error('[choose-car] loadModels failed:', e);
      setModelError();
    }
  }

  function onMakeChange(preselect){
    var id = makeEl.value;
    if (!id){ setModelPlaceholder('Модель'); return; }
    loadModels(id, preselect || null);
  }

  // Инициализируем Choices у МАРКИ при необходимости
  ensureChoices(makeEl);

  // Слушаем смену марки
  makeEl.addEventListener('change', function(){ onMakeChange(null); });

  // Первичный рендер
  if (makeEl.value) onMakeChange(null);
  else setModelPlaceholder('Сначала выберите марку');
})();

/* ===== 2) Сабмит формы (AJAX + показ ошибок) ===== */
(function setupChooseCarSubmit(){
  const form = document.getElementById('choose-car-form');
  if (!form) return;

  const statusEl  = form.querySelector('.form-status');
  const submitBtn = form.querySelector('button[type="submit"]');

  function setStatus(msg, ok=false){
    if (!statusEl) return;
    statusEl.textContent = msg || '';
    statusEl.style.color = ok ? '#0a7b28' : '#d00';
  }
  function clearErrors(){
    form.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.choices.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  }
  function showFieldError(name, message){
    const holder = form.querySelector(`.field-error[data-error-for="${name}"]`);
    if (holder) holder.textContent = message;
    const field = form.querySelector(`[name="${name}"]`);
    if (field){
      field.classList.add('is-invalid');
      const next = field.nextElementSibling;
      const wrap = (next && next.classList && next.classList.contains('choices')) ? next : field.closest('.choices');
      if (wrap && wrap.classList) wrap.classList.add('is-invalid');
    }
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    clearErrors(); setStatus(''); submitBtn.disabled = true;

    try{
      const fd = new FormData(form);
      const res = await fetch(form.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: fd, credentials: 'same-origin', cache: 'no-store'
      });

      if (res.status === 201 || res.ok){
        setStatus('Заявка отправлена. Спасибо!', true);
        form.reset();

        // сброс марки (Choices)
        const makeEl = document.getElementById('choose-make');
        if (makeEl?.choices){
          makeEl.choices.removeActiveItems();
          makeEl.choices.setChoiceByValue('');
        } else if (makeEl){
          makeEl.value = '';
        }

        // вернуть модель к плейсхолдеру
        const modelEl = document.getElementById('choose-model');
        if (modelEl){
          try { modelEl.choices?.destroy?.(); } catch(e){}
          const wrap = modelEl.nextElementSibling;
          if (wrap && wrap.classList?.contains('choices')) wrap.remove();
          modelEl.innerHTML = '<option value="" disabled selected>Сначала выберите марку</option>';
          modelEl.disabled = true;
        }
        return;
      }

      if (res.status === 422){
        const data = await res.json();
        const errors = data.errors || {};
        Object.keys(errors).forEach(name => {
          const msg = Array.isArray(errors[name]) ? errors[name][0] : String(errors[name]);
          showFieldError(name, msg);
        });
        setStatus('Исправьте ошибки в форме.');
        return;
      }

      setStatus('Ошибка при отправке. Попробуйте позже.');
    } catch(err){
      console.error('[choose-car] submit failed:', err);
      setStatus('Сетевая ошибка. Проверьте соединение.');
    } finally {
      submitBtn.disabled = false;
    }
  });
})();
