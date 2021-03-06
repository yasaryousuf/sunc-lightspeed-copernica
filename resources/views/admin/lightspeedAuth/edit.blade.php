@extends('admin.master')

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content Head -->
        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">

                    <h3 class="kt-subheader__title">
                        Lightspeed credentials
                    </h3>

                    <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                    <div class="kt-subheader__group" id="kt_subheader_search">
                        <span class="kt-subheader__desc" id="kt_subheader_total">
                           Save or edit lightspeed API key and secret
                        </span>

                    </div>

                </div>
                <div class="kt-subheader__toolbar">

                    <a href="{{url('/')}}" class="btn btn-default btn-bold">

                        Back                </a>

                </div>
            </div>
        </div>
        <!-- end:: Content Head -->
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="kt-wizard-v4" id="kt_user_add_user" data-ktwizard-state="first">
                <div class="kt-portlet">
                    <div class="kt-portlet__body kt-portlet__body--fit">
                        <div class="kt-grid">
                            <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v4__wrapper">
                                <form class="kt-form" id="kt_user_add_form" novalidate="novalidate" action="{{url('lightspeed-auth/settings')}}" method="post">
                                    @csrf
                                    <div class="kt-wizard-v4__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
                                        <div class="kt-heading kt-heading--md">Lightspeed Details:</div>
                                        <div class="kt-section kt-section--first">
                                            <div class="kt-wizard-v4__form">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="kt-section__body">
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">API key</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input class="form-control" type="text" value="{{$lightspeedAuth ? $lightspeedAuth->api_key : ''}}" name="api_key">
                                                                    @if ($errors->has('api_key'))
                                                                        <span class="error invalid-feedback d-block">{{ $errors->first('api_key') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">API secret</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input class="form-control" type="text" value="{{$lightspeedAuth ? $lightspeedAuth->api_secret : ''}}" name="api_secret">
                                                                    @if ($errors->has('api_secret'))
                                                                        <span class="error invalid-feedback d-block">{{ $errors->first('api_secret') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-form__actions">
                                        <button class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" type="submit">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection