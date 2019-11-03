@extends ('layouts.home')
@section ('contenido')
{!!Form::open(array('url'=>'auth/reset/update','method'=>'POST','autocomplete'=>'off','id'=>'validateFormReset','data-toggle'=>'validator', 'role'=>'form' ))!!}
	{{Form::token()}}
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h5>{{Lang::get('leyendas.email.usuario')}}: <strong>{{$email}}</strong></h5> 
		</div>
		<div class="col-md-3"></div>
	</div>	
	<input type="hidden" name="email" value="{{$email}}">
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div class="form-group">
	        	<label for="password1" class="col-md-2 control-label"></label>
				<input id="password" type="password" data="Password" class="form-control required alfa" maxlength="15" name="password" placeholder="{{Lang::get('leyendas.teclea.password')}}" value="" tabindex="1">
	   		</div>
		</div>
		<div class="col-md-3"></div>
	</div>
	<div class="row">
		<div class="col-md-3"></div>
	    <div class="col-md-6">
			<div class="form-group">
	           <label for="password2" class="col-md-2 control-label"></label>
				<input id="passwordC" type="password"  data="Confirmación de Password" class="form-control required alfa" placeholder="{{Lang::get('leyendas.confirma.password')}}" maxlength="15" name="passwordC" value="" tabindex="2">
	    	</div>
	   	</div>
	   	<div class="col-md-3"></div>
	</div>
	<div class="row">
		<div class="col-md-3"></div>
	    <div class="col-md-6">
			<div class="form-group tdCenter">
				<button class="btn btn-primary  btn-sm" id="guardaReset" type="submit">{{Lang::get('leyendas.guardar')}}</button>        
			</div>
		</div>
		<div class="col-md-3"></div>
	</div><br/><br/><br/><br/><br/><br/>
{!!Form::close()!!}	
@endsection