@if(\Illuminate\Support\Facades\Session::has('success'))
    <div class="alert alert-success">
        <p>{{ session('success') }}</p>
    </div>
@endif

@error('error')
<div class="alert alert-danger">
    {{ $message }}
</div>
@enderror
