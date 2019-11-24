<script src="{{asset('js/tablesorter.js')}}"></script>
<input type ="hidden" name="noPage" id="noPage" value="{{$noPage}}">
<input type="hidden" name="idRegistro" id="idRegistro" value="">
<table class="table">
	<thead>
		<tr>
			<th class="tdCenter">{{Lang::get('leyendas.calificar.nombre')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.calificar.paterno')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.calificar.materno')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.calificar.proyecto')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.calificar.ejercicio')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.calificar.fechaFin')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.calificar.estatus')}}</th>
			<th width="8%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.calificar.accion')}}</th>
		</tr>
		<tr class="tablesorter-filter-row tablesorter-ignoreRow" role="search">
			<th class="tdCenter">
				<input type="text" name="nombre" id="nombre" data-column="1" value="{{$nombre}}" class="form-control searchs letras" >	
			</th>
			<th class="tdCenter">
				<input type="text" name="paterno" id="paterno" data-column="1" value="{{$paterno}}" class="form-control searchs letras" >
			</th>						
			<th class="tdCenter">
				<input type="text" name="materno" id="materno" data-column="1" value="{{$materno}}" class="form-control searchs letras" >
			</th>						
			<th class="tdCenter">
				<select name="idProyecto" id="idProyecto" data-column="1" class="form-control searchs combo" >
					<option value="">Todos</option>
					@foreach($proyectos as $obj)
						@if( $obj->idproyecto == $idProyecto)
							<option value="{{$obj->idproyecto}}" selected>{{$obj->nombre}}</option>
						@else
							<option value="{{$obj->idproyecto}}">{{$obj->nombre}}</option>
						@endif							
					@endforeach						
				</select>
			</th>
			<th class="tdCenter">
				<select name="idEjercicio" id="idEjercicio" data-column="1" class="form-control searchs combo" >
					<option value="">Todos</option>
					@foreach($tipo as $obj)
						@if( $obj->idtipo_ejercicio == $idEjercicio)
							<option value="{{$obj->idtipo_ejercicio}}" selected>{{$obj->nombre}}</option>
						@else
							<option value="{{$obj->idtipo_ejercicio}}">{{$obj->nombre}}</option>
						@endif							
					@endforeach						
				</select>
			</th>
			<th class="tdCenter">
				<input type="text" name="fechaFin" id="fechaFin" data-column="1" placeholder="{{Lang::get('leyendas.configuracion.formatofechaDMY')}}" value="{{$fechaFin}}" class="form-control searchs fecha">			
			</th>									
			<th class="tdCenter">
				<select name="estatus" id="estatus" data-column="5" class="form-control searchs combo">
					<option value="">Todos</option>
					@foreach($catEstatus as $key => $value)
						@if( $key == $estatus)
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
			<td colspan="4" class="tdLeft ts-pager form-inline">
				{{$ejercicios->appends(['nombre' => $nombre,'paterno' => $paterno,'materno' => $materno,'idProyecto' => $idProyecto,
									    'idEjercicio' => $idEjercicio, 'fechaFin' => $fechaFin, 'estatus' => $estatus])->render()}}
			</td>
			<td colspan="3" class="tdLeft ts-pager form-inline"> 			
				@if(count($ejercicios) > 0)
					{{Lang::get('leyendas.registros')}}: {{$leyenda['from']}} 
					{{Lang::get('leyendas.de')}} {{$leyenda['to']}}&nbsp;&nbsp;&nbsp;
					{{Lang::get('leyendas.total')}} ( {{$leyenda['total']}} )     					
				@endif
			</td>						
			<td colspan="1" class="tdRight ts-pager form-inline">
				@if(count($ejercicios) > 0)
					@include('seguridad.usuario.paginador')
				@endif
			</td>
		</tr>					
	</tfoot>	
	<tbody>
		@if (count($ejercicios) > 0)
			@foreach ($ejercicios as $eje)
				<tr>
					<td style="background-color:{{$eje->clase}};">{{ $eje->nombre}}</td>
					<td style="background-color:{{$eje->clase}};">{{ $eje->paterno}}</td>
					<td style="background-color:{{$eje->clase}};">{{ $eje->materno}}</td>
					<td style="background-color:{{$eje->clase}};">{{ $eje->proyecto}}</td>
					<td style="background-color:{{$eje->clase}};">{{ $eje->tipoejercicio}}</td>
					<td style="background-color:{{$eje->clase}};">{{ $eje->fecha_fin}}</td>
					<td style="background-color:{{$eje->clase}};">{{ $catEstatus[$eje->estatus]}}</td>
					<td  style="background-color:{{$eje->clase}};" class="tdCenter">
						<a href="{{URL::action('CalificarController@edit',Crypt::encrypt($eje->idcandidato_proyecto_ejercicio))}}" class="btn btn-default btn-xs">
						<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.calificar')}}" class="glyphicon glyphicon-pencil"></span>&nbsp;{{Lang::get('leyendas.calificar')}}</a>					
					</td>
				</tr>
			@endforeach
		@else
			<!--  {{Lang::get('leyendas.sinresultados')}}-->
		@endif
	</tbody>
</table>	