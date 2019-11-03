@extends ('layouts.home')
@section ('contenido')
<script src="{{asset('js/usuarios.js')}}"></script>
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
	</div>
</div>
		
{!!Form::open(array('url'=>'seguridad/usuario','method'=>'POST','autocomplete'=>'off','id'=>'validateFormUser','data-toggle'=>'validator', 'role'=>'form' ))!!}
    {{Form::token()}}
    <input type="hidden" id="id"  name="id"  value="0">
    
    @if($isAdmin)
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
				<label class="col-lg-3 control-label" for="empresa">{{Lang::get('leyendas.empresa')}}</label>
				<select name="idEmpresaBusca"  id="idEmpresaBusca" tabindex="1"  class="form-control"  {{$disabled}}> 
					<option value="" selected>Seleccione</option>
					@foreach($empresas as $emp)
						@if($emp->idempresa == $usuario->idempresa)
							<option value="{{$emp->idempresa}}" selected>{{$emp->nombre}}</option>
						@else
				    		<option value="{{$emp->idempresa}}">{{$emp->nombre}}</option>
				    	@endif
					@endforeach
					</select>
			</div>
		</div>
	@else
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
			<label class="col-lg-3 control-label" for="idEmpresaBusca"></label>
				<input type="hidden" name="idEmpresaBusca"  id="idEmpresaBusca" value="{{$idEmpresaUser}}">
			</div>
		</div>
	@endif
    
    
   
	 <div class="row">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="form-group">
				<label  class="col-lg-2 control-label" for="perfil">*{{Lang::get('leyendas.perfil')}}</label>
				@if(count($perfiles) > 0)   		
				<select name="idperfil" id="idperfil"  class="form-control perfiln" tabindex="2">
  					<option value="">{{Lang::get('leyendas.seleccione')}}</option>
						@foreach($perfiles as $perfil)
							<option value="{{$perfil->idperfil}}" >{{$perfil->nombre}}</option>
						@endforeach  				
				</select>
				@endif
			</div>
		</div>
		
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="form-group">
				<label  class="col-lg-2 control-label" for="idliga">*{{Lang::get('leyendas.liga')}}</label>
				@if(count($ligas) > 0)   		
				<select name="idliga" id="idliga"  class="form-control" tabindex="3">
  					<option value="">{{Lang::get('leyendas.todas')}}</option>
						@foreach($ligas as $liga)
							<option value="{{$liga->idliga}}" >{{$liga->nombre}}</option>
						@endforeach  				
				</select>
				@endif
			</div>
		</div>
		
	</div>
	
	
	<div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">*{{Lang::get('leyendas.email')}}</label>
				<input id="email" type="email" class="form-control required correo" maxlength="100" name="email" value="" tabindex="4" data-bv-identical="true"  data-bv-identical-field="validaEmail"  data-bv-identical-message="Debe ser el mismo email" >
    	    </div>
    	</div>
    	
    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="validaEmail" class="col-md-4 control-label">*{{Lang::get('leyendas.email_valida')}}</label>
				<input id="validaEmail" type="email" class="form-control required correo" maxlength="100" name="validaEmail" data-bv-identical="true"  data-bv-identical-field="email"  data-bv-identical-message="Debe ser el mismo email" value="" tabindex="5" >
    	    </div>
    	</div>
    </div>
    
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="form-group">
                <label for="name" class="col-md-1 control-label">*{{Lang::get('leyendas.nombre')}}</label>
				<input id="name" type="text" class="form-control required letras" maxlength="70" name="name" value="" tabindex="6">
   		     </div>
   		</div>
    </div>
    
    
	<div class="row" id="modulos">
	 	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
        		<label for="modulos" class="col-md-2 control-label">*{{Lang::get('leyendas.modulos')}}</label>        
        		<div id="tableModulos"></div>        	
			</div>		 
    	</div>
    </div>
    <br>
	<div class="form-group tdRight">
		@if (in_array('Crear',$sessionPermisos))
			<button class="btn btn-success  btn-sm" id="guardaUsuario" type="submit" tabindex="7">
			<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
		@endif		
		<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/seguridad/usuario')}}" tabindex="8">
			<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}
			</button>		        
		</div>	
	@include('seguridad.usuario.eliminaModulo')
	{!!Form::close()!!}	
@endsection