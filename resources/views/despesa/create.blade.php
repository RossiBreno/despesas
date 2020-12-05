@extends('template.index')
@section('title', 'Criar Despesa')
@section('content')
<div class='card'>
    <h1>Criar uma despesa</h1>

    @component('components.errorMessage')
    @endcomponent

    <form method="POST" action="{{route('despesa.create')}}" enctype="multipart/form-data">
        @csrf
        <div class="inputs-container">
            <div class="inputs-container-column">
                <input type="text" name="value" class="value {{@$errors->first('value') ? 'input-error' : ''}}" placeholder="Preço" value="R$0,00" required/>
                <input type="text" name="date" class="date {{@$errors->first('date') ? 'input-error' : ''}}" placeholder="Data" required/>
                <input id="input-file" type="file" name="image" required/>
                <label id="label-input-file" class="{{@$errors->first('image') ? 'input-error' : ''}}" for="input-file">Selecione uma foto</label>
            </div>
            <div class="inputs-container-row">
                <textarea id="desc" name="desc" placeholder="Descrição" class="{{@$errors->first('desc') ? 'input-error' : ''}}" required></textarea>
                <h5 id="carac-length">0/500 caracteres</h5>
            </div>
        </div>
        <div class="button-container">
            <button class="purple-button">Criar</button>
        </div>
    </form>
</div>
@endsection