<script src="{{asset('js/competencias.js')}}"></script>
<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
<input name="idelemento" id="idelemento" type="hidden" value="{{$competencia->idcompetencia}}" />
<input name="idcompetencia" id="idcompetencia" type="hidden" value="{{$competenciaIdUser}}" />
<input type="hidden" name="comportamientos" id="comportamientos" >	
	
	
	<div class="row">
    	<div  class="col-md-6">	
    		<div class="form-group">
    			<label for="email" class="col-md-2 control-label">*{{Lang::get('leyendas.competencia.nombre')}}</label>
				<input id="nombreCompetencia" name="nombreCompetencia"  class="form-control required" maxlength="50" value="{{$competencia->nombre}}" tabindex="1" >
    		</div>
    	</div>
    	<div  class="col-md-6">
    		<div class="form-group">
    			<label for="tipoCompetencia" class="col-md-4 control-label">*{{Lang::get('leyendas.competencia.tipo')}}</label>
				<select name="tipoCompetencia" id="tipoCompetencia"  tabindex="3" class="form-control combo">
					<option value="">{{Lang::get('leyendas.seleccionar')}}</option>
					@foreach($catTipoCompetencias as $catTipoCompetencia)
						@if($catTipoCompetencia->idtipo_competencia == $competencia->idtipo_competencia) 
							<option value="{{$catTipoCompetencia->idtipo_competencia}}" selected>{{ $catTipoCompetencia->nombre}}</option>
						@else
							<option value="{{$catTipoCompetencia->idtipo_competencia}}">{{ $catTipoCompetencia->nombre}}</option>
						@endif	
					@endforeach
				</select>
    		</div>
    	</div>		
    </div>
	
	<div class="row">
    	
    	<div class="col-md-12">
            <div class="form-group">
                <label for="definicionCompetencia" class="col-md-4 control-label">*{{Lang::get('leyendas.descripcion')}}</label>
				<input type="text" id="definicionCompetencia" name="definicionCompetencia" class="form-control required" maxlength="1000"  value="{{$competencia->definicion}}" tabindex="2" >
    	    </div>
    	</div>
	</div>
    	
    <div class="row">
    	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <div class="form-group">
              	<div class="rows">
                    <button 
                       type="button" 
                       class="btn btn-primary editable btn-sm glyphicon glyphicon-plus" 
                       data-toggle="modal" 
                       data-target="#modalComportamiento" onclick="return abreCompetencia();">Agregar</button>
                 </div>
					<table class="table table-bordered" id="tablaComportamientos">
						<thead>
							<tr>
								<th style="visibility: hidden">id</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.competencia.comportamiento')}}</th>
								<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
								<th style="visibility: hidden" width="0">Opciones</th>
							</tr>				
						</thead>
						
						<tbody>	
							@if (count($comportamientos) > 0)
						    	@foreach ($comportamientos as $com)
									<tr id="fila-{{$com->idcomportamiento}}">
										<td style="visibility: hidden">{{ $com->idcomportamiento}}</td>
										<td>{{ $com->nombre}}</td>
										<td class="tdCenter">
											<a href="#" class="btn btn-default  btn-xs editaComportamiento" id="{{$com->idcomportamiento}}">
								 				<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil" id="{{$com->idcomportamiento}}"></span>
								 			</a>
								 		</td>
								 		<td style="visibility: hidden" valor=""> </td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>	
			</div>
	    </div>
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group tdRight">
		@if ($competencia->nombre != '')
			@if (in_array('Editar',$sessionPermisos))
				<button class="btn btn-success btn-sm"  type="submit" tabindex="10" onclick="return guardaCompetencia();">
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@else
			@if (in_array('Crear',$sessionPermisos))
				<button class="btn btn-success btn-sm"  type="submit" tabindex="10" onclick="return guardaCompetencia();">
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@endif
			<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/catalogos/competencia')}}" tabindex="11" >
			<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
		</div>
	</div>
	
	@include('catalogos.competencia.modalComportamiento')