@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h3 class="card-header align-items-center d-flex">{{ __('admin.page_card_title') }}:
                                {{ $item->title_admin }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="dropdown">
                                <a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown"
                                    aria-expanded="false" class="">
                                    <i class="ri-more-2-fill fs-14"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink1"
                                    style="">
                                    <li>
                                        <a type="button" class="dropdown-item" href="{{ route('admin.pages.index') }}">
                                            <i class="ri-arrow-left-line align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_back') }}</a>
                                    </li>

                                    <li><a href="{{ route('admin.pages.edit', $item->slug) }}"
                                            class="dropdown-item edit-item-btn"><i
                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_edit') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if ($item->description)
                        <h5 class="text-muted">{{ __('admin.field_description') }}:</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">{!! $item->description !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                    @endif

                </div>
                <!--end card-body-->
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">{{ __('admin.page_card_info') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row">Id:</th>
                                    <td class="text-muted">{{ $item->id }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_title') }}:</th>
                                    <td class="text-muted">{{ $item->title }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_slug') }}:</th>
                                    <td class="text-muted">{{ $item->slug }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_created') }}:</th>
                                    <td class="text-muted">{{ $item->created_at }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_updated') }}:</th>
                                    <td class="text-muted">{{ $item->updated_at }}</td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">{{ __('admin.title_seo') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_meta_title') }}:</th>
                                    <td class="text-muted">{{ $item->meta_title }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_meta_description') }}:</th>
                                    <td class="text-muted">{{ $item->meta_description }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_meta_keywords') }}:</th>
                                    <td class="text-muted">{{ $item->meta_keywords }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_og_title') }}:</th>
                                    <td class="text-muted">{{ $item->og_title }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_og_description') }}:</th>
                                    <td class="text-muted">{{ $item->og_description }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_og_url') }}:</th>
                                    <td class="text-muted">{{ $item->og_url }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div>
            @if ($item->slug === 'home')
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="card-header align-items-center d-flex flex-grow-1 mb-0">
                                Баннеры страницы
                            </h5>
                            <button type="button" class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal"
                                data-bs-target="#addBannerModal">
                                + Добавить баннер
                            </button>
                        </div>

                        <div class="row" id="bannersGrid">
                            @forelse($item->banners as $banner)
                                @include('admin.pages.partials.banner-card', ['banner' => $banner])
                            @empty
                                <div class="col-12">
                                    <p class="text-muted mb-0">Баннеров пока нет.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="addBannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="addBannerForm" action="{{ route('admin.page-banners.store', $item->slug) }}" method="POST"
                    enctype="multipart/form-data" data-mode="create">
                    @csrf
                    <input type="hidden" id="banner_mode" value="create">
                    <input type="hidden" id="banner_edit_id" value="">

                    <div class="modal-header">
                        <h5 class="modal-title" id="bannerModalTitle">Добавить баннер</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Заголовок</label>
                                <input type="text" name="title" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Порядок</label>
                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="banner_is_active" checked value="1">
                                    <label class="form-check-label" for="banner_is_active">
                                        Активен
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Desktop изображение <span class="text-danger"
                                        id="desktopRequiredMark">*</span></label>
                                <input type="file" name="image_desktop" id="banner_image_desktop"
                                    class="form-control" accept="image/*">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mobile изображение <span class="text-danger"
                                        id="mobileRequiredMark">*</span></label>
                                <input type="file" name="image_mobile" id="banner_image_mobile" class="form-control"
                                    accept="image/*">
                            </div>
                        </div>

                        <div class="alert alert-danger mt-3 d-none" id="bannerErrors"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary" id="addBannerSubmit">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('addBannerForm');
                const submitBtn = document.getElementById('addBannerSubmit');
                const errorsBox = document.getElementById('bannerErrors');
                const bannersGrid = document.getElementById('bannersGrid');
                const modalEl = document.getElementById('addBannerModal');
                const modal = modalEl ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;
                const modeInput = document.getElementById('banner_mode');
                const editIdInput = document.getElementById('banner_edit_id');
                const modalTitle = document.getElementById('bannerModalTitle');
                const btnAddBanner = document.querySelector('[data-bs-target="#addBannerModal"]');

                const desktopInput = document.getElementById('banner_image_desktop');
                const mobileInput = document.getElementById('banner_image_mobile');
                const desktopReqMark = document.getElementById('desktopRequiredMark');
                const mobileReqMark = document.getElementById('mobileRequiredMark');

                if (!form) {
                    console.warn('addBannerForm not found');
                    return;
                }

                /**
                 * Режим СОЗДАНИЯ
                 */
                function openCreateMode() {
                    form.dataset.mode = 'create';
                    modeInput.value = 'create';
                    editIdInput.value = '';

                    modalTitle.textContent = 'Добавить баннер';

                    form.action = "{{ route('admin.page-banners.store', $item->slug) }}";

                    // сброс формы и ошибок
                    form.reset();
                    errorsBox.classList.add('d-none');
                    errorsBox.innerHTML = '';

                    // файлы обязательны
                    desktopInput.required = true;
                    mobileInput.required = true;
                    desktopReqMark.classList.remove('d-none');
                    mobileReqMark.classList.remove('d-none');

                    if (modal) modal.show();
                }

                /**
                 * Режим РЕДАКТИРОВАНИЯ
                 */
                function openEditMode(button) {
                    const id = button.dataset.id;
                    const title = button.dataset.title || '';
                    const sort = button.dataset.sort || 0;
                    const isActive = button.dataset.active === '1';
                    const updateUrl = button.dataset.updateUrl;

                    form.dataset.mode = 'edit';
                    modeInput.value = 'edit';
                    editIdInput.value = id;

                    modalTitle.textContent = 'Редактировать баннер #' + id;

                    form.action = updateUrl;

                    // заполняем поля
                    form.querySelector('input[name="title"]').value = title;
                    form.querySelector('input[name="sort_order"]').value = sort;
                    form.querySelector('input[name="is_active"]').checked = isActive;

                    // очищаем file-инпуты
                    desktopInput.value = '';
                    mobileInput.value = '';

                    // файлы НЕ обязательны при редактировании
                    desktopInput.required = false;
                    mobileInput.required = false;
                    desktopReqMark.classList.add('d-none');
                    mobileReqMark.classList.add('d-none');

                    errorsBox.classList.add('d-none');
                    errorsBox.innerHTML = '';

                    if (modal) modal.show();
                }

                // Кнопка "Добавить баннер"
                if (btnAddBanner) {
                    btnAddBanner.addEventListener('click', function(e) {
                        // bootstrap сам откроет модалку по data-bs-target,
                        // мы просто переключаем режим перед этим
                        openCreateMode();
                    });
                }

                // Делегирование клика по .js-edit-banner
                if (bannersGrid) {
                    bannersGrid.addEventListener('click', function(e) {

                        const btnEdit = e.target.closest('.js-edit-banner');
                        if (btnEdit) {
                            e.preventDefault();
                            openEditMode(btnEdit);
                            return;
                        }

                        const btnDelete = e.target.closest('.js-delete-banner');
                        if (btnDelete) {
                            e.preventDefault();

                            const id = btnDelete.dataset.id;
                            const url = btnDelete.dataset.deleteUrl;

                            if (!confirm('Удалить баннер #' + id + '?')) {
                                return;
                            }

                            const formData = new FormData();
                            formData.append('_method', 'DELETE');

                            fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                    },
                                    body: formData,
                                })
                                .then(async response => {
                                    const data = await response.json();

                                    if (!response.ok || data.status !== 'ok') {
                                        throw data;
                                    }

                                    const card = document.querySelector('.banner-card[data-id="' + data
                                        .id + '"]');
                                    if (card) {
                                        card.remove();
                                    }
                                })
                                .catch(err => {
                                    console.error(err);
                                    alert('Ошибка при удалении баннера');
                                });
                        }
                    });
                }


                // Общий submit для create + edit
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const mode = form.dataset.mode || 'create';

                    errorsBox.classList.add('d-none');
                    errorsBox.innerHTML = '';

                    const formData = new FormData(form);

                    // для PATCH добавляем _method
                    if (mode === 'edit') {
                        formData.append('_method', 'PATCH');
                    }

                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Сохраняю...';

                    fetch(form.action, {
                            method: 'POST', // и для create, и для edit (через _method)
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData,
                        })
                        .then(async response => {
                            const data = await response.json();

                            if (!response.ok || data.status !== 'ok') {
                                throw data;
                            }

                            if (mode === 'create') {
                                // добавляем новую плитку
                                const wrapper = document.createElement('div');
                                wrapper.innerHTML = data.html;
                                const card = wrapper.firstElementChild;
                                bannersGrid.appendChild(card);
                                form.reset();
                            } else {
                                // заменяем существующую плитку
                                const card = document.querySelector('.banner-card[data-id="' + data.id +
                                    '"]');
                                if (card) {
                                    const wrapper = document.createElement('div');
                                    wrapper.innerHTML = data.html;
                                    card.replaceWith(wrapper.firstElementChild);
                                }
                            }

                            if (modal) {
                                modal.hide();
                            }
                        })
                        .catch(err => {
                            let messages = [];

                            if (err && err.errors) {
                                for (const key in err.errors) {
                                    if (Object.prototype.hasOwnProperty.call(err.errors, key)) {
                                        messages.push(err.errors[key].join('<br>'));
                                    }
                                }
                            } else if (err && err.message) {
                                messages.push(err.message);
                            } else {
                                messages.push('Ошибка при сохранении баннера.');
                            }

                            errorsBox.innerHTML = messages.join('<hr>');
                            errorsBox.classList.remove('d-none');
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerText = 'Сохранить';
                        });
                });
            });
        </script>
    @endpush
@endsection
