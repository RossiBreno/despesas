@extends('template.index')

@section('title', 'Cadastro')
@section('content')
<div class='card'>
    <div id='image-logo-container'>
        <image id='image-logo' src="{{ asset('images/logo-branca.svg') }}" />
    </div>

    @component('components.errorMessage')
    @endcomponent
    
    <form method="POST" action="{{route('user.register')}}">
    @csrf
        <div class="inputs-container">
            <div class="inputs-container-column">
                <input type="text" name="name" class="<?php if($errors->first('name')) { echo 'input-error'; } ?>" placeholder="Nome" required/>
                <input type="email" name="email" class="<?php if($errors->first('email')) { echo 'input-error'; } ?>" placeholder="Email" required/>
            </div>
            <div class="inputs-container-column">
                <input type="password" name="password" class="<?php if($errors->first('password')) { echo 'input-error'; } ?>" placeholder="Senha" required/>
                <input type="password" name="confirm_password" class="<?php if($errors->first('confirm_password')) { echo 'input-error'; } ?>" placeholder="Confirmar Senha" required/>
            </div>
        </div>
        <div class="button-container button-row-container">
            <button type="button" class="white-button" onclick="window.location='{{ route("user.login.index") }}'">Login</button>
            <button class="purple-button">Cadastrar-se</button>
        </div>
    </form>
</div>
@endsection