@extends ('layouts.home')
@section ('contenido')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!-- <h3>Nueva TipoEjercicio</h3> -->
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
			{!!Form::open(array('url'=>'catalogos/tipoEjercicio','method'=>'POST','autocomplete'=>'off','id'=>'validateFormTipoEjercicio','data-toggle'=>'validator', 'role'=>'form'))!!}
            {{Form::token()}}
            @include('catalogos.tipoEjercicio.forms.tipoEjercicio')  
			{!!Form::close()!!}		            
		</div>
	</div>
@endsection