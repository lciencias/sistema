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
			{!!Form::model($ejercicio,['method'=>'post','id'=>'validateFormEjercicio','data-toggle'=>'validator', 'role'=>'form',
				'url'=>['catalogos.ejercicio.update',$ejercicio->idtipo_ejercicio_cliente]])!!}
           		@include('catalogos.ejercicio.forms.ejercicio')
			{!!Form::close()!!}					            
		</div>
	</div>
@endsection