@extends('admin.master') @section('admin_active', 'kt-menu__item--open') @section('admin_users_sub_active',
'kt-menu__item--active') @section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        Credentials
                    </h3>
                    <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                    <div class="kt-subheader__group" id="kt_subheader_search"> <span class="kt-subheader__desc"
                            id="kt_subheader_total">
                            Setup user Credentials
                        </span>
                    </div>
                </div>
                <div class="kt-subheader__toolbar"> <a href="{{ url('/') }}" class="btn btn-default btn-bold"><i
                            class="la la-long-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="kt-portlet">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="first">
                        <div class="kt-grid__item">
                            <!--begin: Form Wizard Nav -->
                            <div class="kt-wizard-v3__nav">
                                <!--doc: Remove "kt-wizard-v3__nav-items--clickable" class and also set 'clickableSteps: false' in the JS init to disable manually clicking step titles -->
                                <div class="kt-wizard-v3__nav-items kt-wizard-v3__nav-items--clickable">
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="current">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>1</span> Lightspeed Details
                                            </div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="pending">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>2</span> Copernica Details</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="pending">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>3</span> User database</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="pending">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>4</span> User fields</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="pending">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>5</span> Final</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper">

                            <form class="kt-form" id="kt_form" novalidate="novalidate" method="post"
                                action={{ url('wizard') }}>
                                @csrf
                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content"
                                    data-ktwizard-state="current">
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v3__form">
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">API key</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $lightspeedAuth ? $lightspeedAuth->api_key : '' }}"
                                                        name="lightspeed[api_key]" required>
                                                    @if ($errors->has('api_key'))
                                                        <span
                                                            class="error invalid-feedback d-block">{{ $errors->first('api_key') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">API secret</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $lightspeedAuth ? $lightspeedAuth->api_secret : '' }}"
                                                        name="lightspeed[api_secret]" required>
                                                    @if ($errors->has('api_secret'))
                                                        <span
                                                            class="error invalid-feedback d-block">{{ $errors->first('api_secret') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v3__form">
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">API key</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $copernicaAuth ? $copernicaAuth->api_key : '' }}"
                                                        name="copernica[api_key]" required>
                                                    @if ($errors->has('api_key'))
                                                        <span
                                                            class="error invalid-feedback d-block">{{ $errors->first('api_key') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">API secret</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $copernicaAuth ? $copernicaAuth->api_secret : '' }}"
                                                        name="copernica[api_secret]" required>
                                                    @if ($errors->has('api_secret'))
                                                        <span
                                                            class="error invalid-feedback d-block">{{ $errors->first('api_secret') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Token</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $copernicaAuth ? $copernicaAuth->token : '' }}"
                                                        name="copernica[token]" required>
                                                    @if ($errors->has('token'))
                                                        <span
                                                            class="error invalid-feedback d-block">{{ $errors->first('token') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v3__form">
                                            <div class="form-group row">
                                                <h1>Click 'Next' to install user database.</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v3__form">
                                            <div class="form-group row">
                                                <h1>Click 'Next' to Add fields to user database.</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v3__form">
                                            <div class="form-group row">
                                                <h1>You are all done!</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-form__actions">
                                    <button
                                        class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u"
                                        data-ktwizard-type="action-prev" type="button">Previous</button>
                                    <button
                                        class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u"
                                        data-ktwizard-type="action-submit" type="submit">Submit</button>
                                    <button class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u"
                                        data-ktwizard-type="action-next" type="button">Next Step</button>
                                </div>
                                <!--end: Form Actions -->
                            </form>
                            <!--end: Form Wizard Form-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>@endsection


@section('script')
    <script>
        jQuery(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            // KTWizard3.init();
            wizard = new KTWizard("kt_wizard_v3", {
                startStep: 1, // Initial active step number
                clickableSteps: true, // Allow step clicking
            }).on("beforeNext", function(e) {
                if (e.currentStep == 1) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/lightspeed-auth-api/settings') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            api_key: $('[name="lightspeed[api_key]"]').val(),
                            api_secret: $('[name="lightspeed[api_secret]"]').val(),
                        },
                        success: function(data) {
                            // goNext();
                        },
                        error: function(data) {
                            swal.fire({
                                title: "",
                                text: "There are some errors in your submission. Please correct them.",
                                type: "error",
                                confirmButtonClass: "btn btn-secondary",
                            });
                            wizard.goTo(1);
                        },
                    });
                } else if (e.currentStep == 2) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/copernica-auth-api/settings') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            api_key: $('[name="copernica[api_key]"]').val(),
                            api_secret: $('[name="copernica[api_secret]"]').val(),
                            token: $('[name="copernica[token]"]').val(),
                        },
                        success: function(data) {
                            // goNext();
                        },
                        error: function(data) {
                            swal.fire({
                                title: "",
                                text: "There are some errors in your submission. Please correct them.",
                                type: "error",
                                confirmButtonClass: "btn btn-secondary",
                            });
                            wizard.goTo(2);
                        },
                    });
                } else if (e.currentStep == 3) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('copernica/database/create/user-api') }}",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            //goNext();
                        },
                        error: function(data) {
                            console.log(data)
                            swal.fire({
                                title: "",
                                text: data.responseJSON.message,
                                type: "error",
                                confirmButtonClass: "btn btn-secondary",
                            });
                        },
                    });
                } else if (e.currentStep == 4) {

                    $.ajax({
                        type: "GET",
                        url: "{{ url('copernica/database/fields/create/user-api') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(data) {
                            //goNext();
                        },
                        error: function(data) {
                            console.log(data)
                            swal.fire({
                                title: "",
                                text: data.responseJSON.message,
                                type: "error",
                                confirmButtonClass: "btn btn-secondary",
                            });
                        },
                    });
                }
            });
        });

    </script>
@endsection
