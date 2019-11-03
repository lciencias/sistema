@extends ('layouts.home')
@section ('contenido')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!-- <h3>Nueva Talento</h3> -->
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
			{!!Form::open(array('url'=>'catalogos/talento','method'=>'POST','autocomplete'=>'off','id'=>'validateFormTalento','data-toggle'=>'validator', 'role'=>'form','files' => true))!!}
            {{Form::token()}}
            @include('catalogos.talento.forms.talento')  
			{!!Form::close()!!}		            
		</div>
	</div>
@endsection