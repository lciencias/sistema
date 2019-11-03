@extends ('layouts.home')
@section ('contenido')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!-- <h3>Nueva Competencia</h3> -->
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
			{!!Form::open(array('url'=>'catalogos/competencia','method'=>'POST','autocomplete'=>'off','id'=>'validateFormCompetencia','data-toggle'=>'validator', 'role'=>'form','files' => true))!!}
            {{Form::token()}}
            @include('catalogos.competencia.forms.competencia')  
			{!!Form::close()!!}		            
		</div>
	</div>
@endsection