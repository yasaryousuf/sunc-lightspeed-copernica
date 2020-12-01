<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8" />

    <title>Dashboard</title>
    <meta name="description" content="Updates and statistics">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <link
        href="{{ asset('metronic/theme/default/demo1/dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/css/pages/wizard/wizard-4.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/css/pages/pricing/pricing-1.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/css/pages/wizard/wizard-3.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/plugins/global/plugins.bundle.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/css/style.bundle.css') }}" rel="stylesheet"
        type="text/css" />

    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/css/skins/header/base/light.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/css/skins/header/menu/light.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/css/skins/brand/dark.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/css/skins/aside/dark.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic/theme/default/demo1/dist/assets/css/style.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="shortcut icon" href="{{ url('public/assets/images/logo-border.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .alert {
            border-radius: 0px;
        }

    </style>

    @yield('style')
</head>

<body
    class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
        <div class="kt-header-mobile__logo">
            <a href="{{ url('/') }}">
                <img src="{{ url('public/assets/images/logo.png') }}">
            </a>
        </div>
        <div class="kt-header-mobile__toolbar">
            <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler">
                <span></span></button>

            <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>

            <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i
                    class="flaticon-more"></i></button>
        </div>
    </div>
    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            @include('admin.includes.sidebar')
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">
                    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
                        <div id="kt_header_menu"
                            class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
                        </div>
                    </div>
                    <div class="kt-header__topbar">

                        <div class="kt-header__topbar-item kt-header__topbar-item--user">
                            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                                <div class="kt-header__topbar-user">
                                    <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                                    <span
                                        class="kt-header__topbar-username kt-hidden-mobile">{{ Auth::user()->name }}</span>
                                    <img class="kt-hidden" alt="Pic"
                                        src="{{ asset('metronic/theme/default/demo1/dist/assets/media/users/300_25.jpg') }}" />
                                    <span
                                        class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            </div>

                            <div
                                class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                                <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x"
                                    style="background: url({{ url('public/assets/images/BG.jpg') }}); background-repeat: no-repeat;background-size: cover;">
                                    <div class="kt-user-card__avatar">
                                        <img class="kt-hidden" alt="Pic"
                                            src="{{ asset('metronic/theme/default/demo1/dist/assets/media/users/300_25.jpg') }}" />
                                        <span
                                            class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <div class="kt-user-card__name">
                                        {{ Auth::user()->name }}
                                    </div>
                                </div>
                                <div class="kt-notification">
                                    <div class="kt-notification__custom kt-space-between">
                                        <a href="{{ url('logout') }}"
                                            class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.includes.flashMessage')
                @yield('content')

                <div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
                    <div class="kt-container  kt-container--fluid ">
                        <div class="kt-footer__copyright">
                            <a class="kt-link">&copy 2020 API by Website</a>
                        </div>
                        <!-- <div class="kt-footer__menu">
                            <a href="http://keenthemes.com/metronic" target="_blank"
                                class="kt-footer__menu-link kt-link">About</a>
                            <a href="http://keenthemes.com/metronic" target="_blank"
                                class="kt-footer__menu-link kt-link">Team</a>
                            <a href="http://keenthemes.com/metronic" target="_blank"
                                class="kt-footer__menu-link kt-link">Contact</a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>

    <script src="{{ asset('metronic/theme/default/demo1/dist/assets/plugins/global/plugins.bundle.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('metronic/theme/default/demo1/dist/assets/js/scripts.bundle.js') }}" type="text/javascript">
    </script>

    <script src="{{ asset('metronic/theme/default/demo1/dist/assets/js/pages/components/extended/toastr.js') }}"
        type="text/javascript"></script>
    <script
        src="{{ asset('metronic/theme/default/demo1/dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"
        type="text/javascript"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript">
    </script>
    <script src="{{ asset('metronic/theme/default/demo1/dist/assets/plugins/custom/gmaps/gmaps.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('metronic/theme/default/demo1/dist/assets/plugins/custom/gmaps/gmaps.js') }}"
        type="text/javascript"></script>
    {{-- <script
        src="{{ asset('metronic/theme/default/demo1/dist/assets/js/pages/custom/wizard/wizard-3.js') }}"
        type="text/javascript">
    </script> --}}
    @yield('script')
</body>

</html>
