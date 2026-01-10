@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('admin.aside_title_order') }}</h4>
                </div>


            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-border-left alert-dismissible fade show " role="alert">

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    @foreach ($errors->all() as $error)
                        <div>
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="live-preview">
                        <form action="{{ route('admin.car_makes_order.update_order', $order->id) }}" method="POST" >
                          @csrf
                          @method('patch')

                          <div class="row gy-4">
                            <div class="col-xxl-6 col-md-6">
                                <div class="mb-3">
                                    <label for="valueInput" class="form-label">{{ __('admin.aside_title_order') }}</label>

                                    @if (!count($car_makes) == 0)
                                    <select id="valueInput" class="form-control" data-choices data-choices-removeItem name="car_makes[]" multiple>
                                        @foreach ($car_makes as $car_make)
                                        <option value="{{ $car_make->title }}" {{ collect($order->car_makes)->contains('title', $car_make->title) ? 'selected' : '' }}>
                                            {{ $car_make->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <div class="text-danger">
                                      {{ __('admin.notification_no_entries_car_makes') }}
                                    </div>
                                    @endif

                                </div>
                            </div>
                          </div>

                    </div>
                    <button type="submit"
                        class="btn btn-soft-success waves-effect waves-light mt-5 float-end">{{ __('admin.btn_save') }}</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>

@endsection
