@extends ('layouts.home')
@section ('contenido')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::open(array('url'=>'catalogos/ejercicio','method'=>'POST','autocomplete'=>'off','id'=>'validateFormEjercicio','data-toggle'=>'validator', 'role'=>'form' ))!!}
           		@include('catalogos.ejercicio.forms.ejercicio')           
			{!!Form::close()!!}		
            
		</div>
	</div>
@endsection