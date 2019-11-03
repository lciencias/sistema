<script src="{{asset('js/tablesorter.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<div class="tdRight ts-pager form-inline">
	@if (in_array('Crear',$sessionPermisos))
		<a href="usuario/create" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.agregarRegistro')}}">
		<span class="fa fa-file-o"></span>&nbsp;{{Lang::get('leyendas.nuevo')}}</a>
	@endif   
	@if (in_array('Exportar',$sessionPermisos))
		@if(count($usuarios) > 0)
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
			<th width="20%" class="tdCenter">{{Lang::get('leyendas.perfil')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.usuario')}}</th>
			<th width="15%" class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
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
				<input type="text" name="name" id="name" data-column="1" value="{{$name}}" class="form-control searchs letras" >
			</th>						
			<th class="tdCenter">
				<select name="idperfil" id="idperfil" data-column="3" class="form-control searchs combo" >
					<option value="">Todos</option>
					@foreach($catPerfiles as $key => $value)
						@if( $key == $idPerfil)
							<option value="{{$key}}" selected>{{$value}}</option>
						@else
							<option value="{{$key}}">{{$value}}</option>
						@endif							
					@endforeach						
				</select>
			</th>
			<th class="tdCenter">
				<input type="text" name="email" id="email" data-column="4" value="{{$email}}" class="form-control searchs" >
			</th>
			<th class="tdCenter">
				<select name="activo" id="activo" data-column="5" class="form-control searchs combo">
					<option value="">Todos</option>
					@foreach($catEstatus as $key => $value)
						@if( $key == $activo)
						<option value="{{$key}}" selected >{{$value}}</option>
						@else
							<option value="{{$key}}">{{$value}}</option>
						@endif
					@endforeach						
				</select>
			</th>
			<th data-column="6" width="10%" >&nbsp;</th>
		</tr>								
	</thead>
	<tfoot>
		<tr>
			<td colspan="2" class="tdLeft ts-pager form-inline">
				{{$usuarios->appends(['idPerfil' => $idPerfil, 'nombre' => $name, 'email' => $email, 'activo' => $activo])->render()}}
			</td>
			@if($isAdmin)
				<td colspan="3" class="tdLeft ts-pager form-inline">
			@else
				<td colspan="2" class="tdLeft ts-pager form-inline">
			@endif  			
				@if(count($usuarios) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>						
			<td colspan="1" class="tdRight ts-pager form-inline">
				@if(count($usuarios) > 0)
					@include('seguridad.usuario.paginador')
				@endif
			</td>
		</tr>					
	</tfoot>	
	<tbody>	
		@if (count($usuarios) > 0)
			@foreach ($usuarios as $usu)
				<tr>	
					@if($isAdmin)
						@if($usu->idempresa != null)
							<td>{{ $catEmpresas[$usu->idempresa]}}</td>
						@else
							<td></td>
						@endif
					@endif				
					<td>{{ $usu->name}}</td>
					
					<td>{{ $catPerfiles[$usu->idperfil]}}</td>
					
					<td>{{ $usu->email}}</td>
					<td>{{ $catEstatus[$usu->activo]}}</td>
					<td class="tdCenter">
					@if ($usu->activo == 1)
						@if (in_array('Consultar',$sessionPermisos))
							<a href="{{URL::action('UsuarioController@edit',Crypt::encrypt($usu->id))}}" class="btn btn-default btn-xs">
							<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil"></span></a>
						@endif
						@if (in_array('Eliminar',$sessionPermisos)  && $usu->id > 1 && $usu->id != Session::get('idUser'))
		               		<a href="#" class="btn btn-default btn-xs modaleliminar" id="elimi-{{$usu->id}}">
		        				<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.eliminarRegistro')}}" class="glyphicon glyphicon-ban-circle"></span>
		        			</a>
		                @endif
		                @if (in_array('Resetear',$sessionPermisos) && $usu->id > 1 && $usu->id != Session::get('idUser'))
		                	<a href="#" class="btn btn-default btn-xs modalresetear" id="reset-{{$usu->id}}">
		                    <span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.resetearContrasena')}}" class="glyphicon glyphicon-refresh"></span></a>
		                @endif
					@else
		            	@if (in_array('Restablecer',$sessionPermisos) && $usu->id > 1 && $usu->id != Session::get('idUser'))
		                	<a href="#" class="btn btn-default btn-xs modalrestablecer" id="resta-{{$usu->id}}">
		                    <span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.restablecerRegistro')}}" class="glyphicon glyphicon-repeat"></span></a>
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
@if (count($usuarios) > 0)
	@include('seguridad.usuario.modal')
	@include('seguridad.usuario.reset')
	@include('seguridad.usuario.restablece')
@endif