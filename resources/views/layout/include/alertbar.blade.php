@if ($errors->any())
    <div class="alert alert-danger error-message">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if ( session('error') )
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if ( session('info') )
    <div class="alert alert-info">{{ session('info') }}</div>
@endif