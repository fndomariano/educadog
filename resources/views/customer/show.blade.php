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
	</table>
	
	<hr>
	
	<a href="{{ route('customer_index') }}" class="btn btn-danger">Voltar</a>

@stop