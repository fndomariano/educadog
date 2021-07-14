@extends('layouts.app')

@section('title', 'Clientes')

@section('content_header')
	
	@include('partials.feedback')

    <div class="row">    	
    	<div class="col-md-6">
    		<h1>Clientes</h1>
    	</div>
		<div class="col-md-6">
			<form method="get" action="{{ route('customer_index') }}">		    
				<div class="input-group float-right" style="width: 250px;">
			    	<input type="text" name="term" class="form-control" placeholder="Buscar">
			        <div class="input-group-append">
			          <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
			        </div>
			  	</div>
		  	</form>
		</div>
	</div>

@stop

@section('content')
	
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <div class="col-md-6">
                    <a href="{{ route('customer_create') }}">
                        <i class="fas fa-plus"></i>
                    </a>		    
                </div>
            </div>
        </div>
            
        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-sm table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>				
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($customers) > 0)
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>
                                    <a href="{{ route('customer_show', $customer->id) }}">{{ $customer->name }}</a>
                                </td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>						
                                <td>
                                    <a href="{{ route('customer_edit', $customer->id) }}" class="btn btn-info btn-sm" alt="Editar">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm delete form-delete-{{ $customer->id }}" alt="Excluir">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <form action="{{ route('customer_delete', $customer->id) }}" method="post" class="form-delete-{{ $customer->id }}" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">                                    
                                    </form> 
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align: center;">Não há pacientes cadastrados.</td>
                        </tr>				        
                    @endif 
                </tbody>
            </table>
        </div>
    </div>
    
    @include('partials.pagination', ['paginator' => $customers])
	
	@include('partials.modal')

@stop


