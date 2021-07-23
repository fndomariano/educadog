@extends('layouts.app')

@section('title', 'Nova atividade')

@section('content_header')
	<h1>Nova atividade</h1>
@stop

@section('content')
	
	<form class="form" action="{{ route('activity_store') }}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label for="activity_date">Data *</label>
			<input type="text" name="activity_date" id="activity_date" class="date form-control"/>
			<span class="text-danger">{{ $errors->first('activity_date') }}</span>   
		</div>

        <div class="form-group">
			<label for="pet_id">Pet *</label>
			<select name="pet_id" class="form-control select2" id="pet_id" placeholder="Selecione um pet...">
				<option></option>
                @foreach ($pets as $pet)
                    <option value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->customer->name }})</option>               
                @endforeach
            </select>
            <span class="text-danger">{{ $errors->first('pet_id') }}</span>
		</div>

        <div class="form-group">
			<label for="score">Nota *</label>
			<input type="text" name="score" id="score" class="form-control"/>
			<span class="text-danger">{{ $errors->first('score') }}</span>   
		</div>

        <div class="form-group">
			<label for="description">Descrição *</label>
			<textarea name="description" class="form-control editor"></textarea>
			<span class="text-danger">{{ $errors->first('description') }}</span>   
		</div>

      	<hr>
		<a href="{{ route('activity_index') }}" class="btn btn-danger">Voltar</a>
		<button type="submit" class="btn btn-success float-right">Salvar</button>
	</form>
	
@stop