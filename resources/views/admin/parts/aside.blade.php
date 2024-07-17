<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">

        <!-- Light Logo-->
        <a href="{{ route('admin.index') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('images/favicon/mstile-144x144.png') }}" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('images/logo.svg') }}" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">{{ __('admin.btn_menu') }}</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link  @if (in_array(Route::current()->getName(), $car_makes_routes)) active @endif"
                        href="{{ route('admin.car_makes.index') }}" aria-expanded="false"
                        aria-controls="sidebarLayouts">
                        <i class="mdi mdi-cards-diamond"></i> <span
                            data-key="t-layouts">{{ __('admin.aside_title_car_makes') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link  @if (in_array(Route::current()->getName(), $car_models_routes)) active @endif"
                        href="{{ route('admin.car_models.index') }}" aria-expanded="false"
                        aria-controls="sidebarLayouts">
                        <i class="mdi mdi-car-info"></i> <span
                            data-key="t-layouts">{{ __('admin.aside_title_car_models') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link  @if (in_array(Route::current()->getName(), $cars_routes)) active @endif"
                        href="{{ route('admin.cars.index') }}" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="mdi mdi-car-side"></i> <span
                            data-key="t-layouts">{{ __('admin.aside_title_cars') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link menu-link  @if (in_array(Route::current()->getName(), $products_routes)) active @endif"
                      href="{{ route('admin.products.index') }}" aria-expanded="false" aria-controls="sidebarLayouts">
                      <i class="mdi mdi-car-door"></i> <span
                          data-key="t-layouts">{{ __('admin.aside_title_products') }}</span>
                  </a>
              </li>
              <li class="nav-item">
                <a class="nav-link menu-link  @if (in_array(Route::current()->getName(), $blogs_routes)) active @endif"
                    href="{{ route('admin.blogs.index') }}" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="mdi mdi-note-edit-outline"></i> <span
                        data-key="t-layouts">{{ __('admin.aside_title_blogs') }}</span>
                </a>
            </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
