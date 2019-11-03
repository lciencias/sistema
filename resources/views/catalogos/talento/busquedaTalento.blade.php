<script src="{{asset('js/tablesorter.js')}}"></script>
<script src="{{asset('js/talentos.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<div class="tdRight ts-pager form-inline">
	@if (in_array('Crear',$sessionPermisos))
		<a href="talento/create" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.agregarRegistro')}}">
		<span class="fa fa-file-o"></span>&nbsp;{{Lang::get('leyendas.nuevo')}}</a>
	@endif   
	@if (in_array('Exportar',$sessionPermisos))
		@if(count($talentos) > 0)
			<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.exportarExcel')}}">
			<span class="fa fa-file-excel-o"></span>&nbsp;{{Lang::get('leyendas.exportarExcel')}}</a>
		@endif		
	@endif   						
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th width="15%" class="tdCenter">{{Lang::get('leyendas.talento.nombre')}}</th>
			<th width="65%" class="tdCenter">{{Lang::get('leyendas.talento.definicion')}}</th>
			<th width="12%" class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
			<th width="8%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
		</tr>				
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			<th class="tdCenter">
				<input type="text" name="nombreTalentoBusca" id="nombreTalentoBusca" data-column="1"  value="{{$nombreTalentoBusca}}" class="form-control searchs letras" style="width:100%;">
			</th>
			<th class="tdCenter">
				<input type="text" name="descripcionTalentoBusca" id="descripcionTalentoBusca" data-column="2" value="{{$descripcionTalentoBusca}}" class="form-control searchs" style="width:100%;">
			</th>
			<th class="tdCenter">
				<select name="activoTalentoBusca" id="activoTalentoBusca" data-column="5" class="form-control searchs combo"  style="width:100%;">
					<option value="-1">Todos</option>
					@foreach($catEstatus as $key => $value)
							@if ($key == $activoTalentoBusca)
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
				{{$talentos->appends(['idtalento' => $idtalento])->render()}}				 
			</td>
			<td colspan="2"  class="tdLeft ts-pager form-inline">
				@if(count($talentos) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>						
			<td class="tdRight ts-pager form-inline">
				@if(count($talentos) > 0)
					@include('seguridad.usuario.paginador')
				@endif
			</td>
		</tr>					
	</tfoot>	
	<tbody>	
		@if (count($talentos) > 0)
      		@foreach ($talentos as $emp)
				<tr>
					<td>{{$emp->nombre}}</td>
					<td>{{$emp->definicion}}</td>
					<td>{{ $catEstatus[$emp->activo]}}</td>
					<td class="tdCenter">
					@if ($emp->activo == 1)
						@if (in_array('Editar',$sessionPermisos))
							<a href="{{URL::action('TalentoController@edit',Crypt::encrypt($emp->idtalento))}}" class="btn btn-default btn-xs">
							<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil"></span></a>							
						@endif
						@if (in_array('Eliminar',$sessionPermisos))
	                         <a href="#" class="btn btn-default btn-xs modaleliminar" id="elimi-{{$emp->idtalento}}">
	                         <span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.eliminarRegistro')}}" class="glyphicon glyphicon-trash"></span></a>
	                    @endif
	                @else
	                	@if (in_array('Restablecer',$sessionPermisos))
	                		<a href="#" class="btn btn-default btn-xs modalrestablecer" id="resta-{{$emp->idtalento}}">	                	
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
@if (count($talentos) > 0)
	@include('catalogos.talento.modalDesactivaTalento')
	@include('catalogos.talento.modalRestableceTalento')
@endif