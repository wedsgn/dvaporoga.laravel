@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-sm-4">
                    <div class="search-box">
                        <form class="d-flex" action="{{ route('admin.products.search') }}" method="get">
                            @csrf
                            <input class="form-control me-2" type="search" name="search" placeholder="{{__('admin.placeholder_search')}}"
                                aria-label="Search">
                            <button class="btn btn-outline-primary" type="submit">{{__('admin.btn_search')}}</button>
                        </form>
                    </div>
                </div>

                <div class="col-sm-auto ms-auto">
                    <div class="list-grid-nav hstack gap-1">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-soft-success addMembers-modal">
                            <i class="ri-add-fill me-1 align-bottom"></i>
                            {{__('admin.btn_add')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="demo-inline-spacing">
                    @if (session('status') === 'item-updated')
                        <div class="alert alert-primary alert-dismissible" role="alert">
                            {{ __('admin.alert_updated') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('status') === 'item-created')
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ __('admin.alert_created') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('status') === 'item-deleted')
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ __('admin.alert_deleted') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive table-card">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 80px;">ID</th>
                                        <th scope="col">{{__('admin.field_title')}}</th>
                                        <th scope="col">{{__('admin.field_slug')}}</th>
                                        <th scope="col" style="width: 150px;">{{__('admin.field_updated')}}</th>
                                        <th scope="col" style="width: 150px;">{{__('admin.field_action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td><a href="{{ route('admin.products.show', $item->slug) }}">{{ $item->title }}, {{ $item->material }}, {{ $item->metal_thickness }}</a></td>
                                            <td>{{ $item->slug }}</td>
                                            <td>{{ $item->updated_at->diffForHumans() }}</td>
                                            <td>

                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill align-middle"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="">
                                                        <li><a href="{{ route('admin.products.show', $item->slug) }}"
                                                                class="dropdown-item"><i
                                                                    class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                                    {{__('admin.btn_show')}}</a></li>
                                                        <li><a href="{{ route('admin.products.edit', $item->slug) }}"
                                                                class="dropdown-item edit-item-btn"><i
                                                                    class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                    {{__('admin.btn_edit')}}</a></li>
                                                        <li>
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalScrollable{{ $item->slug }}"><i
                                                                    class="bx bx-trash me-1 text-danger" role="button"></i>
                                                                    {{__('admin.btn_delete')}}</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modalScrollable{{ $item->slug }}" tabindex="-1"
                                            style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalScrollableTitle"> {{__('admin.question_delete')}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p
                                                            class="mt-1 text-sm text-gray-600 dark:text-gray-400  alert alert-warning text-wrap">
                                                            {{__('admin.notification_delete')}}
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">
                                                            {{__('admin.btn_close')}}
                                                        </button>
                                                        <form
                                                            action="{{ route('admin.products.destroy', $item->slug) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalScrollableConfirm">{{__('admin.btn_confirm')}}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td class="text-danger">{{__('admin.notification_no_entries')}}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($products->links()->paginator->hasPages())
                            {{ $products->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
