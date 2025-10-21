@extends('layouts.admin')

@section('content')
    <div class="row">


        <div class="col-lg-8">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h3 class="card-header align-items-center d-flex">{{ __('admin.car_make_card_title') }}:
                                {{ $item->title }}</h3>
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

                                  <li><a href="{{ route('admin.car_makes.edit', $item->slug) }}"
                                          class="dropdown-item edit-item-btn"><i
                                              class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                          {{ __('admin.btn_edit') }}</a></li>
                                  <li>
                                      <button type="submit" class="dropdown-item text-danger" data-bs-toggle="modal"
                                          data-bs-target="#modalScrollable{{ $item->slug }}"><i
                                              class="bx bx-trash me-1 text-danger" role="button"></i>
                                          {{ __('admin.btn_delete') }}</button>
                                  </li>
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
            <div class="row">
              @if (!empty($item->image))
                  <div class="col-xxl-6">
                      <div class="card">
                          <div class="card-body">
                              <p class="card-title-desc text-muted">{{ __('admin.field_current_image') }}</p>
                              <div class="live-preview">
                                  <div>
                                    @if ($item->image === 'default')
                                        <img src="{{ asset('images/mark/' . $item->slug . '.png') }}" alt="{{$item->slug}}" />
                                    @else
                                        <img src="{{ asset('storage') . '/' . $item->image }}" class="img-fluid d-block" alt="Логотип {{ $item->title }}" />
                                    @endif
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              @else
              @endif

              @if (!empty($item->image_mob))
                  <div class="col-xxl-6">
                      <div class="card">
                          <div class="card-body">
                              <p class="card-title-desc text-muted">{{ __('admin.field_current_image_mob') }}</p>
                              <div class="live-preview">
                                  <div>
                                      <img src="{{ asset('storage') . '/' . $item->image_mob }}" class="img-fluid"
                                          alt="Responsive image">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              @else
              @endif
          </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-header align-items-center d-flex">{{ __('admin.car_make_card_info') }}</h5>
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

                                <div class="modal fade" id="modalScrollable{{ $item->slug }}" tabindex="-1"
                                    style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalScrollableTitle">
                                                    {{ __('admin.question_delete') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p
                                                    class="mt-1 text-sm text-gray-600 dark:text-gray-400  alert alert-warning text-wrap">
                                                    {{ __('admin.notification_delete') }}
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    {{ __('admin.btn_close') }}
                                                </button>
                                                <form action="{{ route('admin.car_makes.destroy', $item->slug) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalScrollableConfirm">{{ __('admin.btn_confirm') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div>
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
        </div>
    </div>
@endsection
