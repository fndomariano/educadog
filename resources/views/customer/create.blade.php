@extends('layouts.app')

@section('title', 'Novo cliente')

@section('content_header')
	<h1>Novo cliente</h1>
@stop

@section('content')
	
	<form class="form" action="{{ route('customer_store') }}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label for="name">Nome *</label>
			<input type="text" name="name" id="name" class="form-control"/>
			<span class="text-danger">{{ $errors->first('name') }}</span>   
		</div>

		<div class="form-group">
			<label for="email">E-mail *</label>
			<input type="text" name="email" id="email" class="form-control"/>
			<span class="text-danger">{{ $errors->first('email') }}</span>   
		</div>

		<div class="form-group">
			<label for="phone">Telefone *</label>
			<input type="text" name="phone" id="phone" class="form-control"/>
			<span class="text-danger">{{ $errors->first('phone') }}</span>   
		</div>

        <div class="form-group">
			<label for="contract">Contrato</label>
			<input type="file" name="contract" id="contract" class="form-control"/>
			<span class="text-danger">{{ $errors->first('contract') }}</span>   
		</div>
		
		<div class="form-check">
        	<input type="checkbox" name="active" class="form-check-input" id="active" />
        	<label for="active">Ativo</label>
      	</div>

      	<hr>
		<a href="{{ route('customer_index') }}" class="btn btn-danger">Voltar</a>
		<button type="submit" class="btn btn-success float-right">Salvar</button>
	</form>
	
@stop