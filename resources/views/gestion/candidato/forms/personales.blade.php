


<div class="box box-solid">
	<div class="box-header">
    	<div class="pull-right box-tools">
        	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
        <i class="fa fa-minus"></i></button>
	</div>
    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.direccion')}}</h3>
	</div>
	
	 <div class="box-body">
		<div class="row">
			
			<div class="col-md-3">
				<div class="form-group">
					<label for="calleCandidato" class="control-label">{{Lang::get('leyendas.direccion.calle')}}</label>
					<input type="text" tabindex="21" id="calleCandidato" value="{{$direccion->calle}}"
						name="calleCandidato" maxlength="100"
						class="form-control alfa">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="noExtCandidato" class="control-label">{{Lang::get('leyendas.direccion.noExt')}}</label>
					<input type="text" tabindex="22" id="noExtCandidato"  value="{{$direccion->no_exterior}}"
						name="noExtCandidato" maxlength="50"
						class="form-control alfa">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="noIntCandidato" class="control-label">{{Lang::get('leyendas.direccion.noInt')}}</label>
					<input type="text" tabindex="23" id="noIntCandidato"  value="{{$direccion->no_interior}}"
						name="noIntCandidato" maxlength="50"
						class="form-control alfa">
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<label for="edificioCandidato" class="control-label">{{Lang::get('leyendas.direccion.edificio')}}</label>
					<input type="text" tabindex="24" id="edificioCandidato" name="edificioCandidato" value="{{$direccion->edificio}}"
						maxlength="14" class="form-control alfa">
				</div>
			</div>

		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label for="coloniaCandidato" class="control-label">{{Lang::get('leyendas.direccion.colonia')}}</label>
					<input type="text" tabindex="25" id="coloniaCandidato" value="{{$direccion->colonia}}"
						name="coloniaCandidato" maxlength="70"
						class="form-control alfa">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="estadoCandidato" class="control-label">{{Lang::get('leyendas.direccion.estado')}}</label>
					@if(count($estados) > 0)   		
    				<select name="estadoCandidato" id="estadoCandidato"  class="form-control estado" tabindex="26">
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
			<div class="col-md-3">
				<div class="form-group">
					<label for="delMunCandidato" class="control-label">{{Lang::get('leyendas.direccion.municipio')}}</label>
					<select name="delMunCandidato" id="delMunCandidato"  class="form-control" tabindex="27">
						<option value="">{{Lang::get('leyendas.seleccione')}}</option>
						@if($municipios != null)
    						@foreach($municipios as $mun)
    							@if($mun->idmunicipio == $direccion->idmunicipio)
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
					<label for="cpCandidato" class="control-label">{{Lang::get('leyendas.direccion.cp')}}</label>
					<input type="text" tabindex="28" id="cpCandidato" value="{{$direccion->cp}}"
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
    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.telefonos')}}</h3>
	</div>
	
	 <div class="box-body">
		
 	<div class="row">
    	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <div class="form-group">
              	<div class="rows">
                    <button 
                       type="button" 
                       class="btn btn-primary editable btn-sm glyphicon glyphicon-plus" 
                       data-toggle="modal" 
                       data-target="#modalEjercicio" onclick="return abreTelefonosCandidato();">Agregar</button>
                 </div>
					<table class="table table-bordered" id="tablaEjercicios">
						<thead>
							<tr>
								<th style="visibility: hidden">id</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.candidato.tipoTelefono')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.candidato.telefono')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.candidato.notas')}}</th>
								<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
								<th style="visibility: hidden" width="0">Opciones</th>
							</tr>				
						</thead>
						
						<tbody>	
							@if (count($telefonos) > 0)
						    	@foreach ($telefonos as $tel)
									<tr id="fila-{{$tel->idtelefono_candidato}}">
										<td style="visibility: hidden">{{ $tel->idtelefono_candidato}}</td>
										<td>{{ $tel->tipo}}</td>
										<td>{{ $tel->telefono}}</td>
										<td>{{ $tel->notas}}</td>
										<td class="tdCenter">
											<a href="#" class="btn btn-default  btn-xs editaTelefono" id="{{$tel->idtelefono_candidato}}">
								 				<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil" id="{{$tel->idtelefono_candidato}}"></span>
								 			</a>
								 		</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>	
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
    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.familiares')}}</h3>
	</div>
	
	 <div class="box-body">
		
 	<div class="row">
    	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <div class="form-group">
              	<div class="rows">
                    <button 
                       type="button" 
                       class="btn btn-primary editable btn-sm glyphicon glyphicon-plus" 
                       data-toggle="modal" 
                       data-target="#modalEjercicio" onclick="return abreFamiliaresCandidato();">Agregar</button>
                 </div>
					<table class="table table-bordered" id="tablaEjercicios">
						<thead>
							<tr>
								<th style="visibility: hidden">id</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.candidato.parentesco')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.nombre')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.ocupacion')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.genero')}}</th>
								<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
								<th style="visibility: hidden" width="0">Opciones</th>
							</tr>				
						</thead>
						
						<tbody>	
							@if (count($telefonos) > 0)
						    	@foreach ($telefonos as $tel)
									<tr id="fila-{{$tel->idtelefono_candidato}}">
										<td style="visibility: hidden">{{ $tel->idtelefono_candidato}}</td>
										<td>{{ $tel->tipo}}</td>
										<td>{{ $tel->telefono}}</td>
										<td>{{ $tel->notas}}</td>
										<td class="tdCenter">
											<a href="#" class="btn btn-default  btn-xs editaTelefono" id="{{$tel->idtelefono_candidato}}">
								 				<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil" id="{{$tel->idtelefono_candidato}}"></span>
								 			</a>
								 		</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>	
			</div>
	    </div>
	</div>
		
	</div>
</div>
