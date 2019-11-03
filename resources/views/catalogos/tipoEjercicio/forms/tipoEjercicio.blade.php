<script src="{{asset('js/tipoEjercicios.js')}}"></script>
<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
<input name="idelemento" id="idelemento" type="hidden" value="{{$tipoEjercicio->idtipoEjercicio}}" />
<input name="idtipoEjercicio" id="idtipoEjercicio" type="hidden" value="{{$tipoEjercicioIdUser}}" />
<input type="hidden" name="ejercicios" id="ejercicios" >	
	
	
	<div class="row">
    	<div  class="col-md-6">	
    		<div class="form-group">
    			<label for="email" class="col-md-2 control-label">*{{Lang::get('leyendas.tipoEjercicio.nombre')}}</label>
				<input id="nombreTipoEjercicio" name="nombreTipoEjercicio"  class="form-control required" maxlength="50" value="{{$tipoEjercicio->nombre}}" tabindex="1" >
    		</div>
    	</div>
    </div>
	
	<div class="row">
    	
    	<div class="col-md-12">
            <div class="form-group">
                <label for="descripcionTipoEjercicio" class="col-md-4 control-label">*{{Lang::get('leyendas.descripcion')}}</label>
				<input type="text" id="descripcionTipoEjercicio" name="descripcionTipoEjercicio" class="form-control required" maxlength="1000"  value="{{$tipoEjercicio->descripcion}}" tabindex="2" >
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
                       data-target="#modalEjercicio" onclick="return abreTipoEjercicio();">Agregar</button>
                 </div>
					<table class="table table-bordered" id="tablaEjercicios">
						<thead>
							<tr>
								<th style="visibility: hidden">id</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.tipoEjercicio.ejercicio')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.descripcion')}}</th>
								<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
								<th style="visibility: hidden" width="0">Opciones</th>
							</tr>				
						</thead>
						
						<tbody>	
							@if (count($ejercicios) > 0)
						    	@foreach ($ejercicios as $com)
									<tr id="fila-{{$com->idejercicio}}">
										<td style="visibility: hidden">{{ $com->idejercicio}}</td>
										<td>{{ $com->nombre}}</td>
										<td>{{ $com->descripcion}}</td>
										<td class="tdCenter">
											<a href="#" class="btn btn-default  btn-xs editaEjercicio" id="{{$com->idejercicio}}">
								 				<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil" id="{{$com->idejercicio}}"></span>
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
		@if ($tipoEjercicio->nombre != '')
			@if (in_array('Editar',$sessionPermisos))
				<button class="btn btn-success btn-sm"  type="submit" tabindex="10" onclick="return guardaTipoEjercicio();">
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@else
			@if (in_array('Crear',$sessionPermisos))
				<button class="btn btn-success btn-sm"  type="submit" tabindex="10" onclick="return guardaTipoEjercicio();">
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@endif
			<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/catalogos/tipoEjercicio')}}" tabindex="11" >
			<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
		</div>
	</div>
	
	@include('catalogos.tipoEjercicio.modalTipoEjercicio')