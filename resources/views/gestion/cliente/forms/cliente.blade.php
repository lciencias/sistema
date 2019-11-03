<script src="{{asset('js/clientes.js')}}"></script>
<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
<input name="idelemento" id="idelemento" type="hidden" value="{{$cliente->idcliente}}" />
<input name="idcliente" id="idcliente" type="hidden" value="{{$clienteIdUser}}" />

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
    	<li class="active"><a href="#tab_1" data-toggle="tab">{{Lang::get('leyendas.cliente.datosGenerales')}}</a></li>
    	@if($cliente->idcliente != null)
    	<li><a href="#tab_2" data-toggle="tab">{{Lang::get('leyendas.cliente.licencias')}}</a></li>
    	<li><a href="#tab_2" data-toggle="tab">{{Lang::get('leyendas.cliente.empleados')}}</a></li>
    	@endif
	</ul>
	 <div class="tab-content">
    	<div class="active" id="tab_1">
        	<div class="box box-solid">
            	<div class="box-header">
                	<div class="pull-right box-tools">
                    	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                        <i class="fa fa-minus"></i></button>
            		</div>
                    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.cliente.datosGenerales')}}</h3>
            	</div>
            	 <div class="box-body">
                	<div class="row">
                    	<div  class="col-md-4">	
                    		<div class="form-group">
                    			<label for="nombreComercialCliente" class="control-label">*{{Lang::get('leyendas.cliente.nombreComercial')}}</label> 
                    			<input type="text"  tabindex="1" id="nombreComercialCliente"  name="nombreComercialCliente" value="{{$cliente->nombre_comercial}}" class="form-control required alfa" maxlength="100">
                    		</div>
                    	</div>
                    	<div  class="col-md-4">
                    		<div class="form-group">
                    			<label for="razonSocialCliente" class="control-label">*{{Lang::get('leyendas.cliente.razonSocial')}}</label>
                    			<input type="text" tabindex="2" id="razonSocialCliente" name="razonSocialCliente" value="{{$cliente->razon_social}}" class="form-control required alfa" maxlength="100">
                    		</div>
                    	</div>		
                    	<div  class="col-md-4">	
                    		<div class="form-group">
                    			<label for="rfcCliente" class="control-label">{{Lang::get('leyendas.cliente.rfc')}}</label>
                    			<input type="text" tabindex="3"  id="rfcCliente" name="rfcCliente"  value="{{$cliente->rfc}}" class="form-control letraNumero upper"  maxlength="13" >
                    		</div>
                    	</div>
                    </div>
                    
                    
               </div>     	
            </div>    
            
            
            <div class="box box-solid">
            	<div class="box-header">
                	<div class="pull-right box-tools">
                    	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                        <i class="fa fa-minus"></i></button>
            		</div>
                    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.cliente.datosAdministrador')}}</h3>
            	</div>
            	 <div class="box-body">
                   <div class="row">	
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="nombreResponsable" class="control-label">*{{Lang::get('leyendas.cliente.nombreResponsable')}}</label> 
                    			<input type="text" tabindex="4" id="nombreResponsable" name="nombreResponsable" value="{{$usuario->name}}" class="form-control required letras" maxlength="50">
                    		</div>
                    	</div>
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="paternoResponsable" class="control-label">*{{Lang::get('leyendas.cliente.paternoResponsable')}}</label> 
                    			<input type="text" tabindex="5" id="paternoResponsable" name="paternoResponsable"  value="{{$usuario->paterno}}" class="form-control required letras" maxlength="50">
                    		</div>
                    	</div>		
                    
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="maternoResponsable" class="control-label">*{{Lang::get('leyendas.cliente.maternoResponsable')}}</label> 
                    			<input type="text" tabindex="6" id="maternoResponsable" name="maternoResponsable" value="{{$usuario->materno}}" class="form-control letras required" maxlength="50">
                    		</div>
                    	</div>
                    </div>
                    
                     <div class="row">	
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="puestoResponsable" class="control-label">{{Lang::get('leyendas.cliente.puestoResponsable')}}</label> 
                    			<input type="text" tabindex="7" id="puestoResponsable" name="puestoResponsable" value="{{$cliente->puesto_responsable}}" class="form-control letras" maxlength="50">
                    		</div>
                    	</div>
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="areaResponsable" class="control-label">{{Lang::get('leyendas.cliente.areaResponsable')}}</label> 
                    			<input type="text" tabindex="8" id="areaResponsable" name="areaResponsable"  value="{{$cliente->area_responsable}}" class="form-control letras" maxlength="50">
                    		</div>
                    	</div>		
                    
                    	
                    </div>
                    
                     <div class="row">	
                     	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="celResponsable" class="control-label">{{Lang::get('leyendas.cliente.celResponsable')}}</label> 
                    			<input type="text" tabindex="9" id="celResponsable" name="celResponsable" value="{{$cliente->tel_cel_responsable}}" class="form-control numeros" maxlength="10">
                    		</div>
                    	</div>
                     
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="telOficinaResponsable" class="control-label">{{Lang::get('leyendas.cliente.telOficinaResponsable')}}</label> 
                    			<input type="text" tabindex="10" id="telOficinaResponsable" name="telOficinaResponsable" value="{{$cliente->tel_oficina_responsable}}" class="form-control numeros" maxlength="10">
                    		</div>
                    	</div>
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="extOficinaResponsable" class="control-label">{{Lang::get('leyendas.cliente.extOficinaResponsable')}}</label> 
                    			<input type="text" tabindex="11" id="extOficinaResponsable" name="extOficinaResponsable"  value="{{$cliente->ext_tel_responsable}}" class="form-control numeros" maxlength="4">
                    		</div>
                    	</div>		
                    
                    </div>
                    
                     <div class="row">	
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="emailResponsable" class="control-label">*{{Lang::get('leyendas.cliente.correoResponsable')}}</label> 
                    			<input type="text" tabindex="12" id="emailResponsable" name="emailResponsable" value="{{$usuario->email}}" 
                    			class="form-control required correo" maxlength="100" data-bv-identical="true" autocomplete="off"
                					data-bv-identical-field="confirmaEmailResponsable"
                					data-bv-identical-message="Debe de confirmar el email">
                    		</div>
                    	</div>
                    	@if($cliente->idcliente == null)
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="confirmaEmailResponsable" class="control-label">*{{Lang::get('leyendas.cliente.confirmaCorreoResponsable')}}</label> 
                    			<input type="text" tabindex="13" id="confirmaEmailResponsable" name="confirmaEmailResponsable" 
                    			class="form-control required correo" maxlength="100"
                    			data-bv-identical="true" autocomplete="off"
                					data-bv-identical-field="emailResponsable"
                					data-bv-identical-message="Debe de confirmar el email">
                    		</div>
                    	</div>
                    	@endif
                    </div>
               </div>     	
            </div>  
            
            
             <div class="box box-solid">
            	<div class="box-header">
                	<div class="pull-right box-tools">
                    	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                        <i class="fa fa-minus"></i></button>
            		</div>
                    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.cliente.datosContacto')}}</h3>
            	</div>
            	 <div class="box-body">
                   <div class="row">	
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="nombreContactoAdmon" class="control-label">{{Lang::get('leyendas.cliente.nombreResponsable')}}</label> 
                    			<input type="text" tabindex="14" id="nombreContactoAdmon" name="nombreContactoAdmon" value="{{$cliente->nombre_admon}}" class="form-control required letras" maxlength="50">
                    		</div>
                    	</div>
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="paternoContactoAdmon" class="control-label">{{Lang::get('leyendas.cliente.paternoResponsable')}}</label> 
                    			<input type="text" tabindex="15" id="paternoContactoAdmon" name="paternoContactoAdmon"  value="{{$cliente->paterno_admon}}" class="form-control required letras" maxlength="50">
                    		</div>
                    	</div>		
                    
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="maternoContactoAdmon" class="control-label">{{Lang::get('leyendas.cliente.maternoResponsable')}}</label> 
                    			<input type="text" tabindex="16" id="maternoContactoAdmon" name="maternoContactoAdmon" value="{{$cliente->materno_admon}}" class="form-control letras required" maxlength="50">
                    		</div>
                    	</div>
                    </div>
                    
                    
                     <div class="row">	
                     	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="celContactoAdmon" class="control-label">{{Lang::get('leyendas.cliente.celResponsable')}}</label> 
                    			<input type="text" tabindex="17" id="celContactoAdmon" name="celContactoAdmon" value="{{$cliente->tel_cel_admon}}" class="form-control numeros" maxlength="10">
                    		</div>
                    	</div>
                     
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="telOficinaContactoAdmon" class="control-label">{{Lang::get('leyendas.cliente.telOficinaResponsable')}}</label> 
                    			<input type="text" tabindex="18" id="telOficinaContactoAdmon" name="telOficinaContactoAdmon" value="{{$cliente->tel_oficina_admon}}" class="form-control numeros" maxlength="10">
                    		</div>
                    	</div>
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="extOficinaContactoAdmon" class="control-label">{{Lang::get('leyendas.cliente.extOficinaResponsable')}}</label> 
                    			<input type="text" tabindex="19" id="extOficinaContactoAdmon" name="extOficinaContactoAdmon"  value="{{$cliente->ext_tel_admon}}" class="form-control numeros" maxlength="4">
                    		</div>
                    	</div>		
                    
                    </div>
                    
                     <div class="row">	
                    	<div class="col-md-4">	
                    		<div class="form-group">
                    			<label for="emailContactoAdmon" class="control-label">{{Lang::get('leyendas.cliente.correoResponsable')}}</label> 
                    			<input type="text" tabindex="20" id="emailContactoAdmon" name="emailContactoAdmon" value="{{$cliente->email_admon}}" class="form-control correo" maxlength="100">
                    		</div>
                    	</div>
                    </div>
               </div>     	
            </div>  
            
            <div class="box box-solid">
            	<div class="box-header">
                	<div class="pull-right box-tools">
                    	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                        <i class="fa fa-minus"></i></button>
            		</div>
                    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.cliente.direccionFisical')}}</h3>
            	</div>
            	
            	 <div class="box-body">
            		<div class="row">
            			
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="calleFiscal" class="control-label">{{Lang::get('leyendas.direccion.calle')}}</label>
            					<input type="text" tabindex="21" id="calleFiscal" value="{{$direccionFiscal->calle}}"
            						name="calleFiscal" maxlength="100"
            						class="form-control alfa">
            				</div>
            			</div>
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="noExtFiscal" class="control-label">{{Lang::get('leyendas.direccion.noExt')}}</label>
            					<input type="text" tabindex="22" id="noExtFiscal"  value="{{$direccionFiscal->no_exterior}}"
            						name="noExtFiscal" maxlength="50"
            						class="form-control alfa">
            				</div>
            			</div>
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="noIntFiscal" class="control-label">{{Lang::get('leyendas.direccion.noInt')}}</label>
            					<input type="text" tabindex="23" id="noIntFiscal"  value="{{$direccionFiscal->no_interior}}"
            						name="noIntFiscal" maxlength="50"
            						class="form-control alfa">
            				</div>
            			</div>
            			
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="edificioFiscal" class="control-label">{{Lang::get('leyendas.direccion.edificio')}}</label>
            					<input type="text" tabindex="24" id="edificioFiscal" name="edificioFiscal" value="{{$direccionFiscal->edificio}}"
            						maxlength="14" class="form-control alfa">
            				</div>
            			</div>
            
            		</div>
            		<div class="row">
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="coloniaFiscal" class="control-label">{{Lang::get('leyendas.direccion.colonia')}}</label>
            					<input type="text" tabindex="25" id="coloniaFiscal" value="{{$direccionFiscal->colonia}}"
            						name="coloniaFiscal" maxlength="70"
            						class="form-control alfa">
            				</div>
            			</div>
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="estadoFiscal" class="control-label">{{Lang::get('leyendas.direccion.estado')}}</label>
            					@if(count($estados) > 0)   		
                				<select name="estadoFiscal" id="estadoFiscal"  class="form-control estado" tabindex="26">
                  					<option value="">{{Lang::get('leyendas.seleccione')}}</option>
                						@foreach($estados as $estado)
                							@if($estado->idestado == $idEstadoFiscal)
            									<option value="{{$estado->idestado}}" selected >{{$estado->nombre}}</option>
            								@else
                								<option value="{{$estado->idestado}}" >{{$estado->nombre}}</option>
                							@endif
                						@endforeach  				
                				</select>
                				@endif
            				</div>
            			</div>
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="delMunFiscal" class="control-label">{{Lang::get('leyendas.direccion.municipio')}}</label>
            					<select name="delMunFiscal" id="delMunFiscal"  class="form-control" tabindex="27">
            						<option value="">{{Lang::get('leyendas.seleccione')}}</option>
            						@if($municipiosFiscal != null)
                						@foreach($municipiosFiscal as $mun)
                							@if($mun->idmunicipio == $direccionFiscal->idmunicipio)
                								<option value="{{$mun->idmunicipio}}" selected >{{$mun->nombre}}</option>
                							@else
                								<option value="{{$mun->idmunicipio}}">{{$mun->nombre}}</option>
                							@endif
                						@endforeach 
            						@endif
                				</select>
            				</div>
            			</div>
            			
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="cpFiscal" class="control-label">{{Lang::get('leyendas.direccion.cp')}}</label>
            					<input type="text" tabindex="28" id="cpFiscal" value="{{$direccionFiscal->cp}}"
            						name="cpFiscal" maxlength="5"
            						class="form-control numeros">
            				</div>
            
            			</div>
            		</div>
            	</div>
            </div>
            
            <div class="box box-solid">
            	<div class="box-header">
                	<div class="pull-right box-tools">
                    	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                        <i class="fa fa-minus"></i></button>
            		</div>
                    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.cliente.direccionComercial')}}</h3>
            	</div>
            	<div class="box-body">
            		<div class="row">
            			
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="calleComercial" class="control-label">{{Lang::get('leyendas.direccion.calle')}}</label>
            					<input type="text" tabindex="29" id="calleComercial" value="{{$direccionComercial->calle}}"
            						name="calleComercial" maxlength="100"
            						class="form-control alfa">
            				</div>
            			</div>
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="noExtComercial" class="control-label">{{Lang::get('leyendas.direccion.noExt')}}</label>
            					<input type="text" tabindex="30" id="noExtComercial" value="{{$direccionComercial->no_exterior}}"
            						name="noExtComercial" maxlength="50"
            						class="form-control alfa">
            				</div>
            			</div>
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="noIntComercial" class="control-label">{{Lang::get('leyendas.direccion.noInt')}}</label>
            					<input type="text" tabindex="31" id="noIntComercial" value="{{$direccionComercial->no_interior}}"
            						name="noIntComercial" maxlength="50"
            						class="form-control alfa">
            				</div>
            			</div>
            			
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="edificioComercial" class="control-label">{{Lang::get('leyendas.direccion.edificio')}}</label>
            					<input type="text" tabindex="32" id="edificioComercial" name="edificioComercial" value="{{$direccionComercial->edificio}}"
            						maxlength="20" class="form-control alfa">
            				</div>
            			</div>
            
            		</div>
            		<div class="row">
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="coloniaComercial" class="control-label">{{Lang::get('leyendas.direccion.colonia')}}</label>
            					<input type="text" tabindex="33" id="coloniaComercial" value="{{$direccionComercial->colonia}}"
            						name="coloniaComercial" maxlength="70"
            						class="form-control alfa">
            				</div>
            			</div>
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="estadoComercial" class="control-label">{{Lang::get('leyendas.direccion.estado')}}</label>
            					@if(count($estados) > 0)   		
                				<select name="estadoComercial" id="estadoComercial"  class="form-control estado" tabindex="34">
                  					<option value="">{{Lang::get('leyendas.seleccione')}}</option>
                  						@foreach($estados as $estado)
                    						@if($estado->idestado == $idEstadoComercial)
            									<option value="{{$estado->idestado}}" selected >{{$estado->nombre}}</option>
            								@else
                								<option value="{{$estado->idestado}}" >{{$estado->nombre}}</option>
                							@endif	
                						@endforeach		
                				</select>
                				@endif
            				</div>
            			</div>
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="delMunComercial" class="control-label">{{Lang::get('leyendas.direccion.municipio')}}</label>
            					<select name="delMunComercial" id="delMunComercial"  class="form-control" tabindex="35"> 
            						<option value="">{{Lang::get('leyendas.seleccione')}}</option>
            						@if($municipiosComercial != null)
                						@foreach($municipiosComercial as $mun)
                							@if($mun->idmunicipio == $direccionComercial->idmunicipio)
                								<option value="{{$mun->idmunicipio}}" selected >{{$mun->nombre}}</option>
                							@else
                								<option value="{{$mun->idmunicipio}}">{{$mun->nombre}}</option>
                							@endif
                						@endforeach
                					@endif 
                				</select>
            				</div>
            			</div>
            			
            			<div class="col-md-3">
            				<div class="form-group">
            					<label for="cpComercial" class="control-label">{{Lang::get('leyendas.direccion.cp')}}</label>
            					<input type="text" tabindex="36" id="cpComercial" value="{{$direccionComercial->cp}}"
            						name="cpComercial" maxlength="5"
            						class="form-control required numeros">
            				</div>
            
            			</div>
            		</div>
            	</div>
            </div>
            
        </div>
	</div>	
</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group tdRight">
		@if ($cliente->nombre != '')
			@if (in_array('Editar',$sessionPermisos))
				<button class="btn btn-success btn-sm" id="guardaCliente" type="submit" tabindex="37" >
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@else
			@if (in_array('Crear',$sessionPermisos))
				<button class="btn btn-success btn-sm" id="guardaCliente" type="submit" tabindex="37" >
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@endif
			<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/gestion/cliente')}}" tabindex="38" >
			<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
		</div>
	</div>
