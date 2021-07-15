@extends('layouts.app')

@section('title', 'Editar cliente')

@section('content_header')
	<h1>Editar cliente</h1>
@stop

@section('content')
	
	<form class="form" action="{{ route('customer_update', $customer->id) }}" method="post">
		@csrf
        @method('PUT')
		<div class="form-group">
			<label for="name">Nome *</label>
			<input type="text" name="name" id="name" class="form-control" value="{{ $customer->name }}"/>
			<span class="text-danger">{{ $errors->first('name') }}</span>   
		</div>

		<div class="form-group">
			<label for="email">E-mail *</label>
			<input type="text" name="email" id="email" class="form-control" value="{{ $customer->email }}"/>
			<span class="text-danger">{{ $errors->first('email') }}</span>   
		</div>

		<div class="form-group">
			<label for="phone">Telefone *</label>
			<input type="text" name="phone" id="phone" class="form-control" value="{{ $customer->phone  }}"/>
			<span class="text-danger">{{ $errors->first('phone') }}</span>   
		</div>

      	<hr>
		<a href="{{ route('customer_index') }}" class="btn btn-danger">Voltar</a>
		<button type="submit" class="btn btn-success float-right">Salvar</button>
	</form>
	
@stop