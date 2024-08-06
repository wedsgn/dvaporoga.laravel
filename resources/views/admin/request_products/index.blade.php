@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-sm-4">
                    <div class="search-box">
                        <form class="d-flex" action="{{ route('admin.request_products.search') }}" method="get">
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
                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive table-card">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 80px;">ID</th>
                                        <th scope="col">{{__('admin.field_name')}}</th>
                                        <th scope="col">{{__('admin.field_phone')}}</th>
                                        <th scope="col">{{__('admin.field_created_at')}}</th>
                                        <th scope="col" style="width: 150px;">{{__('admin.field_action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($request_products as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->created_at->diffForHumans() }}</td>
                                            <td>

                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill align-middle"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="">
                                                        <li><a href="{{ route('admin.request_products.show', $item->id) }}"
                                                                class="dropdown-item"><i
                                                                    class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                                    {{__('admin.btn_show')}}</a></li>
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
                        @if ($request_products->links()->paginator->hasPages())
                            {{ $request_products->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

