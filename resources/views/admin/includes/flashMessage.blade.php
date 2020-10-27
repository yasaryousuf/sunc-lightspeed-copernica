@if (Session::has('success'))
    <div class="alert alert-success" role="alert">
        <div class="alert-text">{{ Session::get('success') }}</div>
    </div>

@endif
@if (Session::has('warning'))
    <div class="alert alert-warning" role="alert">
        <div class="alert-text">{{ Session::get('warning') }}</div>
    </div>
@endif
