<div class="modal fade modal-slide-in-right" aria-hidden="true" data-backdrop="static"
role="dialog" tabindex="-1" id="modalDetalleEmpresa">
<div class="modal-dialog" style="width: 90%;" id="validateFormEmpresa">
		<div class="modal-content">
		<div class="modal-header">
                <h4 class="modal-title">{{Lang::get('leyendas.seguridad.empresa.create')}}</h4>
			</div>
		<div class="modal-body">
	<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
	<input name="idelemento" id="idelemento" type="hidden" />
	<input name="idempresa" id="idempresa" type="hidden" value="{{$empresaIdUser}}" />
	<input name="empresa" id="empresa" type="hidden" />
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">	
		<div class="form-group">
			<label for="nombre" class="col-lg-6 control-label">*{{Lang::get('leyendas.empresa.nombre')}}</label> 
			<input type="text"  tabindex="1" id="nombreEmpresa" data="Nombre de la Empresa" name="nombreEmpresa" class="form-control required alfa">
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="form-group">
			<label for="razon" class="col-lg-3 control-label">*{{Lang::get('leyendas.empresa.razon')}}</label>
			<input type="text" tabindex="2" id="razonSocialEmpresa" name="razonSocialEmpresa" value="{{$empresa->razon_social}}" class="form-control required alfa" >
		</div>
	</div>		
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">	
		<div class="form-group">
			<label for="direccion" class="col-lg-2 control-label">{{Lang::get('leyendas.empresa.direccion')}}</label> 
			<input type="text" tabindex="3" id="direccionEmpresa" name="direccionEmpresa" value="{{$empresa->direccion}}" class="form-control alfa required">
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">	
		<div class="form-group">
			<label for="nombre_representante" class="col-lg-5 control-label">{{Lang::get('leyendas.empresa.nombreRepresentante')}}</label> 
			<input type="text" tabindex="4" id="nombreRepresentanteEmpresa" name="nombreRepresentanteEmpresa" value="{{$empresa->nombre_representante}}" class="form-control required letras">
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">	
		<div class="form-group">
			<label for="paterno_representante" class="col-lg-5 control-label">{{Lang::get('leyendas.empresa.paternoRepresentante')}}</label> 
			<input type="text" tabindex="5" id="paternoRepresentanteEmpresa" name="paternoRepresentanteEmpresa"  value="{{$empresa->paterno_representante}}" class="form-control required letras">
		</div>
	</div>		

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">	
		<div class="form-group">
			<label for="materno_representante" class="col-lg-6 control-label">{{Lang::get('leyendas.empresa.maternoRepresentante')}}</label> 
			<input type="text" tabindex="6" id="maternoRepresentanteEmpresa" name="maternoRepresentanteEmpresa" value="{{$empresa->materno_representante}}" class="form-control letras required">
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">	
		<div class="form-group">
			<label for="email_representante" class="col-lg-6 control-label">{{Lang::get('leyendas.empresa.correoRepresentante')}}</label> 
			<input type="text" tabindex="7" id="emailRepresentanteEmpresa" name="emailRepresentanteEmpresa" value="{{$empresa->email_representante}}" class="form-control required correo">
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">	
		<div class="form-group">
			<label for="rfc" class="col-lg-6 control-label">{{Lang::get('leyendas.empresa.rfc')}}</label>
			<input type="text" tabindex="8"  id="rfcEmpresa" name="rfcEmpresa"  value="{{$empresa->rfc}}" class="form-control letras upper"  maxlength="14" >
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">	
		<div class="form-group">
			<label for="logotipo" class="col-lg-6 control-label">{{Lang::get('leyendas.empresa.logotipo')}}</label>
			<input type="file"  name="image" id="image"  tabindex="9"  class="btn btn-sm" accept="image/*">						
			@if ($imagen != '')							
				<br>{{Lang::get('leyendas.empresa.archivo')}} <b>{{$imagen}}</b>
			@endif
		</div>
	</div>		

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group tdRight">
			@if (in_array('Editar',$sessionPermisos) || in_array('Crear',$sessionPermisos))
				<button class="btn btn-success btn-sm" id="guardaEmpresa" type="submit" tabindex="10" >
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
			<button type="button" class="btn btn-danger btn-sm "  data-dismiss="modal"  tabindex="11" >
			<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
		</div>
	</div>
</div>

<div class="modal-footer">
			</div>
</div>
</div>
</div>