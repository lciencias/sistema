{!! Form::open(array('url'=>'seguridad/perfil','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<?php 
$tmp1 = $tmp2 = $tmp3 = "";
if($activo == 1){
	$tmp1 = "selected ";
	$tmp2 = $tmp3 = "";
}
if($activo == 0){
	$tmp2 = "selected ";
	$tmp1 = $tmp3 = "";
}
if($activo == ''){
	$tmp3 = "selected ";
	$tmp1 = $tmp2 = "";
}
?>
<div class="form-group">
	<div class="row">
		<div class="col-md-4"> 
			<input type="text" class="form-control inputText" name="searchText" placeholder="Buscar por perfil" value="{{$searchText}}" >
		</div> 
		@if($isAdmin == 1)
		<div class="col-md-4"> 
			<select  name="idEmpresaBusca" value="{{$idEmpresaBusca}}"
			class="form-control inputSelect"> 
				<option value="" selected >Todas las Empresas</option>
					@foreach($empresas as $emp)
						@if($emp->idempresa == $idEmpresaBusca)
							<option value="{{$emp->idempresa}}" selected>{{$emp->nombre}}</option>
						@else
							<option value="{{$emp->idempresa}}" >{{$emp->nombre}}</option>
						@endif
					@endforeach
			</select>			
		</div>  
		@endif
		<div class="col-md-4">
			<select name="activo" id="activo"  class="form-control" placeholder="Luis">
  				<option value="-1">Todos los Estatus </option>
  				<option value="1" {{$tmp1}} >Activo</option>
  				<option value="0" {{$tmp2}}>No Activo</option>
			</select>		
		</div>
	</div>
	<div class="row">	
    	<div class="col-md-12 tdRight"> <br/>
			<button type="submit" class="btn btn-primary  btn-sm submit">
			<span class="glyphicon glyphicon-search"></span>&nbsp;Buscar</button>
			@if (in_array('Crear',$sessionPermisos))
				 <a href="perfil/create" class="btn btn-success  btn-sm">
				 <span class="fa fa-file-o"></span>&nbsp;Nuevo</a>
			@endif
			@if (in_array('Exportar',$sessionPermisos))
				@if(count($perfiles) > 0)
					<a href="{{URL::action('ExcelController@index',$moduloId)}}" class="btn btn-warning btn-sm">
					<span class="fa fa-file-excel-o"></span>&nbsp;Exportar</a>
				@else
					<button class="btn btn-warning btn-sm" disabled="disabled">
					<span class="fa fa-file-excel-o"></span>&nbsp;Exportar</button>				
				@endif				
			@endif   			
		</div> 		     
    </div>
   
</div>
{{Form::close()}}