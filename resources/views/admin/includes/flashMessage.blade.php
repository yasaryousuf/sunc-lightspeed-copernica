@if (Session::has('success'))
    <div class="alert alert-success" role="alert">
        <div class="alert-text">{{ Session::get('success') }}</div>
    </div>
    {{--<script>
        < ? php /* blade_comment_end */ ? >
            <
            ? php /* blade_comment_start */ ? > toastr.success("{{ Session::get('success') }}"); < ?
        php /* blade_comment_end */ ? >
            <
            ? php /* blade_comment_start */ ? >

    </script>--}}
@endif
@if (Session::has('warning'))
    {{-- <script>
        < ? php /* blade_comment_end */ ? >
            <
            ? php /* blade_comment_start */ ? > toastr.error("{{ Session::get('warning') }}"); < ?
        php /* blade_comment_end */ ? >
            <
            ? php /* blade_comment_start */ ? >

    </script>--}}
    <div class="alert alert-warning" role="alert">
        <div class="alert-text">{{ Session::get('warning') }}</div>
    </div>
@endif
