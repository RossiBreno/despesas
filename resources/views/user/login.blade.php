@extends('template.index')
@section('title', 'Login')
@section('content')
<div class='card-small'>
    <div id='image-logo-container'>
        <image id='image-logo' src="{{ asset('images/logo-branca.svg') }}" />
    </div>

    <form method="POST" action="{{route('user.login')}}">
    @csrf
        <div class="inputs-container">
            <input type="email" name="email" class="<?php if($errors->first('error')) { echo 'input-error'; } ?>" placeholder="Email" required/>
            <input type="password" name="password" class="<?php if($errors->first('error')) { echo 'input-error'; } ?>" placeholder="Senha" required/>
            @if ($errors->any())
                <h5 id="text-error-message">Usuário e/ou Senha inválido!</h5>
            @endif
        </div>
        <div class="button-container">
            <button class="purple-button">Entrar</button>
            <button type="button" class="white-button" onclick="window.location='{{ route("user.register.index") }}'">Cadastrar-se</button>
        </div>  
    </form>
</div>
@endsection