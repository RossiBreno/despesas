@extends('template.index')
@section('title', 'Erro')
@section('content')
<div class='card-small error-messages'>
    <h2>{{ $exception->getMessage() }}</h2>
</div>
@endsection
