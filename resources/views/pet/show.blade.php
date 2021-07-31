@extends('layouts.app')

@section('title', 'Detalhes do pet')

@section('content_header')
    <h1>Detalhes do pet</h1>
@stop

@section('content')
	
	<table class="table table-bordered table-condensed table-striped">
		<tr>
			<th>Código</th>
			<td>{{ $pet->id }}</td>
		</tr>

		<tr>
			<th>Nome</th>
			<td>{{ $pet->name }}</td>
		</tr>

		<tr>
			<th>Cliente</th>
			<td><a href="{{ route('customer_show', $pet->customer_id) }}">{{ $pet->customer->name }}</a></td>
		</tr>

		<tr>
			<th>Raça</th>
			<td>{{ $pet->breed }}</td>
		</tr>

		<tr>
			<th>Ativo</th>
			<td>{{ $pet->active ? 'Sim' : 'Não' }}</td>
		</tr>
		
		@if ($pet->getFirstMedia('pets'))
			<tr>
				<th>Foto</th>			
				<td>
					<div class="col-sm-3">					
						<a href="{{ $pet->getFirstMedia('pets')->getUrl() }}" data-fslightbox>
							<img src="{{ $pet->getFirstMedia('pets')->getUrl() }}" class="mw-100" />
						</a>
					</div>
				</td>
			</tr>
			@include('partials.modal')
		@endif
		
	</table>
	
	<hr>
	
	<a href="{{ route('pet_index') }}" class="btn btn-danger">Voltar</a>

@stop