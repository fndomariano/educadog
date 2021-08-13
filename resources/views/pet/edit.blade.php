@extends('layouts.app')

@section('title', 'Editar pet')

@section('content_header')
	<h1>Editar pet</h1>
@stop

@section('content')
	
	<form class="form" action="{{ route('pet_update', $pet->id) }}" method="post" enctype="multipart/form-data">
		@csrf
        @method('PUT')
		<div class="form-group">
			<label for="name">Nome *</label>
			<input type="text" name="name" id="name" class="form-control" value="{{ $pet->name }}"/>
			<span class="text-danger">{{ $errors->first('name') }}</span>   
		</div>

		<div class="form-group">
			<label for="email">Raça *</label>
			<input type="text" name="breed" id="breed" class="form-control" value="{{ $pet->breed }}"/>
			<span class="text-danger">{{ $errors->first('breed') }}</span>   
		</div>

        <div class="form-group">
			<label for="phone">Cliente *</label>
			<select name="customer_id" class="form-control select2" placeholder="Selecione um cliente...">				
                @foreach ($customers as $customer)
                    <option {{ $customer->id == $pet->customer_id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->name }}</option>               
                @endforeach
            </select>
            <span class="text-danger">{{ $errors->first('customer_id') }}</span>
		</div>
		
        <div class="form-group">
			<label for="phone">Foto</label>
			<input type="file" name="photo" id="photo" class="form-control"/>
			<span class="text-danger">{{ $errors->first('photo') }}</span>
			@if ($pet->getFirstMedia('pets'))
				<br>
				<div class="col-sm-3">					
					<a href="{{ $pet->getFirstMedia('pets')->getUrl() }}" data-fslightbox>
						<img src="{{ $pet->getFirstMedia('pets')->getUrl() }}" class="mw-100" />
					</a>
				</div>
				@include('partials.modal')		
			@endif
		</div>

		<div class="form-check">
        	<input type="checkbox" name="active" class="form-check-input" id="active" {{ $pet->active ? 'checked' : '' }} />
        	<label for="active">Ativo</label>
      	</div>

      	<hr>
		<a href="{{ route('pet_index') }}" class="btn btn-danger">Voltar</a>
		<button type="submit" class="btn btn-success float-right">Salvar</button>
	</form>
	
@stop