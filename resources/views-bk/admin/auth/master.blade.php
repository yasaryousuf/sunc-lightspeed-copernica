<!DOCTYPE html>

<html lang="en" >

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8"/>

    <title>Login</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">


    <link href="{{asset('metronic/theme/default/demo1/dist/assets/css/pages/login/login-1.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('metronic/theme/default/demo1/dist/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('metronic/theme/default/demo1/dist/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('metronic/theme/default/demo1/dist/assets/css/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('metronic/theme/default/demo1/dist/assets/css/skins/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('metronic/theme/default/demo1/dist/assets/css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('metronic/theme/default/demo1/dist/assets/css/skins/aside/dark.css')}}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{url('public/assets/images/logo-border.png')}}" />

<body  class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"  >

<div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
            <div class="kt-grid__item kt-grid__item--order-tablet-and-mobile-2 kt-grid kt-grid--hor kt-login__aside" style="background-image: url({{url('public/assets/images/BG.jpg')}});">
                <div class="kt-grid__item">
                    <a href="{{url('/')}}" class="kt-login__logo">
                        <img src="{{url('public/assets/images/logo.png')}}">
                    </a>
                </div>
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver">
                    <div class="kt-grid__item kt-grid__item--middle">
                        <h3 class="kt-login__title">API by Website</h3>
                        <h4 class="kt-login__subtitle">The ultimate email marketing web apps.</h4>
                    </div>
                </div>
                <div class="kt-grid__item">
                    <div class="kt-login__info">
                        <div class="kt-login__copyright">
                            &copy 2020 API by Website
                        </div>
                        <!-- <div class="kt-login__menu">
                            <a href="#" class="kt-link">Privacy</a>
                            <a href="#" class="kt-link">Legal</a>
                            <a href="#" class="kt-link">Contact</a>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">
                @include('admin.includes.flashMessage');
                @yield('content')
            </div>
        </div>
    </div>
</div>


<script src="{{asset('metronic/theme/default/demo1/dist/assets/plugins/global/plugins.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('metronic/theme/default/demo1/dist/assets/js/scripts.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('metronic/theme/default/demo1/dist/assets/js/pages/custom/login/login-1.js')}}" type="text/javascript"></script>
</body>
</html>
