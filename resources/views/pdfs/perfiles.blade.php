<table class="table table-bordered">
	<thead>
		<tr>
			<th width="5%" class="tdCenter">{{Lang::get('leyendas.id')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.nombre')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.descripcion')}}</th>
			<th width="10%" class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
		</tr>
	</thead>
	<tbody>
		@if (count($perfiles) > 0)
			@foreach ($perfiles as $per)
				<tr>					
					<td>{{$per->idperfil}}</td>
					<td>{{$per->nombre}}</td>
					<td>{{$per->descripcion}}</td>
					<td>{{$per->activo}}</td>
				</tr>
			@endforeach
		@endif
	</tbody>
</table>
