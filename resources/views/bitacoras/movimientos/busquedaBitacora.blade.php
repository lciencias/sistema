<script src="{{asset('js/tablesorter.js')}}"></script>
<script src="{{asset('js/acceso.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<div class="row">
		<div class="col-md-2"></div> 
		<div class="col-md-3 tdCenter">
			<div class="input-group date" >
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control pull-right searchs" type="text" name="fechainiacceso" id="fechainiacceso" placeholder="Fecha Inicial" value = "{{$fechainiacceso}}" />
			</div>
		</div>
		<div class="col-md-3 tdCenter">
			<div class="input-group date" >
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control pull-right searchs" type="text" name="fechafinacceso" id="fechafinacceso" placeholder="Fecha Final" value = "{{$fechafinacceso}}" />
			</div>				
		</div>		
		 <div class="col-md-2"> 
			<button type="button" id="buttonacceso" class="btn btn-default  btn-sm submit glyphicon glyphicon-search">Buscar</button>
			@if (in_array('Exportar',$sessionPermisos))
				@if(count($bitacoras) > 0)
					<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.exportarExcel')}}">
					<span class="fa fa-file-excel-o"></span>&nbsp;{{Lang::get('leyendas.exportarExcel')}}</a>
				@endif
			@endif  
		</div>
		<div class="col-md-2"></div> 
</div>
<br>
<table class="table">
	<thead>
		<tr>
			<th class="tdCenter">{{Lang::get('leyendas.nombreUsuario')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.modulo')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.fecha')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.registro')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.movimiento')}}</th>
		</tr>
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			<th class="tdCenter">
				<select name="iduser" id="iduser" data-column="1" class="form-control searchs combo">
					<option value="">Todos</option>
					@foreach($usuariosCat as $key => $value)
						@if($key == $iduser) 
							<option value="{{$key}}" selected>{{$value}}</option>
						@else
							<option value="{{$key}}">{{$value}}</option>
						@endif	
					@endforeach
				</select>
			</th>					
			<th class="tdCenter">
				<select name="idmodulo" id="idmodulo" data-column="2" class="form-control searchs combo">
					<option value="">Todos</option>
					@foreach($modulosCat as $key => $value)
						@if($key == $idmodulo) 
							<option value="{{$key}}" selected>{{$value}}</option>
						@else
							<option value="{{$key}}">{{$value}}</option>
						@endif	
					@endforeach
				</select>
			</th>			
			<th class="tdCenter">
				<input type="text" name="fecha" id="fecha" data-column="3" placeholder="{{Lang::get('leyendas.formatoFecha')}}" value="{{$fecha}}" class="form-control searchs fecha" style="width:120px;" {{($fechainiacceso !='' || $fechafinacceso !='')?'disabled':''}}>
			</th>					
			<th class="tdCenter">
				<input type="text" name="nombreRegistro" id="nombreRegistro" data-column="4" value="{{$nombreRegistro}}" class="form-control searchs ip" style="width:120px;">
			</th>					
			<th class="tdCenter">
				<select name="idmovimiento" id="idmovimiento" data-column="5" class="form-control searchs combo">
					<option value="">Todos</option>
					@foreach($movimientosCat as $key => $value)
						@if($key == $idmovimiento) 
							<option value="{{$key}}" selected>{{$value}}</option>
						@else
							<option value="{{$key}}">{{$value}}</option>
						@endif	
					@endforeach
				</select>
			</th>					
		</tr>								
	</thead>
	<tfoot>
		<tr>
			<td colspan="2" class="tdLeft ts-pager form-inline">
				{{$bitacoras->appends(['id' => $id,'iduser' => $iduser,'idmodulo' => $idmodulo, 'fecha' => $fecha, 'nombreRegistro' => $nombreRegistro,'idmovimiento' => $idmovimiento])->render()}}
			</td>
			<td colspan="2" class="tdLeft ts-pager form-inline">
				@if(count($bitacoras) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>
			<td class="tdRight ts-pager form-inline">
				@include('seguridad.usuario.paginador')
			</td>
		</tr>					
	</tfoot>	
	<tbody>
	@if(count($bitacoras) > 0)
		@foreach($bitacoras as $bitacora)
			<tr>					
				<td>
				@if (array_key_exists($bitacora->iduser,$usuariosCat))
					{{$usuariosCat[$bitacora->iduser]}}
				@endif
				</td>
				<td>
				@if (array_key_exists($bitacora->idmodulo,$modulosCat))
					{{$modulosCat[$bitacora->idmodulo]}}
				@endif
				</td>
				<td>{{$bitacora->fecha}}</td>
				<td>{{$bitacora->nombre_registro}}</td>
				<td>
				@if (array_key_exists($bitacora->tipo_movimiento,$movimientosCat))
					{{$movimientosCat[$bitacora->tipo_movimiento]}}
				@endif	
				</td>
			</tr>
		@endforeach
	@else
		<!--  {{Lang::get('leyendas.sinresultados')}}-->
	@endif						
	</tbody>	
</table>