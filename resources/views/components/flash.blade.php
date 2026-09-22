@if(session('success'))
    <div class="toast" role="status">
        <i class="fa-solid fa-check mr-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="toast error-toast" role="alert">
        <i class="fa-solid fa-circle-exclamation mr-2"></i>
        {{ $errors->first() }}
    </div>
@endif