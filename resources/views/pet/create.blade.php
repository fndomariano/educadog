@extends('layouts.app')

@section('title', 'Novo pet')

@section('content_header')
	<h1>Novo pet</h1>
@stop

@section('content')
	
	<form class="form" action="{{ route('pet_store') }}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label for="name">Nome *</label>
			<input type="text" name="name" id="name" class="form-control"/>
			<span class="text-danger">{{ $errors->first('name') }}</span>   
		</div>

		<div class="form-group">
			<label for="email">Raça *</label>
			<input type="text" name="breed" id="breed" class="form-control"/>
			<span class="text-danger">{{ $errors->first('breed') }}</span>   
		</div>

        <div class="form-group">
			<label for="phone">Cliente *</label>
			<select name="customer_id" class="form-control select2" placeholder="Selecione um cliente...">
				<option></option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>               
                @endforeach
            </select>
            <span class="text-danger">{{ $errors->first('customer_id') }}</span>
		</div>

        <div class="form-group">
			<label for="phone">Foto</label>
			<input type="file" name="photo" id="photo" class="form-control"/>
		</div>

		<div class="form-check">
        	<input type="checkbox" name="active" class="form-check-input" id="active" />
        	<label for="active">Ativo</label>
      	</div>

      	<hr>
		<a href="{{ route('pet_index') }}" class="btn btn-danger">Voltar</a>
		<button type="submit" class="btn btn-success float-right">Salvar</button>
	</form>
	
@stop