@extends('layouts.app')

@section('title', 'Detalhes do cliente')

@section('content_header')
    <h1>Detalhes do cliente</h1>
@stop

@section('content')
	
	<table class="table table-bordered table-condensed table-striped">
		<tr>
			<th>Código</th>
			<td>{{ $customer->id }}</td>
		</tr>

		<tr>
			<th>Nome</th>
			<td>{{ $customer->name }}</td>
		</tr>

		<tr>
			<th>E-mail</th>
			<td>{{ $customer->email }}</td>
		</tr>

		<tr>
			<th>Telefone</th>
			<td>{{ $customer->phone }}</td>
		</tr>

		<tr>
			<th>Ativo</th>
			<td>{{ $customer->active ? 'Sim' : 'Não' }}</td>
		</tr>
		
		@if (count($customer->pets) > 0)
			<tr>
				<th>Pets</th>
				<td>
					<ul>
						@foreach ($customer->pets as $pet)
							<li>
								<a href="{{ route('pet_show', $pet->id) }}">{{ $pet->name }}</a>
							</li>
						@endforeach
					</ul>
				</td>
			</tr>
		@endif
		
		@if ($customer->getFirstMedia('customers'))
			<tr>
				<th>Contrato</th>			
				<td>
					<a href="{{ $customer->getFirstMedia('customers')->getUrl() }}" target="_blank">
						Clique para visualizar o contrato
					</a>
				</td>
			</tr>
		@endif
	</table>
	
	<hr>
	
	<a href="{{ route('customer_index') }}" class="btn btn-danger">Voltar</a>

@stop