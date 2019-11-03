{!! Form::open(array('url'=>'bitacoras/acceso','method'=>'POST','autocomplete'=>'off','role'=>'search')) !!}
<div class="form-group">
	<div class="row">
		<div class="col-md-12 tdLeft">
		<span style="color:#ff0000;">Favor de seleccionar al menos un filtro de b&uacute;squeda</span><br><br>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-5 tdCenter">			
			<select name="idUsuario" id="idUsuario"  class="form-control" >
  				@if($idRol < 3)
  					<option value="-1">Todos los usuarios</option>
  				@endif
				@foreach($usuarios as $usu)
					@if($usu->id == $idUsuario)
						<option value="{{$usu->id}}" selected>{{$usu->name}}</option>
					@else
						<option value="{{$usu->id}}" >{{$usu->name}}</option>
					@endif
				@endforeach						
			</select>
		</div>
		<div class="col-md-2 tdCenter">
			<div class="input-group date" >
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control pull-right datetimepicker" type="text" name="fechaInicio" id="fechaInicio" placeholder="Fecha Inicial" value = "{{$fechaInicio}}" />
			</div>
		</div>
		<div class="col-md-2 tdCenter">
			<div class="input-group date" >
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control pull-right datetimepicker" type="text" name="fechaFinal" id="fechaFinal" placeholder="Fecha Final" value = "{{$fechaFinal}}" />
			</div>				
		</div>		
		<div class="col-md-3 tdCenter">
			<button type="submit" class="btn btn-primary  btn-sm submit" name="botonBuscar">
			<span class="glyphicon glyphicon-search"></span>&nbsp;Buscar</button>
			@if (!in_array('Exportar',$sessionPermisos))
				@if(count($accesos) > 0)
					<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-warning btn-sm">
					<span class="glyphicon glyphicon-export"></span>&nbsp;Exportar</a>
				@else
					<button class="btn btn-warning btn-sm" disabled="disabled">
					<span class="glyphicon glyphicon-export"></span>&nbsp;Exportar</button>	
				@endif
			@endif   			
		</div>
	</div>						
</div>
{{Form::close()}}