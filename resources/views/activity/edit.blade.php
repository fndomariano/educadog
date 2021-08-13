@extends('layouts.app')

@section('title', 'Editar atividade')

@section('content_header')
	<h1>Editar atividade</h1>
@stop

@section('content')
	
	<form class="form" action="{{ route('activity_update', $activity->id) }}" method="post" enctype="multipart/form-data">
		@csrf
        @method('PUT')
		<div class="form-group">
			<label for="activity_date">Data *</label>
			<input type="text" name="activity_date" id="activity_date" class="date form-control" value="{{ date('d/m/Y', strtotime($activity->activity_date)) }}"/>
			<span class="text-danger">{{ $errors->first('activity_date') }}</span>   
		</div>

        <div class="form-group">
			<label for="pet_id">Pet *</label>
			<select name="pet_id" class="form-control select2" id="pet_id" placeholder="Selecione um pet...">
				<option></option>
                @foreach ($pets as $pet)
                    <option {{ $pet->id == $activity->pet_id ? 'selected' : '' }} value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->customer->name }})</option>               
                @endforeach
            </select>
            <span class="text-danger">{{ $errors->first('pet_id') }}</span>
		</div>

        <div class="form-group">
			<label for="score">Nota *</label>
			<input type="text" name="score" id="score" class="form-control" value="{{ $activity->score }}"/>
			<span class="text-danger">{{ $errors->first('score') }}</span>   
		</div>

        <div class="form-group">
			<label for="description">Descrição *</label>
			<textarea name="description" class="form-control editor">{{ $activity->description }}</textarea>
			<span class="text-danger">{{ $errors->first('description') }}</span>   
		</div>

		<div class="form-group">
			<label for="files">Arquivos *</label>
			<input type="file" name="files[]" class="form-control" multiple></textarea>
			<span class="text-danger">{{ $errors->first('files.*') }}</span>   
		</div>

		@include('activity.gallery', ['activity' => $activity])

      	<hr>
		<a href="{{ route('activity_index') }}" class="btn btn-danger">Voltar</a>
		<button type="submit" class="btn btn-success float-right">Salvar</button>
	</form>
	
	@include('partials.modal')
@stop