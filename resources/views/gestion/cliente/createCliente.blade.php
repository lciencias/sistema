@extends ('layouts.home')
@section ('contenido')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!-- <h3>Nueva Cliente</h3> -->
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
			{!!Form::open(array('url'=>'gestion/cliente','method'=>'POST','autocomplete'=>'off','id'=>'validateFormCliente','data-toggle'=>'validator', 'role'=>'form','files' => true))!!}
            {{Form::token()}}
            @include('gestion.cliente.forms.cliente')  
			{!!Form::close()!!}		            
		</div>
	</div>
@endsection