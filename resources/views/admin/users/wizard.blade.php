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
                                            <div class="kt-wizard-v3__nav-label"> <span>4</span> Checkout database</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="pending">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>5</span> Order Collection</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="pending">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>6</span> Row Collection</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="pending">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>7</span> Collect data</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="pending">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>8</span> Sync data</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step"
                                        data-ktwizard-state="pending">
                                        <div class="kt-wizard-v3__nav-body">
                                            <div class="kt-wizard-v3__nav-label"> <span>9</span> Final</div>
                                            <div class="kt-wizard-v3__nav-bar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress" style="height: 20px; border-radius: 0">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100">0%</div>
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
                                                <h1>Click 'Next' to install checkout database.</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v3__form">
                                            <div class="form-group row">
                                                <h1>Click 'Next' to Add order collection to database.</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v3__form">
                                            <div class="form-group row">
                                                <h1>Click 'Next' to Add order row collection to database.</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v3__form">
                                            <div class="form-group row">
                                                <h1>Click 'Next' to Add collection to our database.</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v3__form">
                                            <div class="form-group row">
                                                <h1>Click 'Next' to sync collection to copernica.</h1>
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
                    clickableSteps: false, // Allow step clicking
                })
                .on('change', function(e) {
                    $('.progress .progress-bar').css("width", `${(e.currentStep-1)*12.5}%`).text(
                        `${(e.currentStep-1)*12.5}%`);
                })
                .on("beforeNext", function(e) {
                    $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                        .prop("disabled", true).addClass('spinner').addClass('spinner-right');
                    if (e.currentStep == 1) {
                        $('.progress .progress-bar').css("width", "12.5%").text("12.5%");
                        $.ajax({
                            type: "POST",
                            url: "{{ url('/lightspeed-auth-api/settings') }}",
                            data: {
                                _token: '{{ csrf_token() }}',
                                api_key: $('[name="lightspeed[api_key]"]').val(),
                                api_secret: $('[name="lightspeed[api_secret]"]').val(),
                            },
                            success: function(data) {
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                            },
                            error: function(data) {
                                swal.fire({
                                    title: "",
                                    text: "There are some errors in your submission. Please correct them.",
                                    type: "error",
                                    confirmButtonClass: "btn btn-secondary",
                                });
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                                wizard.goTo(1);
                            },
                        });
                    } else if (e.currentStep == 2) {
                        $('.progress .progress-bar').css("width", "25%").text("25%");
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
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                            },
                            error: function(data) {
                                swal.fire({
                                    title: "",
                                    text: "There are some errors in your submission. Please correct them.",
                                    type: "error",
                                    confirmButtonClass: "btn btn-secondary",
                                });
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                                wizard.goTo(2);
                            },
                        });
                    } else if (e.currentStep == 3) {
                        $('.progress .progress-bar').css("width", "37.5%").text("37.5%");
                        $.ajax({
                            type: "GET",
                            url: "{{ url('copernica/database/create/user-api') }}",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                            },
                            error: function(data) {
                                swal.fire({
                                    title: "",
                                    text: data.responseJSON.message,
                                    type: "error",
                                    confirmButtonClass: "btn btn-secondary",
                                });
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');

                                // wizard.goTo(3);
                            },
                        });
                    } else if (e.currentStep == 4) {
                        $('.progress .progress-bar').css("width", "50%").text("50%");
                        $.ajax({
                            type: "GET",
                            url: "{{ url('copernica/database/create/checkout-api') }}",
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                            },
                            error: function(data) {
                                swal.fire({
                                    title: "",
                                    text: data.responseJSON.message,
                                    type: "error",
                                    confirmButtonClass: "btn btn-secondary",
                                });
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                                // wizard.goTo(4);
                            },
                        });
                    } else if (e.currentStep == 5) {
                        $('.progress .progress-bar').css("width", "62.5%").text("62.5%");
                        $.ajax({
                            type: "GET",
                            url: "{{ url('copernica/collection/create/order-api') }}",
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                //goNext();
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                            },
                            error: function(data) {
                                console.log(data)
                                swal.fire({
                                    title: "",
                                    text: data.responseJSON.message,
                                    type: "error",
                                    confirmButtonClass: "btn btn-secondary",
                                });
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                                // wizard.goTo(5);
                            },
                        });
                    } else if (e.currentStep == 6) {
                        $('.progress .progress-bar').css("width", "75%").text("75%");
                        $.ajax({
                            type: "GET",
                            url: "{{ url('copernica/collection/create/orderrow-api') }}",
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                //goNext();
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                            },
                            error: function(data) {
                                console.log(data)
                                swal.fire({
                                    title: "",
                                    text: data.responseJSON.message,
                                    type: "error",
                                    confirmButtonClass: "btn btn-secondary",
                                });
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                                // wizard.goTo(6);
                            },
                        });
                    } else if (e.currentStep == 7) {
                        $('.progress .progress-bar').css("width", "87.5%").text("87.5%");
                        $.ajax({
                            type: "GET",
                            url: "{{ url('lightspeed/import') }}",
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                //goNext();
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                            },
                            error: function(data) {
                                console.log(data)
                                swal.fire({
                                    title: "",
                                    text: data.responseJSON.message,
                                    type: "error",
                                    confirmButtonClass: "btn btn-secondary",
                                });
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                                // wizard.goTo(6);
                            },
                        });
                    } else if (e.currentStep == 8) {
                        $('.progress .progress-bar').css("width", "100%").text("100%");
                        $.ajax({
                            type: "GET",
                            url: "{{ url('copernica/profile/create') }}",
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                //goNext();
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                            },
                            error: function(data) {
                                console.log(data)
                                swal.fire({
                                    title: "",
                                    text: data.responseJSON.message,
                                    type: "error",
                                    confirmButtonClass: "btn btn-secondary",
                                });
                                $("[data-ktwizard-type='action-next'], [data-ktwizard-type='action-prev'], [data-ktwizard-type='action-submit']")
                                    .prop("disabled", false)
                                    .removeClass('spinner').removeClass('spinner-right');
                                // wizard.goTo(6);
                            },
                        });
                    }
                });
        });

    </script>
@endsection

@section('style')
    <style>
        .kt-wizard-v3 .kt-wizard-v3__nav .kt-wizard-v3__nav-items .kt-wizard-v3__nav-item {
            -ms-flex: 0 0 11.5%;
            flex: 0 0 11.5%;
        }

        .spinner.spinner-right.btn:not(.btn-block) {
            padding-right: 3.5rem;
        }

        .spinner {
            position: relative;
        }

        .spinner.spinner-right:before {
            left: auto;
            right: 1rem;
        }

        .spinner:before {
            -webkit-animation: animation-spinner .5s linear infinite;
            animation: animation-spinner .5s linear infinite;
        }

        .spinner:before {
            width: 1.5rem;
            height: 1.5rem;
            margin-top: -0.75rem;
        }

        .spinner:before {
            content: '';
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            position: absolute;
            top: 50%;
            left: 0;
            border-radius: 50%;
            border: 2px solid #D1D3E0;
            border-right: 2px solid transparent;
        }

        @-webkit-keyframes animation-spinner {
            to {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes animation-spinner {
            to {
                -webkit-transform: rotate(360deg);
                t

    </style>
@endsection
