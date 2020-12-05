@if ($errors->any())
    <div id="alert-error">
        <image id='warning-icon' src="{{ asset('images/icons/warning.svg') }}" />
        <div id="errors-container">
            @foreach ($errors->all() as $error)
                <h4>{{ $error }}</h4>
            @endforeach
        </div>    
    </div>
@endif