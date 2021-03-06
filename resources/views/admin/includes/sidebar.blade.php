<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop"
     id="kt_aside">
    <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
        <div class="kt-aside__brand-logo">
            <a href="{{url('/')}}">
                <img alt="Logo"
                     src="{{asset('metronic/theme/default/demo1/dist/assets/media/logos/logo-light.png')}}"/>
            </a>
        </div>
        <div class="kt-aside__brand-tools">
            <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                <span><i class="flaticon-email-black-circular-button"></i></span>
                <span><i class="flaticon-email-black-circular-button"></i></span>
            </button>
        </div>
    </div>
    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div
                id="kt_aside_menu"
                class="kt-aside-menu "
                data-ktmenu-vertical="1"
                data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500"
        >
            <ul class="kt-menu__nav ">
                <li class="kt-menu__item  kt-menu__item--active" aria-haspopup="true"><a href="{{url('/')}}"
                                                                                         class="kt-menu__link "><span
                                class="kt-menu__link-icon"><i class="flaticon-laptop"></i></span><span
                                class="kt-menu__link-text">Dashboard</span></a></li>
                <li class="kt-menu__section ">
                    <h4 class="kt-menu__section-text">Menu</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                    data-ktmenu-submenu-toggle="hover">
                    <a href="javascript:"
                       class="kt-menu__link kt-menu__toggle"><span
                                class="kt-menu__link-icon"><i class="flaticon2-shopping-cart"></i></span><span
                                class="kt-menu__link-text">Lightspeed</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu ">
                        <span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span
                                            class="kt-menu__link-text"></span></span></li>
                            <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                                data-ktmenu-submenu-toggle="hover"><a href="{{url('lightspeed/subscribers')}}"
                                                                      class="kt-menu__link kt-menu__toggle"><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span
                                            class="kt-menu__link-text">Subscribers</span></a>
                            </li>
                            <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                                data-ktmenu-submenu-toggle="hover"><a href="{{url('lightspeed/discounts')}}"
                                                                      class="kt-menu__link kt-menu__toggle"><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span
                                            class="kt-menu__link-text">Discounts</span></a>
                            </li>
                            <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                                data-ktmenu-submenu-toggle="hover"><a href="{{url('lightspeed/orders')}}"
                                                                      class="kt-menu__link kt-menu__toggle"><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span
                                            class="kt-menu__link-text">Orders</span></a>
                            </li>
                            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open" aria-haspopup="true"
                                data-ktmenu-submenu-toggle="hover">
                                <a href="javascript:"
                                   class="kt-menu__link kt-menu__toggle"><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span
                                            class="kt-menu__link-text">Settings</span><i
                                            class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="kt-menu__submenu ">
                                    <span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item " aria-haspopup="true"><a
                                                    href="{{url('lightspeed-auth/settings')}}"
                                                    class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">API tokens</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                    data-ktmenu-submenu-toggle="hover">
                    <a href="javascript:"
                       class="kt-menu__link kt-menu__toggle"><span
                                class="kt-menu__link-icon"><i class="flaticon-multimedia-2"></i></span><span
                                class="kt-menu__link-text">Copernica</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu ">
                        <span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open" aria-haspopup="true"
                                data-ktmenu-submenu-toggle="hover">
                                <a href="javascript:"
                                   class="kt-menu__link kt-menu__toggle"><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span
                                            class="kt-menu__link-text">Settings</span><i
                                            class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="kt-menu__submenu ">
                                    <span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item " aria-haspopup="true"><a
                                                    href="{{url('copernica-auth/settings')}}"
                                                    class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">API tokens</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>