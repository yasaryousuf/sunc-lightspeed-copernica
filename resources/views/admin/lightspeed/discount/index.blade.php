@extends('admin.master')

@section('light_active', 'kt-menu__item--open')

@section('light_discount_sub_active', 'kt-menu__item--active')

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

        <!-- begin:: Subheader -->
        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title"> Discounts</h3>

                    <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                    <div class="kt-subheader__group" id="kt_subheader_search">
                        <span class="kt-subheader__desc" id="kt_subheader_total">
                            from Lightspeed
                        </span>

                    </div>
                </div>
                <div class="kt-subheader__toolbar">
                    <div class="kt-subheader__wrapper">
                        <a href="{{ url('/') }}" class="btn kt-subheader__btn-primary">
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: Subheader -->

        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="kt-font-brand flaticon2-line-chart"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Discount table
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <a href="{{ url('lightspeed/discounts/import') }}" class="btn btn-brand btn-icon-sm">
                                <i class="flaticon-upload"></i>
                                Import
                            </a>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <table class="table table-striped table-responsive table-bordered table-hover table-checkable"
                        id="kt_table_1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th>Is Active?</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Type</th>
                                <th>Discount</th>
                                <th>Apply to</th>
                                <th>Shipment</th>
                                <th>Usage limit</th>
                                <th>Times used</th>
                                <th>Minimum Amount</th>
                                <th>Before Tax</th>
                                <th>Minimum after</th>
                                <th>Code</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (!empty($discounts))
                                @foreach ($discounts as $discount)
                                    <tr>
                                        <td>{{ $discount['id'] }}</td>
                                        <td>{{ $discount['createdAt'] }}</td>
                                        <td>{{ $discount['updatedAt'] }}</td>
                                        <td>{{ $discount['isActive'] ? 'Yes' : 'No' }}</td>
                                        <td>{{ $discount['startDate'] }}</td>
                                        <td>{{ $discount['endDate'] }}</td>
                                        <td>{{ $discount['type'] }}</td>
                                        <td>{{ $discount['discount'] }}</td>
                                        <td>{{ $discount['applyTo'] }}</td>
                                        <td>{{ $discount['shipment'] }}</td>
                                        <td>{{ $discount['usageLimit'] }}</td>
                                        <td>{{ $discount['timesUsed'] }}</td>
                                        <td>{{ $discount['minimumAmount'] }}</td>
                                        <td>{{ $discount['before_tax'] ? 'Yes' : 'No' }}</td>
                                        <td>{{ $discount['minimum_after'] ? 'Yes' : 'No' }}</td>
                                        <td>{{ $discount['code'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="16">No data found</td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                    <!--end: Datatable -->
                </div>
            </div>
        </div>
        <!-- end:: Content -->
    </div>
@endsection
