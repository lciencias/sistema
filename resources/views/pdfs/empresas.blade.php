<table class="table table-bordered">
	<thead>
		<tr>
			<th width="5%" class="tdCenter">{{Lang::get('leyendas.id')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.nombre')}}</th>
			<th class="tdCenter">{{Lang::get('leyendas.correo')}}</th>
			<th width="10%" class="tdCenter">{{Lang::get('leyendas.estatus')}}</th>
		</tr>
	</thead>
	<tbody>
		@if (count($empresas) > 0)
			@foreach ($empresas as $emp)
				<tr>					
					<td>{{$emp->idempresa}}</td>
					<td>{{$emp->nombre}}</td>
					<td>{{$emp->email_representante}}</td>
					<td>{{$emp->activo}}</td>
				</tr>
			@endforeach
		@endif
	</tbody>
</table>
