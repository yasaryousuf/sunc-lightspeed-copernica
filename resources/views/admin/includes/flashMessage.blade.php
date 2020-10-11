@if( Session::has( 'success' ))
    <div class="alert alert-success" role="alert">
        <div class="alert-text">{{ Session::get( 'success' ) }}!</div>
    </div>
{{--<script>--}}
{{--    toastr.success("{{ Session::get( 'success' ) }}");--}}
{{--</script>--}}
@endif
@if( Session::has( 'warning' ))
{{--    <script>--}}
{{--        toastr.error("{{ Session::get( 'warning' ) }}");--}}
{{--    </script>--}}
    <div class="alert alert-warning" role="alert">
        <div class="alert-text">{{ Session::get( 'warning' ) }}!</div>
    </div>
@endif