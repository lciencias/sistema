@extends ('layouts.home')
@section ('contenido')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!-- <h3>Editar Candidato: {{ $candidato->nombre}}</h3>-->
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
			{!!Form::model($candidato,['method'=>'post','id'=>'validateFormCandidato','data-toggle'=>'validator', 'role'=>'form',
				'files' => true,'url'=>['gestion.candidato.update',$candidato->idcandidato]])!!}
          	 	@include('gestion.candidato.forms.candidato')
			{!!Form::close()!!}		
            
		</div>
	</div>
@endsection