@extends ('layouts.home')
@section ('contenido')
	<script src="{{asset('js/ajax.js')}}"></script>
	<script src="{{asset('js/usuarios.js')}}"></script>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!-- <h3>Editar Usuario: {{ $usuario->name}}</h3> -->
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
	{!!Form::model($usuario,['method'=>'post','id'=>'validateFormUser','data-toggle'=>'validator', 'role'=>'form',
	'url'=>['seguridad.usuario.update',$usuario->id]])!!}
	{{Form::token()}}
	<input type="hidden" id="id"  name="id"  value="{{$usuario->id}}">
	
	 @if($isAdmin)
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="form-group">
				<label class="col-lg-3 control-label" for="empresa">*{{Lang::get('leyendas.empresa')}}</label>
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
					<label  class="col-lg-2 control-label" for="perfil">*Perfil</label>					
					@if(count($perfiles) > 0)   		
					<select name="idperfil" id="idperfil"  class="form-control required perfilnAjax" tabindex="2">
  						<option value="">Seleccione</option>
						@foreach($perfiles as $per)
							@if($per->idperfil == $usuario->idperfil)
								<option value="{{$per->idperfil}}" selected >{{$per->nombre}}</option>
							@else
								<option value="{{$per->idperfil}}">{{$per->nombre}}</option>
							@endif
						@endforeach  				
					</select>
					@endif
				</div>
			</div>
		</div>						
	
	
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		       	<div class="form-group">
		           	<label for="name" class="col-md-1 control-label">*Nombre</label>
					<input id="name" type="text" class="form-control required letras" maxlength="70" name="name" value="{{$usuario->name}}" tabindex="3">
		       </div>
		    </div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="form-group">
		    		<label for="email" class="col-md-3 control-label">*E-Mail</label>
					<input id="email" type="email" class="form-control required correo" readonly="readonly"  maxlength="100" name="email" value="{{ $usuario->email }}" tabindex="4">
				</div>
			</div>
		</div>
		<div class="row" id="modulos">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
		        	<label for="modulos" class="col-md-4 control-label">*M&oacute;dulos</label>        
		        	<div id="tableModulos"></div>        	
				</div>		 
		    </div>
		</div>
		<div class="form-group tdRight" >
		@if (in_array('Editar',$sessionPermisos))
			<button class="btn btn-success btn-sm"  id="guardaUsuario" type="submit">
			<span class="fa fa-floppy-o"></span>&nbsp;Guardar</button>
		@endif
		    <button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/seguridad/usuario')}}" >
		    <span class="glyphicon glyphicon-ban-circle"></span>&nbsp;Cancelar</button>	  
		</div>
		@include('seguridad.usuario.eliminaModulo')
	{!!Form::close()!!}
@endsection