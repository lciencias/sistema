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
		@if (count($usuarios) > 0)
			@foreach ($usuarios as $usu)
				<tr>					
					<td>{{ $usu->id}}</td>
					<td>{{ $usu->name}}</td>
					<td>{{ $usu->email}}</td>
					<td>{{ $usu->activo}}</td>
				</tr>
			@endforeach
		@endif
	</tbody>
</table>
