@extends('admin.master')

@section('admin_active', 'kt-menu__item--open')

@section('admin_users_sub_active', 'kt-menu__item--active')

@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        Users
                    </h3>
                    <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                    <div class="kt-subheader__group" id="kt_subheader_search">
                        <span class="kt-subheader__desc" id="kt_subheader_total">
                            Manage users
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
                            Users table
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($users->first())
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td> <a href="{{ url('/admin/user/change-status/' . $user->id) }}"
                                                onclick="return confirm('Are you sure?')"
                                                class='btn btn-sm btn-{{ $user->active ? 'success' : 'danger' }}'>{{ $user->active ? 'Active' : 'Inactive' }}</a>
                                        </td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            <span>
                                                {{-- <a title="Edit"
                                                    class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                                    <i class="la la-edit"></i></a> --}}
                                                <a title="Delete" onclick="return confirm('Are you sure?')"
                                                    href="{{ url('/admin/user/delete/' . $user->id) }}"
                                                    class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                                    <i class="la la-trash"></i> </a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">No data found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
