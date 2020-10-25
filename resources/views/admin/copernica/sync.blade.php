@extends('admin.master')
@section('coper_active', 'kt-menu__item--open')
@section('coper_sub_active', 'kt-menu__item--open')
@section('coper_auth_sub_active', 'kt-menu__item--active')
@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Subheader -->
        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        Sync
                    </h3>
                    <span class="kt-subheader__separator kt-hidden"></span>
                    <div class="kt-subheader__group" id="kt_subheader_search">
                        <span class="kt-subheader__desc" id="kt_subheader_total">
                            Sync from Lightspeed to Copernica
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="la la-puzzle-piece"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Choose any from below
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-pricing-1 kt-pricing-1--fixed">
                        <div class="kt-pricing-1__items row">
                            <div class="kt-pricing-1__item col-lg-6">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon1"></div>
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i
                                            class="fa flaticon-piggy-bank"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Subscribers</span>
                                <h2 class="kt-pricing-1__subtitle">Sync to copernica</h2>

                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('/copernica/profile/create/subscriber') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div>
                            {{-- <div class="kt-pricing-1__item col-lg-4">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon1"></div>
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-rocket"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Discounts</span>
                                <h2 class="kt-pricing-1__subtitle">Sync to copernica</h2>

                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('/copernica/profile/create/discount') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div> --}}
                            <div class="kt-pricing-1__item col-lg-6">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon1"></div>
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-gift"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Orders</span>
                                <h2 class="kt-pricing-1__subtitle">Sync to copernica</h2>
                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('/copernica/profile/create/order') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: Content -->
    </div>
@endsection
