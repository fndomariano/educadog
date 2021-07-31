@extends('layouts.app')

@section('title', 'Pets')

@section('content_header')
	
	@include('partials.feedback')

    <div class="row">    	
    	<div class="col-md-6">
    		<h1>Pets</h1>
    	</div>
		<div class="col-md-6">
			<form method="get" action="{{ route('pet_index') }}">		    
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
                    <a href="{{ route('pet_create') }}">
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
                        <th>Cliente</th>
                        <th>Raça</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($pets) > 0)
                        @foreach ($pets as $pet)
                            <tr>
                                <td>{{ $pet->id }}</td>
                                <td>
                                    <a href="{{ route('pet_show', $pet->id) }}">{{ $pet->name }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('customer_show', $pet->customer_id) }}">{{ $pet->customer->name }}</a>
                                </td>
                                <td>{{ $pet->breed }}</td>
                                <td>{{ $pet->active ? 'Sim' : 'Não' }}</td>
                                <td>
                                    <a href="{{ route('pet_edit', $pet->id) }}" class="btn btn-info btn-sm" alt="Editar">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm delete form-delete-{{ $pet->id }}" alt="Excluir">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <form action="{{ route('pet_delete', $pet->id) }}" method="post" class="form-delete-{{ $pet->id }}" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">                                    
                                    </form> 
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align: center;">Não há pets cadastrados.</td>
                        </tr>				        
                    @endif 
                </tbody>
            </table>
        </div>
    </div>
    
    @include('partials.pagination', ['paginator' => $pets])
	
	@include('partials.modal')

@stop


