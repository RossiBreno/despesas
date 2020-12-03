@extends('template.index')
@section('title', 'Foto da Despesa')
@section('content')

<div class="card-image">
    <div id="title">
        <a href="{{ route('dashboard.index') }}">
            <img src="{{ asset('images/icons/angle-left.svg') }}">
        </a>
        <h1>Imagem da despesa</h1>
    </div>
    <img id="show-image" src="{{ route('despesa.image.get', $id) }}">
</div>

@endsection