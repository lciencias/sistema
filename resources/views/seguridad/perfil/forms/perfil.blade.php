<script src="{{asset('js/perfil.js')}}"></script>
<div class="rows">
<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
<input name="idelemento" id="idelemento" type="hidden" value="{{$perfil->idperfil}}" />
<input name="idempresa" id="idempresa" type="hidden" value="{{$empresaIdUser}}" />
<input type="hidden" id="noPermisos" name="noPermisos" value="{{$noPermisos}}">
@if($isAdmin)
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="form-group">
			<label class="col-lg-3 control-label" for="empresa">{{Lang::get('leyendas.empresa')}}</label>
			<select name="empresa"  id="empresa" tabindex="1"  class="form-control"  {{$disabled}}> 
				<option value="" selected>Seleccione</option>
				@foreach($empresas as $emp)
					@if($emp->idempresa == $perfil->idempresa)
						<option value="{{$emp->idempresa}}" selected>{{$emp->nombre}}</option>
					@else
			    		<option value="{{$emp->idempresa}}">{{$emp->nombre}}</option>
			    	@endif
				@endforeach
				</select>
		</div>
	</div>
@else
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group">
		<label class="col-lg-3 control-label" for="empresa"></label>
			<input type="hidden" name="empresa"  id="empresa" value="{{$empresaIdUser}}">
		</div>
	</div>
@endif
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="form-group">
			<label  class="col-lg-4 control-label" for="nombre">*{{Lang::get('leyendas.perfil.nombre')}}</label>  
				<input type="text" name="nombre" maxlength="50" class="form-control required letras" value="{{$perfil->nombre}}" tabindex="2">
		</div>
	</div>	
</div>
<div class="rows">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="form-group">
			<label class="col-lg-3 control-label" for="descripcion">{{Lang::get('leyendas.descripcion')}}</label> 
			<textarea rows="3" cols="50" maxlength="2000"   name="descripcion" class="form-control" tabindex="3"
				>{{$perfil->descripcion}}</textarea>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
	</div>
</div>
<div class="rows">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group">
			<label class="col-lg-3 control-label" for="descripcion">{{Lang::get('leyendas.perfil.menu')}}</label>
		</div>
	</div>
</div>	
<div class="rows">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<?php $contador = 1 ?>
		<?php $columnas = 5;?>
		<?php $ancho = round(100/$columnas) ?>
		<?php $contadorTd = 1 ?>
		@if(count($modulosP) > 0)
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			  @foreach($modulosP as $modulo)	  
			  	<div class="panel panel-default">
		    		<div class="panel-heading" role="tab" id="heading{{$contador}}">
		      			<h4 class="panel-title">
		      				<?php
		      					$checkModulo = "";
			      				if(in_array($modulo->idmodulo, $perfilModulo)){
				      				$checkModulo = " checked ";
				      			}				      			 
		      				?>		      				
							<input class="test-checkbox parents p-{{$modulo->idmodulo}}" id="p-{{$modulo->idmodulo}}"  
								data-toggle="tooltip" data-placement="left" title="{{Lang::get('leyendas.perfil.asigna1')}}"
								name="modulosParents[]" type="checkbox" value="{{$modulo->idmodulo}}" {{$checkModulo}}>		      			
		        			<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$contador}}" aria-expanded="false" aria-controls="collapse{{$contador}}">
		          				&nbsp;&nbsp;<label for="mods"><b>*&nbsp;&nbsp;{{$modulo->nombre}}</b></label></a>
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
		        					$permisosSel = array();
		        					if (array_key_exists($mod->idmodulo,$perfilModuloPermisos)){
		        						$permisosSel = $perfilModuloPermisos[$mod->idmodulo];
		        					}
		        					$contadorTd = 1;
		        					$checkModuloParent= "";
		        					if(in_array($mod->idmodulo, $perfilModulo)){
		        						$checkModuloParent = " checked ";
		        					}
		        					 
		        				?>  
    	
		        				<tr><td class="warning" colspan="{{$columnas}}">
		        				<input class="test-checkbox parents_parents sub-{{$modulo->idmodulo}}" id="sub-{{$modulo->idmodulo}}-{{$mod->idmodulo}}"  
								data-toggle="tooltip" data-placement="left" title="{{Lang::get('leyendas.perfil.asigna1')}}"
								name="sub-{{$modulo->idmodulo}}-{{$mod->idmodulo}}" type="checkbox" value="{{$modulo->idmodulo}}-{{$mod->idmodulo}}" {{$checkModuloParent}}>
		        				<b>{{$mod->nombre}}</b></td></tr>
		        				<tr>			
								@foreach($permisos as $permiso)									
									@if($permiso->idmodulo == $mod->idmodulo)
																			
										<?php 
											
											$checkPermiso = "";	
											if(in_array($permiso->idpermiso,$permisosSel)){
												$checkPermiso = " checked ";
											}										
											?>
											<?php 
												if($contadorTd == 6){
													$contadorTd = 1;
											?>					
												</tr>												
												<tr>
											<?php 
												}
											?>				
												<td class ="tdLeft" style="width:{{$ancho}}%;">
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													   <input class="test-checkbox permisos sub-{{$mod->parent}}-{{$mod->idmodulo}}-c" 
													   name  = "permisos[]" type="checkbox"
													   id    = "permiso-{{$mod->parent}}-{{$mod->idmodulo}}-{{$permiso->idpermiso}}"  
													   data-toggle="tooltip" data-placement="left" title="{{Lang::get('leyendas.perfil.asigna2')}}"  
													   value = "permiso-{{$mod->parent}}-{{$mod->idmodulo}}-{{$permiso->idpermiso}}" 
													   {{$checkPermiso}}/>
													{{$permiso->nombre}}										
												</td>
												<?php $contadorTd = $contadorTd + 1;?>
										@endif
									@endforeach
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
		@if ($perfil->nombre != null)		
			@if (in_array('Editar',$sessionPermisos))
				<button class="btn btn-success btn-sm" type="submit"  id="actualizaPerfil" tabindex="99">
					<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}
				</button>
			@endif			
		@else
			@if (in_array('Crear',$sessionPermisos))
				<button class="btn btn-success btn-sm" type="submit" id="creaPerfil" tabindex="99">
					<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}
				</button>
			@endif		
		@endif		
			<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/seguridad/perfil')}}" tabindex="100">
				<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}
			</button>
		</div>
	</div>
</div>