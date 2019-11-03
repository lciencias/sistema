<script src="{{asset('js/tablesorter.js')}}"></script>
<script src="{{asset('js/competencias.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<div class="tdRight ts-pager form-inline">
	@if (in_array('Crear',$sessionPermisos))
		<a href="competencia/create" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.agregarRegistro')}}">
		<span class="fa fa-file-o"></span>&nbsp;{{Lang::get('leyendas.nuevo')}}</a>
	@endif   
	@if (in_array('Exportar',$sessionPermisos))
		@if(count($competencias) > 0)
			<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.exportarExcel')}}">
			<span class="fa fa-file-excel-o"></span>&nbsp;{{Lang::get('leyendas.exportarExcel')}}</a>
		@endif		
	@endif   						
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th width="15%" class="tdCenter">{{Lang::get('leyendas.competencia.nombre')}}</th>
			<th width="10%" class="tdCenter">{{Lang::get('leyendas.competencia.tipo')}}</th>
			<th width="55%" class="tdCenter">{{Lang::get('leyendas.descripcion')}}</th>
			<th width="12%" class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
			<th width="8%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
		</tr>				
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			<th class="tdCenter">
				<input type="text" name="nombreCompetenciaBusca" id="nombreCompetenciaBusca" data-column="1"  value="{{$nombreCompetenciaBusca}}" class="form-control searchs letras" style="width:100%;">
			</th>
			<th class="tdCenter">
				<select name="tipoCompetenciaBusca" id="tipoCompetenciaBusca"  class="form-control searchs combo"  style="width:100%;">
					<option value="">{{Lang::get('leyendas.todos')}}</option>
					@foreach($catTipoCompetencias as $competencia)
						@if( $competencia->idtipo_competencia == $tipoCompetenciaBusca)
							<option value="{{$competencia->idtipo_competencia}}" selected>{{$competencia->nombre}}</option>
						@else
							<option value="{{$competencia->idtipo_competencia}}">{{$competencia->nombre}}</option>
						@endif							
					@endforeach						
				</select>
			</th>
			<th class="tdCenter">
				<input type="text" name="definicionCompetenciaBusca" id="definicionCompetenciaBusca" data-column="2" value="{{$definicionCompetenciaBusca}}" class="form-control searchs" style="width:100%;">
			</th>
			<th class="tdCenter">
				<select name="activoCompetenciaBusca" id="activoCompetenciaBusca" data-column="5" class="form-control searchs combo"  style="width:100%;">
					<option value="-1">Todos</option>
					@foreach($catEstatus as $key => $value)
							@if ($key == $activoCompetenciaBusca)
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
			<td colspan="2"  class="tdLeft ts-pager form-inline">
				{{$competencias->appends(['idcompetencia' => $idcompetencia])->render()}}				 
			</td>
			<td colspan="2"  class="tdLeft ts-pager form-inline">
				@if(count($competencias) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>						
			<td class="tdRight ts-pager form-inline">
				@if(count($competencias) > 0)
					@include('seguridad.usuario.paginador')
				@endif
			</td>
		</tr>					
	</tfoot>	
	<tbody>	
		@if (count($competencias) > 0)
      		@foreach ($competencias as $emp)
				<tr>
					<td>{{$emp->nombre}}</td>
					<td>{{$emp->tipo_competencia->nombre}}</td>
					<td>{{$emp->definicion}}</td>
					<td>{{ $catEstatus[$emp->activo]}}</td>
					<td class="tdCenter">
					@if ($emp->activo == 1)
						@if (in_array('Editar',$sessionPermisos))
							<a href="{{URL::action('CompetenciaController@edit',Crypt::encrypt($emp->idcompetencia))}}" class="btn btn-default btn-xs">
							<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil"></span></a>							
						@endif
						@if (in_array('Eliminar',$sessionPermisos))
	                         <a href="#" class="btn btn-default btn-xs modaleliminar" id="elimi-{{$emp->idcompetencia}}">
	                         <span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.eliminarRegistro')}}" class="glyphicon glyphicon-trash"></span></a>
	                    @endif
	                @else
	                	@if (in_array('Restablecer',$sessionPermisos))
	                		<a href="#" class="btn btn-default btn-xs modalrestablecer" id="resta-{{$emp->idcompetencia}}">	                	
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
@if (count($competencias) > 0)
	@include('catalogos.competencia.modalDesactivaCompetencia')
	@include('catalogos.competencia.modalRestableceCompetencia')
@endif