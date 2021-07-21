@extends('layouts.app')

@section('title', 'Atividades')

@section('content_header')
	
	@include('partials.feedback')

    <div class="row">    	
    	<div class="col-md-6">
    		<h1>Atividades</h1>
    	</div>
		<div class="col-md-6">
			<form method="get" action="{{ route('activity_index') }}">		    
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
                    <a href="{{ route('activity_create') }}">
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
                        <th>Data</th>
                        <th>Nota</th>
                        <th>Pet</th>
                        <th>Cliente</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($activities) > 0)
                        @foreach ($activities as $activity)
                            <tr>
                                <td>{{ $activity->id }}</td>
                                <td>
                                    <a href="{{ route('activity_show', $activity->id) }}">{{ date('d/m/Y', strtotime($activity->activity_date)) }}</a>
                                </td>
                                <td>{{ $activity->score }}</td>
                                <td>
                                    <a href="{{ route('pet_show', $activity->pet_id) }}">{{ $activity->pet->name }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('customer_show', $activity->pet->customer_id) }}">{{ $activity->pet->customer->name }}</a>
                                </td>                                
                                <td>
                                    <a href="{{ route('activity_edit', $activity->id) }}" class="btn btn-info btn-sm" alt="Editar">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm delete form-delete-{{ $activity->id }}" alt="Excluir">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <form action="{{ route('activity_delete', $activity->id) }}" method="post" class="form-delete-{{ $activity->id }}" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">                                    
                                    </form> 
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align: center;">Não há atividades cadastradas.</td>
                        </tr>				        
                    @endif 
                </tbody>
            </table>
        </div>
    </div>
    
    @include('partials.pagination', ['paginator' => $activities])
	
	@include('partials.modal')

@stop


