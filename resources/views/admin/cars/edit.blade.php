@extends('layouts.admin')

@section('content')

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">
          {{ __('admin.edit_car_model_card_title') }} {{ $item->title }}
        </h4>
      </div>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger alert-border-left alert-dismissible fade show " role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif
  </div>

  <style>
    .offer-handle, .tag-handle { cursor: grab; user-select: none; }
    .offer-row.opacity-50, .tag-row.opacity-50 { opacity: .5; }
  </style>

  <div class="row">
    @if (!empty($item->image_mob))
      <div class="col-xxl-6">
        <div class="card">
          <div class="card-body">
            <p class="card-title-desc text-muted">{{ __('admin.field_current_image_mob') }}</p>
            <div class="live-preview">
              <div>
                @if ($item->image == 'default')
                  <img src="{{ asset('images/cars/merc.png') }}" class="img-fluid" alt="Responsive image">
                @else
                  <img src="{{ asset('storage') . '/' . $item->image }}" class="img-fluid" alt="Responsive image">
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    @if (!empty($item->image))
      <div class="col-xxl-6">
        <div class="card">
          <div class="card-body">
            <p class="card-title-desc text-muted">{{ __('admin.field_current_image') }}</p>
            <div class="live-preview">
              <div>
                @if ($item->image == 'default')
                  <img src="{{ asset('images/cars/merc.png') }}" class="img-fluid" alt="Responsive image">
                @else
                  <img src="{{ asset('storage') . '/' . $item->image }}" class="img-fluid" alt="Responsive image">
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="live-preview">
          <form action="{{ route('admin.cars.update', $item->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="row gy-4">
              <div class="col-xxl-6 col-md-6">
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_title') }} *</label>
                  <input type="text" value="{{ $item->title }}" class="form-control" id="valueInput" name="title" placeholder="{{ __('admin.placeholder_text') }}">
                  <input type="hidden" name="old_title" value="{{ $item->title }}">
                </div>
              </div>

              <div class="col-xxl-6 col-md-6">
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_generation') }}</label>
                  <input type="text" value="{{ $item->generation }}" class="form-control" id="valueInput" name="generation" placeholder="{{ __('admin.placeholder_text') }}">
                </div>
              </div>

              <div class="col-xxl-6 col-md-6">
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_years') }}</label>
                  <input type="text" value="{{ $item->years }}" class="form-control" id="valueInput" name="years" placeholder="{{ __('admin.placeholder_text') }}">
                </div>
              </div>

              <div class="col-xxl-6 col-md-6">
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_body') }} *</label>
                  <input type="text" value="{{ $item->body }}" class="form-control" id="valueInput" name="body" placeholder="{{ __('admin.placeholder_text') }}">
                </div>
              </div>

              <div class="col-xxl-6 col-md-6">
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_artikul') }} *</label>
                  <input type="text" value="{{ $item->artikul }}" class="form-control" id="valueInput" name="artikul" placeholder="{{ __('admin.placeholder_text') }}">
                </div>
              </div>

              <div class="col-xxl-6 col-md-6">
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_top') }} *</label>
                  <input type="text" value="{{ $item->top }}" class="form-control" id="valueInput" name="top" placeholder="{{ __('admin.placeholder_text') }}">
                </div>
              </div>

              <div class="col-xxl-6 col-md-6">
                <label for="valueInput" class="form-label">{{ __('admin.car_model_card_title') }} *</label>
                @if (!count($car_models) == 0)
                  <select type="text" data-choices class="form-control" name="car_model_id" id="valueInput">
                    @foreach ($car_models as $car_model)
                      <option value="{{ $car_model->title }}" {{ $car_model->id == $item->car_model->id ? 'selected' : '' }}>
                        {{ $car_model->title }}
                      </option>
                    @endforeach
                  </select>
                @else
                  <div class="text-danger">{{ __('admin.notification_no_entries_car_models') }}</div>
                @endif
              </div>

              <div class="col-xxl-6 col-md-6">
                <div>
                  <label for="formFile" class="form-label">{{ __('admin.field_image_mob') }}</label>
                  <input class="form-control" type="file" id="formFile" name="image_mob">
                </div>
              </div>

              <div class="col-xxl-6 col-md-6">
                <div>
                  <label for="formFile" class="form-label">{{ __('admin.field_image') }}</label>
                  <input class="form-control" type="file" id="formFile" name="image">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label" for="basic-default-message">{{ __('admin.field_description') }} *</label>
                <textarea id="basic-default-message" class="form-control" name="description" placeholder="{{ __('admin.placeholder_text') }}" style="height: 234px;">{{ $item->description }}</textarea>
              </div>
            </div>

            <div class="row gy-4">
              <div class="card-header align-items-center d-flex"></div>

              <div class="col-xxl-6 col-md-6">
                <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.title_seo') }}</h4>
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_meta_title') }}</label>
                  <input type="text" value="{{ $item->meta_title }}" class="form-control" id="valueInput" name="meta_title" placeholder="{{ __('admin.placeholder_text') }}">
                </div>
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_meta_keywords') }}</label>
                  <input type="text" value="{{ $item->meta_keywords }}" class="form-control" id="valueInput" name="meta_keywords" placeholder="{{ __('admin.placeholder_text') }}">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">{{ __('admin.field_meta_description') }}</label>
                <textarea id="editor" class="form-control" name="meta_description" placeholder="{{ __('admin.placeholder_text') }}" style="height: 234px;">{{ $item->meta_description }}</textarea>
              </div>

              <div class="col-xxl-6 col-md-6">
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_og_url') }}</label>
                  <input type="text" value="{{ $item->og_url }}" class="form-control" id="valueInput" name="og_url" placeholder="{{ __('admin.placeholder_text') }}">
                </div>
                <div>
                  <label for="valueInput" class="form-label">{{ __('admin.field_og_title') }}</label>
                  <input type="text" value="{{ $item->og_title }}" class="form-control" id="valueInput" name="og_title" placeholder="{{ __('admin.placeholder_text') }}">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">{{ __('admin.field_og_description') }}</label>
                <textarea id="editor" class="form-control" name="og_description" placeholder="{{ __('admin.placeholder_text') }}" style="height: 234px;">{{ $item->og_description }}</textarea>
              </div>
            </div>

            <div class="d-flex gap-2 flex-wrap mt-3">
              <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tagsModal">
                Редактировать теги
              </button>

              <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#offersModal">
                Редактировать акции
              </button>

              <button type="submit" class="btn btn-soft-success waves-effect waves-light ms-auto">
                {{ __('admin.btn_save') }}
              </button>
            </div>

            {{-- OFFERS MODAL --}}
            <div class="modal fade" id="offersModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Акции для {{ $item->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <div class="text-muted">Перетаскивай карточки, чтобы менять порядок.</div>
                      <button type="button" class="btn btn-sm btn-success" id="offerAddBtn">+ Добавить акцию</button>
                    </div>

                    <div id="offersRows">
                      @php $offers = $item->offers ?? collect(); @endphp
                      @foreach ($offers as $i => $offer)
                        <div class="border rounded p-3 mb-3 offer-row" data-offer-row draggable="true">
                          <div class="row g-2 align-items-end">
                            <div class="col-md-1 d-flex align-items-end">
                              <button type="button" class="btn btn-sm btn-secondary js-drag-handle" aria-label="Перетащить">⇅</button>
                              <input type="hidden" name="offers[{{ $i }}][sort]" value="{{ $offer->sort ?? ((($i+1)*10)) }}">
                            </div>

                            <div class="col-md-3">
                              <label class="form-label">Заголовок</label>
                              <input class="form-control" name="offers[{{ $i }}][title]" value="{{ $offer->title }}">
                            </div>

                            <div class="col-md-2">
                              <label class="form-label">Цена от</label>
                              <input class="form-control" name="offers[{{ $i }}][price_from]" value="{{ $offer->price_from }}">
                            </div>

                            <div class="col-md-2">
                              <label class="form-label">Старая цена</label>
                              <input class="form-control" name="offers[{{ $i }}][price_old]" value="{{ $offer->price_old }}">
                            </div>

                            <div class="col-md-1">
                              <label class="form-label">Валюта</label>
                              <input class="form-control" name="offers[{{ $i }}][currency]" value="{{ $offer->currency ?? '₽' }}">
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="offers[{{ $i }}][is_active]" value="1" {{ $offer->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">Активна</label>
                              </div>
                            </div>

                            <div class="col-md-1 d-flex align-items-end justify-content-end">
                              <button type="button" class="btn btn-sm btn-danger offerRemoveBtn">Удалить</button>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>

                    <template id="offerRowTpl">
                      <div class="border rounded p-3 mb-3 offer-row" data-offer-row draggable="true">
                        <div class="row g-2 align-items-end">
                          <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-secondary js-drag-handle" aria-label="Перетащить">⇅</button>
                            <input type="hidden" name="__NAME__[sort]" value="1000">
                          </div>

                          <div class="col-md-3">
                            <label class="form-label">Заголовок</label>
                            <input class="form-control" name="__NAME__[title]" value="">
                          </div>

                          <div class="col-md-2">
                            <label class="form-label">Цена от</label>
                            <input class="form-control" name="__NAME__[price_from]" value="">
                          </div>

                          <div class="col-md-2">
                            <label class="form-label">Старая цена</label>
                            <input class="form-control" name="__NAME__[price_old]" value="">
                          </div>

                          <div class="col-md-1">
                            <label class="form-label">Валюта</label>
                            <input class="form-control" name="__NAME__[currency]" value="₽">
                          </div>

                          <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="__NAME__[is_active]" value="1" checked>
                              <label class="form-check-label">Активна</label>
                            </div>
                          </div>

                          <div class="col-md-1 d-flex align-items-end justify-content-end">
                            <button type="button" class="btn btn-sm btn-danger offerRemoveBtn">Удалить</button>
                          </div>
                        </div>
                      </div>
                    </template>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Готово</button>
                  </div>
                </div>
              </div>
            </div>

            {{-- TAGS MODAL --}}
            <div class="modal fade" id="tagsModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Теги (плашки) для {{ $item->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <div class="text-muted">Перетаскивай карточки, чтобы менять порядок.</div>
                      <button type="button" class="btn btn-sm btn-success" id="tagAddBtn">+ Добавить тег</button>
                    </div>

                    <div id="tagsRows">
                      @php $tags = $item->tags ?? collect(); @endphp
                      @foreach ($tags as $i => $tag)
                        <div class="border rounded p-3 mb-2 tag-row" data-tag-row draggable="true">
                          <div class="row g-2 align-items-end">
                            <div class="col-md-1 d-flex align-items-end">
                              <button type="button" class="btn btn-sm btn-secondary js-drag-handle" aria-label="Перетащить">⇅</button>
                              <input type="hidden" name="tags[{{ $i }}][sort]" value="{{ $tag->sort ?? ((($i+1)*10)) }}">
                            </div>

                            <div class="col-md-9">
                              <label class="form-label">Текст</label>
                              <input class="form-control tag-input"
                                     name="tags[{{ $i }}][title]"
                                     value="{{ $tag->title }}"
                                     placeholder="Например: Доставка по РФ">
                            </div>

                            <div class="col-md-2 d-flex justify-content-end align-items-end">
                              <button type="button" class="btn btn-sm btn-danger tagRemoveBtn">Удалить</button>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>

                    <template id="tagRowTpl">
                      <div class="border rounded p-3 mb-2 tag-row" data-tag-row draggable="true">
                        <div class="row g-2 align-items-end">
                          <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-secondary js-drag-handle" aria-label="Перетащить">⇅</button>
                            <input type="hidden" name="tags[__I__][sort]" value="1000">
                          </div>

                          <div class="col-md-9">
                            <label class="form-label">Текст</label>
                            <input class="form-control tag-input" name="tags[__I__][title]" value="" placeholder="Например: Без предоплаты">
                          </div>

                          <div class="col-md-2 d-flex justify-content-end align-items-end">
                            <button type="button" class="btn btn-sm btn-danger tagRemoveBtn">Удалить</button>
                          </div>
                        </div>
                      </div>
                    </template>

                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Готово</button>
                  </div>
                </div>
              </div>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<style>
  .js-drag-handle{
    cursor: grab;
    user-select: none;
    touch-action: none;
  }
  body.dragging-sort .js-drag-handle{ cursor: grabbing; }

  .drag-ghost{
    position: fixed;
    z-index: 99999;
    pointer-events: none;
    opacity: .92;
    transform: translate3d(0,0,0);
  }
  .drag-placeholder{
    border: 2px dashed rgba(255,255,255,.25);
    border-radius: 10px;
    background: rgba(255,255,255,.04);
  }
</style>

<script>
(function () {
  function closest(el, sel) {
    while (el && el !== document) {
      if (el.matches && el.matches(sel)) return el;
      el = el.parentNode;
    }
    return null;
  }

  function lockModalScroll(container) {
    document.body.classList.add('dragging-sort');

    const modalBody = container.closest ? container.closest('.modal-body') : closest(container, '.modal-body');
    if (modalBody) modalBody.dataset.prevOverflow = modalBody.style.overflow || '';
    if (modalBody) modalBody.style.overflow = 'hidden';

    window.__sortBlockTouchMove = function(ev){ ev.preventDefault(); };
    window.addEventListener('touchmove', window.__sortBlockTouchMove, { passive: false });

    return function unlock() {
      document.body.classList.remove('dragging-sort');
      if (modalBody) modalBody.style.overflow = modalBody.dataset.prevOverflow || '';
      if (window.__sortBlockTouchMove) {
        window.removeEventListener('touchmove', window.__sortBlockTouchMove, { passive: false });
        window.__sortBlockTouchMove = null;
      }
    };
  }

  function makeSortable(opts) {
    const container = document.querySelector(opts.container);
    if (!container) return;

    let dragging = null;

    function getRows() {
      return Array.from(container.querySelectorAll(opts.rowSelector));
    }

    function createPlaceholder(row) {
      const ph = document.createElement('div');
      ph.className = 'drag-placeholder';
      ph.style.height = row.getBoundingClientRect().height + 'px';
      return ph;
    }

    function createGhost(row) {
      const rect = row.getBoundingClientRect();
      const g = row.cloneNode(true);
      g.classList.add('drag-ghost');
      g.style.width = rect.width + 'px';
      g.style.left  = rect.left + 'px';
      g.style.top   = rect.top + 'px';
      return g;
    }

    function pointerMove(e) {
      if (!dragging) return;

      dragging.ghost.style.left = (e.clientX - dragging.grabOffsetX) + 'px';
      dragging.ghost.style.top  = (e.clientY - dragging.grabOffsetY) + 'px';

      const y = e.clientY;
      const rows = getRows().filter(r => r !== dragging.row);

      let beforeEl = null;
      for (const r of rows) {
        const rRect = r.getBoundingClientRect();
        const mid = rRect.top + rRect.height / 2;
        if (y < mid) { beforeEl = r; break; }
      }

      if (beforeEl) {
        if (dragging.placeholder.nextSibling !== beforeEl) {
          container.insertBefore(dragging.placeholder, beforeEl);
        }
      } else {
        container.appendChild(dragging.placeholder);
      }
    }

    function finishDrag() {
      if (!dragging) return;

      // вернуть оригинал
      dragging.row.style.display = '';

      container.insertBefore(dragging.row, dragging.placeholder);

      dragging.ghost.remove();
      dragging.placeholder.remove();

      if (dragging.unlock) dragging.unlock();

      try {
        const handle = dragging.handle;
        if (handle && dragging.pointerId != null) handle.releasePointerCapture(dragging.pointerId);
      } catch (_) {}

      if (typeof opts.onSorted === 'function') {
        opts.onSorted(container);
      }

      dragging = null;
    }

    function pointerDown(e) {
      const handle = closest(e.target, opts.handleSelector);
      if (!handle) return;

      const row = closest(handle, opts.rowSelector);
      if (!row || !container.contains(row)) return;

      if (e.button !== undefined && e.button !== 0) return;

      e.preventDefault();

      try { handle.setPointerCapture(e.pointerId); } catch (_) {}

      const rect = row.getBoundingClientRect();

      const ghost = createGhost(row);
      document.body.appendChild(ghost);

      const placeholder = createPlaceholder(row);

      const grabOffsetX = e.clientX - rect.left;
      const grabOffsetY = e.clientY - rect.top;

      row.style.display = 'none';
      container.insertBefore(placeholder, row);

      const unlock = lockModalScroll(container);

      dragging = {
        row,
        handle,
        ghost,
        placeholder,
        pointerId: e.pointerId,
        unlock,
        grabOffsetX,
        grabOffsetY
      };

      pointerMove(e);
    }

    window.addEventListener('pointermove', pointerMove, { passive: true });
    window.addEventListener('pointerup', finishDrag, { passive: true });
    window.addEventListener('pointercancel', finishDrag, { passive: true });

    container.addEventListener('pointerdown', pointerDown, { passive: false });
  }

  // ======= update sort + reindex =======

  function updateTagsSort(container) {
    const rows = Array.from(container.querySelectorAll('.tag-row'));
    rows.forEach((row, i) => {
      const sortInput  = row.querySelector('input[type="hidden"]');
      const titleInput = row.querySelector('.tag-input');

      if (sortInput) {
        sortInput.name  = `tags[${i}][sort]`;
        sortInput.value = (i + 1) * 10;
      }
      if (titleInput) {
        titleInput.name = `tags[${i}][title]`;
      }
    });
  }

  function updateOffersSortAndReindex(container) {
    const rows = Array.from(container.querySelectorAll('.offer-row'));
    rows.forEach((row, i) => {
      row.querySelectorAll('input,select,textarea').forEach(inp => {
        if (!inp.name) return;
        inp.name = inp.name.replace(/^offers\[\d+\]/, 'offers[' + i + ']');
      });

      let sortInput = row.querySelector(`input[name="offers[${i}][sort]"]`) || row.querySelector('input[name$="[sort]"]');
      if (!sortInput) {
        sortInput = document.createElement('input');
        sortInput.type = 'hidden';
        sortInput.name = 'offers[' + i + '][sort]';
        row.appendChild(sortInput);
      }
      sortInput.value = (i + 1) * 10;
    });
  }

  function initTagsUI() {
    const rows = document.getElementById('tagsRows');
    const tpl  = document.getElementById('tagRowTpl');
    const addBtn = document.getElementById('tagAddBtn');
    if (!rows || !tpl || !addBtn) return;

    function bindRemove(btn) {
      btn.addEventListener('click', () => {
        const row = closest(btn, '.tag-row');
        if (row) row.remove();
        updateTagsSort(rows);
      });
    }

    function bindEnter(input) {
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          addRow(true);
        }
      });
    }

    function addRow(focus) {
      const next = rows.querySelectorAll('.tag-row').length;
      const html = tpl.innerHTML.replaceAll('__I__', next);

      const wrap = document.createElement('div');
      wrap.innerHTML = html.trim();
      const node = wrap.firstElementChild;

      rows.appendChild(node);

      const rm = node.querySelector('.tagRemoveBtn');
      if (rm) bindRemove(rm);

      const inp = node.querySelector('.tag-input');
      if (inp) {
        bindEnter(inp);
        if (focus) inp.focus();
      }

      updateTagsSort(rows);
    }

    rows.querySelectorAll('.tagRemoveBtn').forEach(bindRemove);
    rows.querySelectorAll('.tag-input').forEach(bindEnter);

    addBtn.addEventListener('click', () => addRow(true));

    updateTagsSort(rows);
  }

  function initOffersUI() {
    const rows = document.getElementById('offersRows');
    const tpl  = document.getElementById('offerRowTpl');
    const addBtn = document.getElementById('offerAddBtn');
    if (!rows || !tpl || !addBtn) return;

    function bindRemove(btn) {
      btn.addEventListener('click', () => {
        const row = closest(btn, '.offer-row');
        if (row) row.remove();
        updateOffersSortAndReindex(rows);
      });
    }

    addBtn.addEventListener('click', () => {
      const next = rows.querySelectorAll('.offer-row').length;
      const html = tpl.innerHTML.replaceAll('__NAME__', 'offers[' + next + ']');

      const wrap = document.createElement('div');
      wrap.innerHTML = html.trim();
      const node = wrap.firstElementChild;

      rows.appendChild(node);

      const rm = node.querySelector('.offerRemoveBtn');
      if (rm) bindRemove(rm);

      updateOffersSortAndReindex(rows);
    });

    rows.querySelectorAll('.offerRemoveBtn').forEach(bindRemove);
    updateOffersSortAndReindex(rows);
  }

  document.addEventListener('DOMContentLoaded', function () {
    initTagsUI();
    initOffersUI();

    makeSortable({
      container: '#tagsRows',
      rowSelector: '.tag-row',
      handleSelector: '.js-drag-handle',
      onSorted: function (container) {
        updateTagsSort(container);
      }
    });

    makeSortable({
      container: '#offersRows',
      rowSelector: '.offer-row',
      handleSelector: '.js-drag-handle',
      onSorted: function (container) {
        updateOffersSortAndReindex(container);
      }
    });
  });

})();
</script>

@endsection
