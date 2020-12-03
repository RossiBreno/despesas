@extends('template.index')
@section('title', 'Editar Despesa')
@section('content')
<div class='card'>
    <h1>Editar uma despesa</h1>

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

    <form method="POST" action="{{route('despesa.edit', $despesa->id)}}"  enctype="multipart/form-data">
    @csrf
    @method('put')
        <div class="inputs-container">
            <div class="inputs-container-column">
                <input type="text" name="value" value="{{$despesa->value}}" class="value <?php if($errors->first('value')) { echo 'input-error'; } ?>" placeholder="Preço" required/>
                <input type="text" name="date" value="{{$despesa->date}}" class="date <?php if($errors->first('date')) { echo 'input-error'; } ?>" placeholder="Data" required/>
                <input id="input-file" type="file" name="image" />
                <label id="label-input-file" class="<?php if($errors->first('image')) { echo 'input-error'; } ?>" for="input-file">Mudar a foto</label>

            </div>
            <div class="inputs-container-row">
                <textarea id="desc" name="desc" placeholder="Descrição" class="<?php if($errors->first('desc')) { echo 'input-error'; } ?>" required>{{$despesa->desc}}</textarea>
                <h5 id="carac-length">{{ strlen($despesa->desc) }}/500 caracteres</h5>
            </div>
        </div>
        <div class="button-container">
            <button class="purple-button">Editar</button>
        </div>
    </form>
</div>
@endsection