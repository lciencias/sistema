<div class="box box-solid">
	<div class="box-header">
		<div class="pull-right box-tools">
			<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
        <i class="fa fa-minus"></i></button>
		</div>
    	<h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.datosGenerales')}}</h3>
	</div>
 	<div class="box-body">
		<div class="row">
    		<div  class="col-md-3">	
        		<div class="form-group">
        			<label for="idclienteCandidato" class="control-label">*{{Lang::get('leyendas.cliente')}}</label> 
        			<select name="idclienteCandidato" id="idclienteCandidato" data-column="1" class="form-control searchs combo">
    					<option value="">{{Lang::get('leyendas.seleccione')}}</option>
    					@foreach($clientes as $cliente)
    							@if ($cliente->idcliente == $candidato->idcliente)
    								<option selected value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial }}</option>
    							@else
    								<option value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial}}</option>
    							@endif
    					@endforeach						
    				</select>
        		</div>
        	</div>
    	
        	<div  class="col-md-3">	
        		<div class="form-group">
        			<label for="nombreCandidato" class="control-label">*{{Lang::get('leyendas.nombre')}}</label> 
        			<input type="text"  tabindex="1" id="nombreCandidato"  name="nombreCandidato" value="{{$candidato->nombre}}" class="form-control required letras" maxlength="50">
        		</div>
        	</div>
        	<div  class="col-md-3">
        		<div class="form-group">
        			<label for="paternoCandidato" class="control-label">*{{Lang::get('leyendas.paterno')}}</label>
        			<input type="text" tabindex="2" id="paternoCandidato" name="paternoCandidato" value="{{$candidato->paterno}}" class="form-control required letras" maxlength="50">
        		</div>
        	</div>		
        	<div  class="col-md-3">	
        		<div class="form-group">
        			<label for="maternoCandidato" class="control-label">{{Lang::get('leyendas.materno')}}</label>
        			<input type="text" tabindex="3"  id="maternoCandidato" name="maternoCandidato"  value="{{$candidato->materno}}" class="form-control required letras"  maxlength="50" >
        		</div>
        	</div>
        </div>
    
    
     <div class="row">
    	<div  class="col-md-3">	
    		<div class="form-group">
    			<label for="noEmpleadoeCandidato" class="control-label">*{{Lang::get('leyendas.candidato.noEmpleado')}}</label> 
    			<input type="text" tabindex="2" id="noEmpleadoeCandidato" name="noEmpleadoeCandidato" value="{{$candidato->no_empleado}}" class="form-control required alfa" maxlength="10">
    		</div>
    	</div>
    	<div  class="col-md-3">	
    		<div class="form-group">
    			<label for="generoCandidato" class="control-label">*{{Lang::get('leyendas.genero')}}</label> 
    			<select name="generoCandidato" id="generoCandidato" data-column="5" class="form-control searchs combo"  style="width:100%;">
					<option value="">Seleccione</option>
					@foreach($catGenero as $key => $value)
						@if ($key == $candidato->genero)
							<option selected value="{{$key}}">{{$value}}</option>
						@else
							<option value="{{$key}}">{{$value}}</option>
						@endif
					@endforeach						
				</select>
    		</div>
    	</div>
    	<div  class="col-md-3">
    		<div class="form-group">
    			<label for="fechaNacCandidato" class="control-label">{{Lang::get('leyendas.fechaNacimiento')}}</label>
    			<input type="text" tabindex="10" id="fechaNacCandidato" name="fechaNacCandidato" value="{{$candidato->fecha_nacimiento}}" maxlength="10" style="width:150px;" class="form-control required datetimepicker">
    		</div>
    	</div>		
    	<div  class="col-md-3">
    		<div class="form-group">
    			<label for="lugarNacimientoCandidato" class="control-label">{{Lang::get('leyendas.candidato.lugarNacimiento')}}</label>
    			@if(count($estados) > 0)   		
				<select name="lugarNacimientoCandidato" id="lugarNacimientoCandidato"  class="form-control estado" tabindex="26">
  					<option value="">{{Lang::get('leyendas.seleccione')}}</option>
						@foreach($estados as $estado)
							@if($estado->idestado == $idEstado)
								<option value="{{$estado->idestado}}" selected >{{$estado->nombre}}</option>
							@else
								<option value="{{$estado->idestado}}" >{{$estado->nombre}}</option>
							@endif
						@endforeach  				
				</select>
				@endif
    		</div>
    	</div>	
    	
    </div>
    
    
    <div class="row">
    	<div  class="col-md-4">	
    		<div class="form-group">
    			<label for="estadoCivilCandidato" class="control-label">{{Lang::get('leyendas.estadoCivil')}}</label> 
    			<select name="estadoCivilCandidato" id="estadoCivilCandidato" data-column="5" class="form-control searchs combo"  style="width:100%;">
					<option value="">Seleccione</option>
					@foreach($catEstadoCivil as $key => $value)
						@if ($key == $candidato->estado_civil)
							<option selected value="{{$key}}">{{$value}}</option>
						@else
							<option value="{{$key}}">{{$value}}</option>
						@endif
					@endforeach						
				</select>
        		</div>
        	</div>	
        	<div  class="col-md-4">
        		<div class="form-group">
        			<label for="curpCandidato" class="control-label">{{Lang::get('leyendas.curp')}}</label>
        			<input type="text" tabindex="2" id="curpCandidato" name="curpCandidato" value="{{$candidato->curp}}" class="form-control required upper" maxlength="18">
        		</div>
        	</div>		
        	<div  class="col-md-4">	
        		<div class="form-group">
        			<label for="rfcCandidato" class="control-label">{{Lang::get('leyendas.rfc')}}</label>
        			<input type="text" tabindex="3"  id="rfcCandidato" name="rfcCandidato"  value="{{$candidato->rfc}}" class="form-control letraNumero upper"  maxlength="13" >
        		</div>
        	</div>
        </div>
        
        
         <div class="row">	
        	<div class="col-md-4">	
        		<div class="form-group">
        			<label for="emailCandidato" class="control-label">*{{Lang::get('leyendas.correo')}}</label> 
        			<input type="text" tabindex="12" id="emailCandidato" name="emailCandidato" value="{{$usuario->email}}" 
        			class="form-control required correo" maxlength="100" data-bv-identical="true" autocomplete="off"
    					data-bv-identical-field="confirmaEmailResponsable"
    					data-bv-identical-message="Debe de confirmar el email">
        		</div>
        	</div>
        	@if($candidato->idcandidato == null)
        	<div class="col-md-4">	
        		<div class="form-group">
        			<label for="confirmaEmailCandidato" class="control-label">*{{Lang::get('leyendas.confirmaCorreo')}}</label> 
        			<input type="text" tabindex="13" id="confirmaEmailCandidato" name="confirmaEmailCandidato" 
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
    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.datosTrabajo')}}</h3>
	</div>
	 <div class="box-body">
        
         <div class="row">	
        	<div class="col-md-4">	
        		<div class="form-group">
        			<label for="puestoCandidato" class="control-label">{{Lang::get('leyendas.candidato.puestoResponsable')}}</label> 
        			<input type="text" tabindex="7" id="puestoCandidato" name="puestoCandidato" value="{{$candidato->puesto_responsable}}" class="form-control letras" maxlength="50">
        		</div>
        	</div>
        	<div class="col-md-4">	
        		<div class="form-group">
        			<label for="areaCandidato" class="control-label">{{Lang::get('leyendas.candidato.areaResponsable')}}</label> 
        			<input type="text" tabindex="8" id="areaCandidato" name="areaCandidato"  value="{{$candidato->area_responsable}}" class="form-control letras" maxlength="50">
        		</div>
        	</div>		
        
        	
        </div>
        
         <div class="row">	
         	<div class="col-md-4">	
        		<div class="form-group">
        			<label for="celCandidato" class="control-label">{{Lang::get('leyendas.candidato.celResponsable')}}</label> 
        			<input type="text" tabindex="9" id="celCandidato" name="celCandidato" value="{{$candidato->tel_cel_responsable}}" class="form-control numeros" maxlength="10">
        		</div>
        	</div>
         
        	<div class="col-md-4">	
        		<div class="form-group">
        			<label for="telOficinaCandidato" class="control-label">{{Lang::get('leyendas.candidato.telOficinaResponsable')}}</label> 
        			<input type="text" tabindex="10" id="telOficinaCandidato" name="telOficinaCandidato" value="{{$candidato->tel_oficina_responsable}}" class="form-control numeros" maxlength="10">
        		</div>
        	</div>
        	<div class="col-md-4">	
        		<div class="form-group">
        			<label for="extOficinaCandidato" class="control-label">{{Lang::get('leyendas.candidato.extOficinaResponsable')}}</label> 
        			<input type="text" tabindex="11" id="extOficinaCandidato" name="extOficinaCandidato"  value="{{$candidato->ext_tel_responsable}}" class="form-control numeros" maxlength="4">
        		</div>
        	</div>		
        
        </div>
        
        
   </div>     	
</div>  

