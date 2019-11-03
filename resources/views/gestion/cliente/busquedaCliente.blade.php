<script src="{{asset('js/tablesorter.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<div class="tdRight ts-pager form-inline">
	@if (in_array('Crear',$sessionPermisos))
		<a href="cliente/create" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.agregarRegistro')}}">
		<span class="fa fa-file-o"></span>&nbsp;{{Lang::get('leyendas.nuevo')}}</a>
	@endif   
	@if (in_array('Exportar',$sessionPermisos))
		@if(count($clientes) > 0)
			<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.exportarExcel')}}">
			<span class="fa fa-file-excel-o"></span>&nbsp;{{Lang::get('leyendas.exportarExcel')}}</a>
		@endif		
	@endif   						
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th class="tdCenter">{{Lang::get('leyendas.cliente.nombreComercial')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.cliente.razonSocial')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.cliente.rfc')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
			<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
		</tr>				
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			<th class="tdCenter">
				<input type="text" name="nombreComercialClienteBusca" id="nombreComercialClienteBusca" data-column="1"  value="{{$nombreComercialClienteBusca}}" class="form-control searchs letras" style="width:100%;">
			</th>
			<th class="tdCenter">
				<input type="text" name="razonSocialClienteBusca" id="razonSocialClienteBusca" data-column="1"  value="{{$razonSocialClienteBusca}}" class="form-control searchs letras" style="width:100%;">
			</th>
			<th class="tdCenter">
				<input type="text" name="rfcClienteBusca" id="rfcClienteBusca" data-column="2" value="{{$rfcClienteBusca}}" class="form-control searchs" style="width:100%;">
			</th>
			<th class="tdCenter">
				<select name="activoClienteBusca" id="activoClienteBusca" data-column="5" class="form-control searchs combo"  style="width:100%;">
					<option value="">Estatus</option>
					@foreach($catEstatus as $key => $value)
							@if ($key == $activoClienteBusca)
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
				{{$clientes->appends(['idcliente' => $idcliente])->render()}}				 
			</td>
			<td colspan="2" class="tdLeft ts-pager form-inline">
				@if(count($clientes) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>						
			<td class="tdRight ts-pager form-inline">
				@if(count($clientes) > 0)
					@include('seguridad.usuario.paginador')
				@endif
			</td>
		</tr>					
	</tfoot>	
	<tbody>	
		@if (count($clientes) > 0)
      		@foreach ($clientes as $emp)
				<tr>
					<td>{{$emp->nombre_comercial}}</td>
					<td>{{$emp->razon_social}}</td>
					<td>{{$emp->rfc}}</td>
					<td>{{ $catEstatus[$emp->activo]}}</td>
					<td class="tdCenter">
					@if ($emp->activo == 1)
						@if (in_array('Editar',$sessionPermisos))
							<a href="{{URL::action('ClienteController@edit',Crypt::encrypt($emp->idcliente))}}" class="btn btn-default btn-xs">
							<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil"></span></a>							
						@endif
						@if (in_array('Eliminar',$sessionPermisos))
	                         <a href="#" class="btn btn-default btn-xs modaleliminar" id="elimi-{{$emp->idcliente}}">
	                         <span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.eliminarRegistro')}}" class="glyphicon glyphicon-trash"></span></a>
	                    @endif
	                @else
	                	@if (in_array('Restablecer',$sessionPermisos))
	                		<a href="#" class="btn btn-default btn-xs modalrestablecer" id="resta-{{$emp->idcliente}}">	                	
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
@if (count($clientes) > 0)
	@include('gestion.cliente.modalDesactivaCliente')
	@include('gestion.cliente.modalRestableceCliente')
@endif