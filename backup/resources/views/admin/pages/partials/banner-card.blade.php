<div class="col-md-4 mb-3 banner-card" data-id="{{ $banner->id }}">
    <div class="card h-100">
        <div class="card-body">
            <h5 class="card-title mb-2">
                {{ $banner->title ?: 'Без названия' }}
                @if (!$banner->is_active)
                    <span class="badge bg-secondary ms-1">Неактивен</span>
                @endif
            </h5>

            <div class="d-flex gap-2 mb-2">
                @if ($banner->image_desktop)
                    <div class="flex-fill text-center">
                        <div class="mb-1"><small>Desktop</small></div>
                        <img src="{{ asset('storage/' . $banner->image_desktop) }}" class="img-fluid rounded border"
                            style="max-height: 120px;" alt="">
                    </div>
                @endif

                @if ($banner->image_mobile)
                    <div class="flex-fill text-center">
                        <div class="mb-1"><small>Mobile</small></div>
                        <img src="{{ asset('storage/' . $banner->image_mobile) }}" class="img-fluid rounded border"
                            style="max-height: 120px;" alt="">
                    </div>
                @endif
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted small">Sort: {{ $banner->sort_order }}</span>

            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-light js-edit-banner" data-id="{{ $banner->id }}"
                    data-title="{{ e($banner->title) }}" data-sort="{{ $banner->sort_order }}"
                    data-active="{{ $banner->is_active ? 1 : 0 }}"
                    data-update-url="{{ route('admin.page-banners.update', $banner) }}">
                    Редактировать
                </button>

                <button type="button" class="btn btn-outline-danger js-delete-banner" data-id="{{ $banner->id }}"
                    data-delete-url="{{ route('admin.page-banners.destroy', $banner) }}">
                    Удалить
                </button>
            </div>
        </div>

    </div>
</div>
