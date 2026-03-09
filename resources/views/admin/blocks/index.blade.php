@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @if (session('status') === 'block-updated')
                <div class="alert alert-success">
                    Блок успешно обновлен.
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h3 class="card-header align-items-center d-flex mb-4">Блоки сайта</h3>

                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Ключ</th>
                                    <th>Заголовок</th>
                                    <th>Фото</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->key }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            @if ($item->key === 'repair_examples')
                                                {{ is_array($item->items) ? count($item->items) : 0 }}
                                            @else
                                                {{ is_array($item->images) ? count($item->images) : 0 }}
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.blocks.edit', $item->id) }}"
                                                class="btn btn-sm btn-primary">
                                                Редактировать
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted">Блоков пока нет.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
