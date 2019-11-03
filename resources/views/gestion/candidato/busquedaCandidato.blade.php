<script src="{{asset('js/tablesorter.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<div class="tdRight ts-pager form-inline">
	@if (in_array('Crear',$sessionPermisos))
		<a href="candidato/create" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.agregarRegistro')}}">
		<span class="fa fa-file-o"></span>&nbsp;{{Lang::get('leyendas.nuevo')}}</a>
	@endif   
	@if (in_array('Exportar',$sessionPermisos))
		@if(count($candidatos) > 0)
			<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.exportarExcel')}}">
			<span class="fa fa-file-excel-o"></span>&nbsp;{{Lang::get('leyendas.exportarExcel')}}</a>
		@endif		
	@endif   						
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th class="tdCenter">{{Lang::get('leyendas.cliente')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.nombre')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.paterno')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.materno')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.candidato.noEmpleado')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
			<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
		</tr>				
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			<th class="tdCenter">
				<select name="idclienteCandidatoBusca" id="idclienteCandidatoBusca" data-column="1" class="form-control searchs combo">
					<option value="-1">Todas</option>
					@foreach($clientes as $cliente)
							@if ($cliente->idcliente == $idclienteCandidatoBusca)
								<option selected value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial }}</option>
							@else
								<option value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial}}</option>
							@endif
					@endforeach						
				</select>
			</th>
		
			<th class="tdCenter">
				<input type="text" name="nombreCandidatoBusca" id="nombreCandidatoBusca" data-column="1"  value="{{$nombreCandidatoBusca}}" class="form-control searchs letras" style="width:100%;">
			</th>
			<th class="tdCenter">
				<input type="text" name="paternoCandidatoBusca" id="paternoCandidatoBusca" data-column="1"  value="{{$paternoCandidatoBusca}}" class="form-control searchs letras" style="width:100%;">
			</th>
			<th class="tdCenter">
				<input type="text" name="maternoCandidatoBusca" id="maternoCandidatoBusca" data-column="1"  value="{{$maternoCandidatoBusca}}" class="form-control searchs letras" style="width:100%;">
			</th>
			<th class="tdCenter">
				<input type="text" name="noEmpleadoCandidatoBusca" id="noEmpleadoCandidatoBusca" data-column="1"  value="{{$noEmpleadoCandidatoBusca}}" class="form-control searchs letras" style="width:100%;">
			</th>
			<th class="tdCenter">
				<select name="activoCandidatoBusca" id="activoCandidatoBusca" data-column="5" class="form-control searchs combo"  style="width:100%;">
					<option value="">Estatus</option>
					@foreach($catEstatus as $key => $value)
							@if ($key == $activoCandidatoBusca)
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
			<td colspan="2" class="tdLeft ts-pager form-inline">
				{{$candidatos->appends(['idcandidato' => $idcandidato])->render()}}				 
			</td>
			<td colspan="2" class="tdLeft ts-pager form-inline">
				@if(count($candidatos) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>						
			<td class="tdRight ts-pager form-inline">
				@if(count($candidatos) > 0)
					@include('seguridad.usuario.paginador')
				@endif
			</td>
		</tr>					
	</tfoot>	
	<tbody>	
		@if (count($candidatos) > 0)
      		@foreach ($candidatos as $emp)
				<tr>
					<td>{{$emp->cliente->nombre_comercial}}</td>
					<td>{{$emp->nombre}}</td>
					<td>{{$emp->paterno}}</td>
					<td>{{$emp->materno}}</td>
					<td>{{$emp->no_empleado}}</td>
					<td>{{ $catEstatus[$emp->activo]}}</td>
					<td class="tdCenter">
					@if ($emp->activo == 1)
						@if (in_array('Editar',$sessionPermisos))
							<a href="{{URL::action('CandidatoController@edit',Crypt::encrypt($emp->idcandidato))}}" class="btn btn-default btn-xs">
							<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil"></span></a>							
						@endif
						@if (in_array('Eliminar',$sessionPermisos))
	                         <a href="#" class="btn btn-default btn-xs modaleliminar" id="elimi-{{$emp->idcandidato}}">
	                         <span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.eliminarRegistro')}}" class="glyphicon glyphicon-trash"></span></a>
	                    @endif
	                @else
	                	@if (in_array('Restablecer',$sessionPermisos))
	                		<a href="#" class="btn btn-default btn-xs modalrestablecer" id="resta-{{$emp->idcandidato}}">	                	
	                		<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.restablecerRegistro')}}" class="glyphicon glyphicon-repeat"></span></a>
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
@if (count($candidatos) > 0)
	@include('gestion.candidato.modalDesactivaCandidato')
	@include('gestion.candidato.modalRestableceCandidato')
@endif