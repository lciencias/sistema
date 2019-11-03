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
			<button type="button" id="buttonacceso" class="btn btn-default btn-sm submit glyphicon glyphicon-search">Buscar</button>
			@if (in_array('Exportar',$sessionPermisos))
				@if(count($accesos) > 0)
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
			<th class="tdCenter" style="width:20%;">{{Lang::get('leyendas.nombreUsuario')}}</th>
			<th class="tdCenter" style="width:20%;">{{Lang::get('leyendas.fecha')}}</th>
			<th class="tdCenter" style="width:20%;">{{Lang::get('leyendas.estatus')}}</th>
			<th class="tdCenter" style="width:20%;">{{Lang::get('leyendas.ip')}}</th>
			<th class="tdCenter" style="width:10%;">{{Lang::get('leyendas.so')}}</th>
			<th class="tdCenter" style="width:10%;">{{Lang::get('leyendas.explorador')}}</th>
		</tr>
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			<th class="tdCenter">
				<select name="idusuario" id="idusuario" data-column="1" class="form-control searchs combo">
					<option value="">Todos</option>
					@foreach($usuariosCat as $key => $value)
						@if($key == $idusuario) 
							<option value="{{$key}}" selected>{{$value}}</option>
						@else
							<option value="{{$key}}">{{$value}}</option>
						@endif	
					@endforeach
				</select>
			</th>					
			<th class="tdCenter">
				<input type="text" name="fecha" id="fecha" data-column="2" placeholder="{{Lang::get('leyendas.formatoFecha')}}" value="{{$fecha}}" class="form-control searchs fecha" {{($fechainiacceso !='' || $fechafinacceso !='')?'disabled':''}}>
			</th>					
			<th class="tdCenter">
				<select name="status" id="status" data-column="3" class="form-control searchs combo"  >
					<option value="">Todos</option>
					@foreach($catEstatus as $key => $value)
							@if ($key == $status)
								<option selected value="{{$key}}">{{$value}}</option>
							@else
								<option value="{{$key}}">{{$value}}</option>
							@endif
					@endforeach						
				</select>
			</th>
			<th class="tdCenter">
				<input type="text" name="ip" id="ip" data-column="4"  value="{{$ip}}" class="form-control searchs ip" >
			</th>					
			<th class="tdCenter">
				<input type="text" name="so" id="so" data-column="5"  value="{{$so}}" class="form-control searchs letras" >
			</th>					
			<th class="tdCenter">
				<input type="text" name="explorador" id="explorador" data-column="6"  value="{{$explorador}}" class="form-control searchs letras" >
			</th>					
		</tr>								
	</thead>
	<tfoot>
		<tr>
			<td colspan="2" class="tdLeft ts-pager form-inline">
				{{$accesos->appends(['idacceso' => $idacceso,'idusuario' => $idusuario, 'fecha' => $fecha, 'status' => $status, 'ip' => $ip, 'so' => $so, 'explorador' => $explorador])->render()}}
			</td>
			<td colspan="2" class="tdLeft ts-pager form-inline">
				@if(count($accesos) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>			
			<td colspan="2" class="tdRight ts-pager form-inline">
				@include('seguridad.usuario.paginador')
			</td>
		</tr>					
	</tfoot>	
	<tbody>
	@if(count($accesos) > 0)
		@foreach($accesos as $acceso)
			<tr>	
				<td>
					@if ($acceso->idusuario != '' && array_key_exists($acceso->idusuario,$usuariosCat))
						{{$usuariosCat[$acceso->idusuario]}}
					@endif
				</td>
				<td>{{$acceso->fecha}}</td>
				@if($acceso->status == 1)
					<td>Login</td>
				@else
					<td>Fallido</td>
				@endif
				<td>{{$acceso->ip}}</td>
				<td>{{$acceso->so}}</td>
				<td>{{$acceso->explorador}}</td>
			</tr>
		@endforeach
	@else
		<!--  {{Lang::get('leyendas.sinresultados')}}-->
	@endif						
	</tbody>
</table>