<script src="{{asset('js/tablesorter.js')}}"></script>
<script src="{{asset('js/empresas.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<div class="tdRight ts-pager form-inline">
	@if (in_array('Crear',$sessionPermisos))
		<a href="#" class="btn btn-default btn-sm modalNuevaEmpresa" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.agregarRegistro')}}">
		<span class="fa fa-file-o"></span>&nbsp;{{Lang::get('leyendas.nuevo')}}</a>
	@endif   
	@if (in_array('Exportar',$sessionPermisos))
		@if(count($empresas) > 0)
			<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.exportarExcel')}}">
			<span class="fa fa-file-excel-o"></span>&nbsp;{{Lang::get('leyendas.exportarExcel')}}</a>
		@endif		
	@endif   						
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th class="tdCenter">{{Lang::get('leyendas.nombre')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.rfc')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.represetante')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.correo')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
			<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
		</tr>				
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			<th class="tdCenter">
				<input type="text" name="nombreEmpresaBusca" id="nombreEmpresaBusca" data-column="1" placeholder="Empresa" value="{{$nombreEmpresaBusca}}" class="form-control searchs letras" style="width:200px;">
			</th>
			<th class="tdCenter">
				<input type="text" name="rfcEmpresaBusca" id="rfcEmpresaBusca" data-column="2" placeholder="RFC" value="{{$rfcEmpresaBusca}}" class="form-control searchs" style="width:200px;">
			</th>
			<th class="tdCenter">
				<input type="text" name="nombreRepresentanteBusca" id="nombreRepresentanteBusca" data-column="3" placeholder="Nombre" value="{{$nombreRepresentanteBusca}}" class="form-control searchs" style="width:200px;">
			</th>
			<th class="tdCenter">
				<input type="text" name="emailRepresentanteBusca" id="emailRepresentanteBusca" data-column="4" placeholder="Email" value="{{$emailRepresentanteBusca}}" class="form-control searchs" style="width:200px;">
			</th>			
			<th class="tdCenter">
				<select name="activo" id="activo" data-column="5" class="form-control searchs combo"  style="width:90px;">
					<option value="">Estatus</option>
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
			<td colspan="2" class="tdLeft ts-pager form-inline">
				{{$empresas->appends(['idempresa' => $idempresa, 'nombre' => $nombreEmpresaBusca])->render()}}				 
			</td>
			<td colspan="3" class="tdLeft ts-pager form-inline">
				@if(count($empresas) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>						
			<td class="tdRight ts-pager form-inline">
				@if(count($empresas) > 0)
					@include('seguridad.usuario.paginador')
				@endif
			</td>
		</tr>					
	</tfoot>	
	<tbody>	
		@if (count($empresas) > 0)
      		@foreach ($empresas as $emp)
				<tr>
					<td>{{$emp->nombre}}</td>
					<td>{{$emp->rfc}}</td>
					<td>{{$emp->nombre_representante}} {{$emp->paterno_representante}}</td>
					<td>{{$emp->email_representante}}</td>
					<td>{{ $catEstatus[$emp->activo]}}</td>
					<td class="tdCenter">
					@if ($emp->activo == 1)
						@if (in_array('Consultar',$sessionPermisos))
							<a href="#" id="{{Crypt::encrypt($emp->idempresa)}}" class="btn btn-default btn-xs editarEmpresa">
							<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil"></span></a>							
						@endif
						@if (in_array('Eliminar',$sessionPermisos))
	                         <a href="#" class="btn btn-default btn-xs modaleliminar" id="elimi-{{$emp->idempresa}}">
	                         <span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.eliminarRegistro')}}" class="glyphicon glyphicon-trash"></span></a>
	                    @endif
	                @else
	                	@if (in_array('Restablecer',$sessionPermisos))
	                		<a href="#" class="btn btn-default btn-xs modalrestablecer" id="resta-{{$emp->idempresa}}">	                	
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
@if (count($empresas) > 0)
	@include('seguridad.empresa.modalEmpresa')
	@include('seguridad.empresa.restablece')
	@include('seguridad.empresa.forms.empresa')
@endif