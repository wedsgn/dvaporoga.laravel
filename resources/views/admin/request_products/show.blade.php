@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h3 class="card-header align-items-center d-flex">{{ __('admin.request_product_card_title') }}:
                                {{ $item->name }}</h3>
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
                                        <a type="button" class="dropdown-item" href="{{ route('admin.request_products.index') }}">
                                            <i class="ri-arrow-left-line align-bottom me-2 text-muted"></i>
                                            {{ __('admin.btn_back') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-body-->
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">{{ __('admin.request_product_card_info') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row">Id:</th>
                                    <td class="text-muted">{{ $item->id }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_name') }}:</th>
                                    <td class="text-muted">{{ $item->name }}</td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">{{ __('admin.field_phone') }}:</th>
                                    <td class="text-muted">{{ $item->phone }}</td>
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
            </div>
            @foreach($products as $product)
            <div class="card">
              <div class="card-body">
                  <div class="d-flex align-items-center mb-4">
                      <div class="flex-grow-1">
                          <h5 class="card-title mb-0">{{ $product->title }}</h5>
                      </div>
                      <div class="flex-shrink-0">
                          <div class="dropdown">
                              <a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="ri-more-2-fill fs-14"></i>
                              </a>

                              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink1">
                                  <li><a class="dropdown-item" href="{{ route('admin.products.show', $product->slug) }}">Показать</a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
                  <div class="d-flex">
                      <div class="flex-shrink-0">
                          <img src="{{ Storage::url($product->image) }}" alt="" height="50" class="rounded">
                      </div>
                      <div class="flex-grow-1 ms-3 overflow-hidden">
                          <a href="{{ route('admin.products.show', $product->slug) }}">
                              <h6 class="text-truncate fs-14">{{ $product->description }}</h6>
                          </a>
                          <p class="text-muted mb-0">{{ $product->price_one_side }} руб.</p>
                      </div>
                  </div>
              </div>
              <!--end card-body-->
          </div>
            @endforeach
        </div>
    </div>
@endsection

