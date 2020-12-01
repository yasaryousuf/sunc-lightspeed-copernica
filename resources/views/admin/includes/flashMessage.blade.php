@if (Session::has('success'))
    <div class="alert alert-success" role="alert">
        <div class="alert-text">{{ Session::get('success') }}</div>
    </div>

@endif
@if (Session::has('warning'))
    <div class="alert alert-danger" role="alert">
        <div class="alert-text">{{ Session::get('warning') }}</div>
    </div>
@endif
@if (Session::has('danger'))
    <div class="alert alert-danger" role="alert">
        <div class="alert-text">{{ Session::get('danger') }}</div>
    </div>
@endif
