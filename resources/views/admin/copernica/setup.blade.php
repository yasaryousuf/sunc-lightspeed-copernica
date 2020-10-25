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
                        Setup
                    </h3>
                    <span class="kt-subheader__separator kt-hidden"></span>
                    <div class="kt-subheader__group" id="kt_subheader_search">
                        <span class="kt-subheader__desc" id="kt_subheader_total">
                            Copernica setup by creating database and fields
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: Subheader -->
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="la la-puzzle-piece"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Basic Copernica setup
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-pricing-1 kt-pricing-1--fixed">
                        <div class="kt-pricing-1__items row">
                            <div class="kt-pricing-1__item col-lg-3">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-rocket"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Subscriber Database</span>
                                <h2 class="kt-pricing-1__subtitle">Insatall database to Copernica</h2>

                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('/copernica/database/create/subscriber') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div>
                            <div class="kt-pricing-1__item col-lg-3">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-rocket"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Order Database</span>
                                <h2 class="kt-pricing-1__subtitle">Insatall database to Copernica</h2>

                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('/copernica/database/create/order') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div>
                            <div class="kt-pricing-1__item col-lg-3">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-rocket"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Partner collection</span>
                                <h2 class="kt-pricing-1__subtitle">Insatall collection to Copernica</h2>

                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('/copernica/collection/create/person') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div>
                            <div class="kt-pricing-1__item col-lg-3">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-rocket"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Product collection</span>
                                <h2 class="kt-pricing-1__subtitle">Insatall collection to Copernica</h2>

                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('/copernica/collection/create/product') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div>
                            <div class="kt-pricing-1__item col-lg-3">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-gift"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Subscriber fields</span>
                                <h2 class="kt-pricing-1__subtitle">Add subscrieber fields to database</h2>
                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('copernica/database/fields/create/subscriber') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div>
                            <div class="kt-pricing-1__item col-lg-3">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-gift"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Order fields</span>
                                <h2 class="kt-pricing-1__subtitle">Add order fields to database</h2>
                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('copernica/database/fields/create/order') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div>
                            <div class="kt-pricing-1__item col-lg-3">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-gift"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Partner fields</span>
                                <h2 class="kt-pricing-1__subtitle">Add Partner fields to collection</h2>
                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('copernica/collection/fields/create/person') }}"
                                        class="btn btn-brand btn-wide btn-bold btn-upper">Click
                                        Here</a>
                                </div>
                            </div>
                            <div class="kt-pricing-1__item col-lg-3">
                                <div class="kt-pricing-1__visual">
                                    <div class="kt-pricing-1__hexagon2"></div>
                                    <span class="kt-pricing-1__icon kt-font-brand"><i class="fa flaticon-gift"></i></span>
                                </div>
                                <span class="kt-pricing-1__price">Product fields</span>
                                <h2 class="kt-pricing-1__subtitle">Add Product fields to collection</h2>
                                <div class="kt-pricing-1__btn">
                                    <a href="{{ url('copernica/collection/fields/create/product') }}"
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
