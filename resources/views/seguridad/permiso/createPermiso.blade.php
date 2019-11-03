@extends ('layouts.home')
@section ('contenido')
{!!Form::open(array('url'=>'seguridad/store','method'=>'POST'))!!}
<script src="{{asset('js/perfil.js')}}"></script>
<div class="rows">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<?php $contador = 1 ?>
		<?php $columnas = 8;?>
		<?php $ancho = round(100/$columnas) ?>
		<?php $contadorTd = 1 ?>
		<?php $asigna  = "Al marcar el checkbox, asignas el módulo al perfil";?>
		<?php $asigna2 = "Al marcar el checkbox, asignas el permiso al perfil";?>
		@if(count($modulosP) > 0)
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			  @foreach($modulosP as $modulo)	  
			  	<div class="panel panel-default">
		    		<div class="panel-heading" role="tab" id="heading{{$contador}}">
		      			<h4 class="panel-title">
		      				<?php
		      					$checkModulo = "";
			      				if(in_array($modulo->idmodulo, $permisoModulo)){
				      				$checkModulo = " checked ";
				      			}				      			 
		      				?>
							<input class="test-checkbox parents p-{{$modulo->idmodulo}}" id="p-{{$modulo->idmodulo}}"  
								data-toggle="tooltip" data-placement="left" title="{{$asigna}}"
								name="modulosParents[]" type="checkbox" value="{{$modulo->idmodulo}}" {{$checkModulo}}>		      			
		        				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$contador}}" aria-expanded="false" aria-controls="collapse{{$contador}}">
		          				&nbsp;&nbsp;
		          				<label for="mods"><b>*&nbsp;&nbsp;{{$modulo->nombre}}</b></label>
		          				</a>
		      			</h4>
		    		</div>
		    		<div id="collapse{{$contador}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$contador}}">
		      			<div class="panel-body">
		      			@if(count($modulosC) > 0)       
		      				<table class="table table-bordered">
		        			@foreach($modulosC as $mod)
		        				@if($mod->parent == $modulo->idmodulo)		
		        				<?php 
		        					$contadorTd = 1;
		        					$checkModuloParent= "";
		        					if(in_array($mod->idmodulo, $permisoModulo)){
		        						$checkModuloParent = " checked ";
		        					}
		        				?>    	
		        				<tr>
		        					<td class="warning" colspan="{{$columnas}}">
		        						<input class="test-checkbox parents_parents sub-{{$modulo->idmodulo}}" id="sub-{{$modulo->idmodulo}}-{{$mod->idmodulo}}"  
										data-toggle="tooltip" data-placement="left" title="{{$asigna}}"
										name="sub-{{$modulo->idmodulo}}-{{$mod->idmodulo}}" type="checkbox" value="{{$modulo->idmodulo}}-{{$mod->idmodulo}}" {{$checkModuloParent}}>
			        					<b>{{$mod->nombre}}</b>
			        				</td>
			        			</tr>
		        				<tr>
		        				<?php
		        					$permisosSel = array();
		        					if (array_key_exists($mod->idmodulo,$permisosSeleccionados)){
		        				 		$permisosSel = $permisosSeleccionados[$mod->idmodulo];
		        				 	}
		        				 	foreach($permisos as $permiso){
		        				 		$checkPermiso = "";
				        			 	if(in_array($permiso->idpermiso , $permisosSel)){
				        				 	$checkPermiso = " checked ";
 		        						}
			        				 	if($contadorTd == 8){
		        				 			$contadorTd = 1;
			        				 	}
			        			?>
		        					<td class ="tdLeft" style="width:{{$ancho}}%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		        				 		<input class="test-checkbox permisos sub-{{$mod->parent}}-{{$mod->idmodulo}}-c" name = "permisos[]" type="checkbox" 
		        				 		id = "permiso-{{$mod->parent}}-{{$mod->idmodulo}}-{{$permiso->idpermiso}}"  
		        				 		data-toggle="tooltip" data-placement="left" title="{{$asigna2}}"  
		        				 		value = "permiso-{{$mod->parent}}-{{$mod->idmodulo}}-{{$permiso->idpermiso}}" 
		        				 		{{$checkPermiso}}/>
		        				 		{{$permiso->nombre}}										
		        				 	</td>			        				 		
			        			<?php
			        					$contadorTd = $contadorTd + 1;
		        				 }		
		        				?>
							</tr>
							@endif
		        		@endforeach
		        	</table>
		        @endif
		      			</div>
		    		</div>
		  		</div>
		  		<?php $contador++ ?>
			  @endforeach
			</div>
		@endif
		<div class="form-group tdRight">
			<button class="btn btn-success btn-xs" type="submit">
			<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;{{Lang::get('leyendas.actualizar')}}</button>
		</div>
	</div>
</div>
{!!Form::close()!!}
<br/><br/>
@endsection