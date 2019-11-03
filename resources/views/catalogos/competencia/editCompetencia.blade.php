@extends ('layouts.home')
@section ('contenido')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!-- <h3>Editar Competencia: {{ $competencia->nombre}}</h3>-->
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
			{!!Form::model($competencia,['method'=>'post','id'=>'validateFormCompetencia','data-toggle'=>'validator', 'role'=>'form',
				'files' => true,'url'=>['catalogos.competencia.update',$competencia->idcompetencia]])!!}
          	 	@include('catalogos.competencia.forms.competencia')
			{!!Form::close()!!}		
            
		</div>
	</div>
@endsection