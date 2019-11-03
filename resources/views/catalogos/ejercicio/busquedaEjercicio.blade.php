<script src="{{asset('js/tablesorter.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<div class="tdRight ts-pager form-inline">
	@if (in_array('Crear',$sessionPermisos))
		<a href="ejercicio/create" class="btn btn-default btn-sm " data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.agregarRegistro')}}">
		<span class="fa fa-file-o"></span>&nbsp;{{Lang::get('leyendas.nuevo')}}</a>
	@endif
	@if (in_array('Exportar',$sessionPermisos))
		@if(count($ejercicios) > 0)
			<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.exportarExcel')}}">
			<span class="fa fa-file-excel-o"></span>&nbsp;{{Lang::get('leyendas.exportarExcel')}}</a>
			
			
		@endif
	@endif   
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th class="tdCenter">{{Lang::get('leyendas.cliente')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.ejercicio.tipoEjercicio')}}</th>
			<th width="10%" class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
			<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
		</tr>				
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			<th class="tdCenter">
				<select name="idclienteEjercicioBusca" id="idclienteEjercicioBusca" data-column="1" class="form-control searchs combo">
    				<option value="">{{Lang::get('leyendas.todos')}}</option>
    				@foreach($clientes as $cliente)
    						@if ($cliente->idcliente == $idclienteEjercicioBusca)
    							<option selected value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial }}</option>
    						@else
    							<option value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial}}</option>
    						@endif
    				@endforeach						
    			</select>
			</th>					
			
			<th class="tdCenter">
				<select name="idTipoEjercicioBusca" id="idTipoEjercicioBusca" data-column="1" class="form-control searchs combo">
    				<option value="">{{Lang::get('leyendas.todos')}}</option>
    				@foreach($tiposEjercicios as $tipoEje)
    						@if ($tipoEje->idtipo_ejercicio == $idTipoEjercicioBusca)
    							<option selected value="{{$tipoEje->idtipo_ejercicio}}">{{$tipoEje->nombre}}</option>
    						@else
    							<option value="{{$tipoEje->idtipo_ejercicio }}">{{$tipoEje->nombre}}</option>
    						@endif
    				@endforeach						
    			</select>
			</th>	
			<th class="tdCenter">
				<select name="activo" id="activo" data-column="5" class="form-control searchs combo">
					<option value="-1">Todos</option>
					@foreach($catEstatus as $key => $value)
							@if ($key == $activo)
								<option selected value="{{$key}}">{{$value}}</option>
							@else
								<option value="{{$key}}">{{$value}}</option>
							@endif
					@endforeach						
				</select>
			</th>
			<th>&nbsp;</th>
		</tr>								
	</thead>
	<tfoot>
		<tr>
			<td class="tdLeft ts-pager form-inline">
				 {{$ejercicios->appends(['idtipo_ejercicio_cliente' => $idclienteEjercicioBusca, 'activo' => $activo])->render()}}
			</td>
				<td colspan="2" class="tdLeft ts-pager form-inline">
			
			
				@if(count($ejercicios) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>			
			<td class="tdRight ts-pager form-inline">
				@if(count($ejercicios) > 0)
					@include('seguridad.usuario.paginador')
				@endif
			</td>
		</tr>					
	</tfoot>	
	<tbody>	
		@if (count($ejercicios) > 0)
	    	@foreach ($ejercicios as $per)
				<tr>
					<td>{{ $per->cliente->nombre_comercial}}</td>
					<td>{{ $per->tipo_ejercicio->nombre}}</td>
					<td>1</td>
					<td class="tdCenter">
						@if ($per->activo == 1)
							@if (in_array('Consultar',$sessionPermisos))
						 		<a href="{{URL::action('EjercicioController@edit',Crypt::encrypt($per->idtipo_ejercicio_cliente))}}" class="btn btn-default  btn-xs">
						 			<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil"></span>
						 		</a>					 		
						 	@endif
						 	@if (in_array('Eliminar',$sessionPermisos) && $per->idejercicio > 1 && $per->idejercicio != Session::get('userIdEjercicio'))
	                       		<a href="#" class="btn btn-default btn-xs modaleliminar" id="elimi-{{$per->idtipo_ejercicio_cliente}}">
	                       			<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.eliminarRegistro')}}" class="glyphicon glyphicon glyphicon-ban-circle"></span>
	                       		</a>
	                       	@endif
	                	@else
	                       	@if (in_array('Restablecer',$sessionPermisos))
								<a href="#" class="btn btn-default btn-xs modalrestablecer" id="resta-{{$per->idtipo_ejercicio_cliente}}">
									<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.restablecerRegistro')}}" class="glyphicon glyphicon-repeat"></span>
								</a>
							@endif                       
	                    @endif
					</td>
				</tr>
			@endforeach
		@else
			<!--  {{Lang::get('leyendas.sinresultados')}}-->
		@endif
	</tbody>
</table>	
@if (count($ejercicios) > 0)
	@include('catalogos.ejercicio.modalEjercicio')
	@include('catalogos.ejercicio.restableceEjercicio')
@endif