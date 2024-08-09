@extends('layouts.admin')

@section('content')
    <div class="row">


        <div class="col-lg-8">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h3 class="card-header align-items-center d-flex">{{ __('admin.aside_title_orders') }}</h3>
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
                                      <a type="button" class="dropdown-item" href="{{ route('admin.car_makes.index') }}">
                                          <i class="ri-arrow-left-line align-bottom me-2 text-muted"></i>
                                          {{ __('admin.btn_back') }}</a>
                                  </li>

                                  <li><a href="{{ route('admin.car_makes_order.order', $item->slug) }}"
                                          class="dropdown-item edit-item-btn"><i
                                              class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                          {{ __('admin.btn_edit') }}</a></li>

                              </ul>
                          </div>
                      </div>
                    </div>

          </div>
            <div class="card">
              <div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 80px;">{{__('admin.field_image')}}</th>
                                    <th scope="col" style="width: 80px;">{{__('admin.field_order')}}</th>
                                    <th scope="col">{{__('admin.field_title')}}</th>
                                    <th scope="col">{{__('admin.field_slug')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($item->car_makes as $item)
                                    <tr>
                                    <td>
                                      @if ($item->image === 'default')
                                          <img src="{{ asset('images/mark/no-image.png') }}" class="img-fluid d-block" alt="Изображения нет" />
                                      @else
                                          <img src="{{ asset('storage') . '/' . $item->image }}" class="img-fluid d-block" alt="Логотип {{ $item->title }}" />
                                      @endif
                                    </td>
                                        <td>{{ $item->id }}</td>
                                        <td><a href="{{ route('admin.car_makes.show', $item->slug) }}">{{ $item->title }}</a></td>
                                        <td>{{ $item->slug }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-danger">{{__('admin.notification_no_entries')}}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
