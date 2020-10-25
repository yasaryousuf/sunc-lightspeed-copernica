@extends('admin.master')

@section('admin_active', 'kt-menu__item--open')

@section('admin_lights_sub_active', 'kt-menu__item--active')

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        Lightspeed API
                    </h3>
                    <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                    <div class="kt-subheader__group" id="kt_subheader_search">
                        <span class="kt-subheader__desc" id="kt_subheader_total">
                            API keys and secrets
                        </span>
                    </div>
                </div>
                <div class="kt-subheader__toolbar">
                    <a href="{{ url('/') }}" class="btn btn-default btn-bold"><i class="la la-long-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="kt-font-brand flaticon2-line-chart"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Lightspeed APIs table
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th>Name</th>
                                <th>Key</th>
                                <th>Secret</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($lightspeedAuths)
                                @foreach ($lightspeedAuths as $lightspeedAuth)
                                    <tr>
                                        <td>{{ $lightspeedAuth->id }}</td>
                                        <td>{{ $lightspeedAuth->created_at }}</td>
                                        <td>{{ $lightspeedAuth->updted_at }}</td>
                                        <td>{{ $lightspeedAuth->user->name }}</td>
                                        <td>{{ $lightspeedAuth->api_key }}</td>
                                        <td>{{ $lightspeedAuth->api_secret }}</td>
                                        <td>
                                            <span>
                                                <a title="Edit" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                                    <i class="la la-edit"></i></a>
                                                <a title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                                    <i class="la la-trash"></i> </a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
