@extends('layouts.app')

@section('title', 'Detalhes da atividade')

@section('content_header')
    <h1>Detalhes da atividade</h1>
@stop

@section('content')
	
	<table class="table table-bordered table-condensed table-striped">
		
        <tr>
			<th>Código</th>
			<td>{{ $activity->id }}</td>
		</tr>

		<tr>
			<th>Data</th>
			<td>{{ date('d/m/Y', strtotime($activity->activity_date)) }}</td>
		</tr>

        <tr>
			<th>Nota</th>
			<td>{{ $activity->score }}</td>
		</tr>

		<tr>
			<th>Pet</th>
			<td><a href="{{ route('pet_show', $activity->pet_id) }}">{{ $activity->pet->name }}</a></td>
		</tr>

		<tr>
			<th>Cliente</th>
			<td><a href="{{ route('customer_show', $activity->pet->customer_id) }}">{{ $activity->pet->customer->name }}</a></td>
		</tr>

		<tr>
			<th>Descrição</th>
			<td>{!! $activity->description !!}</td>
        </tr>
		
	</table>

	@include('activity.gallery', ['activity' => $activity])

	@include('partials.modal')
	
	<hr>
	
	<a href="{{ route('activity_index') }}" class="btn btn-danger">Voltar</a>

@stop