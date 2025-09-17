<!doctype html>
<html lang="ru" data-layout="vertical" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-sidebar-visibility="show" data-layout-style="default"
    data-bs-theme="dark" data-layout-width="fluid" data-layout-position="fixed">

<head>

    <meta charset="utf-8" />
    <title>{{ __('admin.page_title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Премиальный шаблон премиум мультифункционального админ-панели и дашборда" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="{{ asset('images/favicon/favicon.ico') }}">
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/admin/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/admin/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/admin/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('admin.parts.header')


        @include('admin.parts.aside')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    {{-- <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">{{preg_replace('#admin/|_|-#', ' ', Request::path())}}</h4>
                                <div class="page-title-right">
                                  {{ Breadcrumbs::render() }}
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- ============================================================== -->
                    <!-- Start right Content here -->
                    <!-- ============================================================== -->
                    @yield('content')
                </div>
            </div>
        </div>
        <div id="removeNotificationModal"></div>

    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <!-- App js -->
    <!-- particles js -->
    <!-- particles app js -->
    {{-- <script src="{{ asset('assets/admin/js/pages/particles.app.js') }}"></script> --}}
    <!-- password-addon init -->
    <script src="{{ asset('assets/admin/js/pages/password-addon.init.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/libs/quill/quill.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/pages/form-editor.init.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/plugins.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/app.js') }}"></script>


</body>

</html>
