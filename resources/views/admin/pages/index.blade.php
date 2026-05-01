@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-sm-4">
                    <div class="search-box">
                        <form class="d-flex" action="{{ route('admin.pages.search') }}" method="get">
                            @csrf
                            <input class="form-control me-2" type="search" name="search" placeholder="{{__('admin.placeholder_search')}}"
                                aria-label="Search">
                            <button class="btn btn-outline-primary" type="submit">{{__('admin.btn_search')}}</button>
                        </form>
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
                                    @forelse ($pages as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>

                                            <td><a href="{{ route('admin.pages.show', $item->slug) }}">{{ $item->title_admin }}</a></td>
                                            <td>{{ $item->slug }}</td>
                                            <td>{{ $item->updated_at->diffForHumans() }}</td>
                                            <td>

                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill align-middle"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="">
                                                        <li><a href="{{ route('admin.pages.show', $item->slug) }}"
                                                                class="dropdown-item"><i
                                                                    class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                                    {{__('admin.btn_show')}}</a></li>
                                                        <li><a href="{{ route('admin.pages.edit', $item->slug) }}"
                                                                class="dropdown-item edit-item-btn"><i
                                                                    class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                    {{__('admin.btn_edit')}}</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-danger">{{__('admin.notification_no_entries')}}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($pages->links()->paginator->hasPages())
                            {{ $pages->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
