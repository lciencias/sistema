<script src="{{asset('js/tablesorter.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<div class="tdRight ts-pager form-inline">
	@if (in_array('Crear',$sessionPermisos))
		<a href="perfil/create" class="btn btn-default btn-sm " data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.agregarRegistro')}}">
		<span class="fa fa-file-o"></span>&nbsp;{{Lang::get('leyendas.nuevo')}}</a>
	@endif
	@if (in_array('Exportar',$sessionPermisos))
		@if(count($perfiles) > 0)
			<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.exportarExcel')}}">
			<span class="fa fa-file-excel-o"></span>&nbsp;{{Lang::get('leyendas.exportarExcel')}}</a>
			
			
		@endif
	@endif   
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			@if($isAdmin)
				<th class="tdCenter">{{Lang::get('leyendas.empresa')}}</th>
			@endif  
			<th class="tdCenter">{{Lang::get('leyendas.nombre')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.descripcion')}}</th>
			<th width="10%" class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
			<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
		</tr>				
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			@if($isAdmin)
				<th class="tdCenter">
					<select name="empresa" id="empresa" data-column="5" class="form-control searchs combo">
						<option value="-1">Todas</option>
						@foreach($catEmpresas as $key => $value)
								@if ($key == $idempresa)
									<option selected value="{{$key}}">{{$value}}</option>
								@else
									<option value="{{$key}}">{{$value}}</option>
								@endif
						@endforeach						
					</select>
				</th>
			@endif 
		
			<th class="tdCenter">
				<input type="text" name="nombre" id="nombre" data-column="1"  value="{{$nombre}}" class="form-control searchs letras">
			</th>					
			
			<th class="tdCenter">
				<input type="text" name="descripcion" id="descripcion" data-column="1"  value="{{$descripcion}}" class="form-control searchs letras">
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
				 {{$perfiles->appends(['idperfil' => $idperfil, 'nombre' => $nombre, '$descripcion' => $descripcion, 'idempresa' => $idempresa, 'activo' => $activo])->render()}}
			</td>
			@if($isAdmin)
				<td colspan="3" class="tdLeft ts-pager form-inline">
			@else
				<td colspan="2" class="tdLeft ts-pager form-inline">
			@endif  
			
			
				@if(count($perfiles) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>			
			<td class="tdRight ts-pager form-inline">
				@if(count($perfiles) > 0)
					@include('seguridad.usuario.paginador')
				@endif
			</td>
		</tr>					
	</tfoot>	
	<tbody>	
		@if (count($perfiles) > 0)
	    	@foreach ($perfiles as $per)
				<tr>
					@if($isAdmin)
						@if($per->idempresa != null)
							<td>{{ $catEmpresas[$per->idempresa]}}</td>
						@else
							<td></td>
						@endif
					@endif
					<td>{{ $per->nombre}}</td>
					<td>{{ $per->descripcion}}</td>
					<td>{{ $catEstatus[$per->activo]}}</td>
					<td class="tdCenter">
						@if ($per->activo == 1)
							@if (in_array('Consultar',$sessionPermisos))
						 		<a href="{{URL::action('PerfilController@edit',Crypt::encrypt($per->idperfil))}}" class="btn btn-default  btn-xs">
						 			<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil"></span>
						 		</a>					 		
						 	@endif
						 	@if (in_array('Eliminar',$sessionPermisos) && $per->idperfil > 1 && $per->idperfil != Session::get('userIdPerfil'))
	                       		<a href="#" class="btn btn-default btn-xs modaleliminar" id="elimi-{{$per->idperfil}}">
	                       			<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.eliminarRegistro')}}" class="glyphicon glyphicon glyphicon-ban-circle"></span>
	                       		</a>
	                       	@endif
	                	@else
	                       	@if (in_array('Restablecer',$sessionPermisos))
								<a href="#" class="btn btn-default btn-xs modalrestablecer" id="resta-{{$per->idperfil}}">
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
@if (count($perfiles) > 0)
	@include('seguridad.perfil.modalPerfil')
	@include('seguridad.perfil.restablecePerfil')
@endif