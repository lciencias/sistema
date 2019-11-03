<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="myModalAgregar" data-backdrop="static">
	<div class="modal-dialog" role="document">
  		<div class="modal-content" >
   			<div class="modal-header">
       			<h4 class="modal-title" id="myModalLabel">Agregar Módulo al Usuario</h4>
   			</div>
   			<div class="modal-body" id="buffer">
   				<div class="table-responsive ">
   					<input type="hidden" name="idModuloA" id="idModuloA" value="0">
					<input type="hidden" name="idParentA" id="idParentA" value="0">   				
					<?php $contador = 1;?>
					<?php $asigna  = "Al oprimir el botón permisos, se mostrarán los permisos asignados al módulo.";?>		
					<?php $asigna2 = "Al oprimir el botón añadir, el sistema asignará el módulo al usuario.";?>

					@if(count($parents) > 0 && count($noRegistrados) > 0)
						<div class="panel-group" id="accordionAgregar" role="tablist" aria-multiselectable="true">			
	       				@foreach($parents as $menu)
							<div class="panel panel-default">
					    		<div class="panel-heading" role="tab" id="headingA{{$contador}}">
					      			<h4 class="panel-title">
										<a role="button" data-toggle="collapse" data-parent="#accordionAgregar" href="#Acollapse{{$contador}}" aria-expanded="true" aria-controls="Acollapse{{$contador}}">   			
			        						<strong>{{$menu->nombre}}</strong>
			        					</a>	      						
			        				</h4>
			        			</div>
			        			<div id="Acollapse{{$contador}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingA{{$contador}}">
				      				<div class="panel-body">
				      				@if(count($childrens) > 0)      		      					
			      						<table class="table table table-striped"">
			        					@foreach($childrens as $submenu)		
			        						@if($menu->idmodulo == $submenu->parent)	
			        							@if(in_array($submenu->idmodulo,$noRegistrados))
				        							<tr>
				        								<td style="width:60%;">
															{{$submenu->nombre}}
				        								</td>
				        								<td style="width:40%;">															
															<button class="btn btn-default btn-sm ver-permisos" type="button" data-toggle="collapse" data-target="#collapseExample{{$submenu->idmodulo}}" id="per-{{$submenu->idmodulo}}" aria-expanded="false" aria-controls="collapseExample">
															  Permisos
															</button>
															&nbsp;&nbsp;
															<button type="button" data-toggle="tooltip" data-placement="left" title="{{$asigna2}}" id="modulo-{{$submenu->parent}}-{{$submenu->idmodulo}}" class="btn btn-success btn-sm anadeModulo">
		 													<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;A&ntilde;adir
															</button>																			        																							
															<div class="collapse" id="collapseExample{{$submenu->idmodulo}}">
  																<div class="well">
  																<?php 
  																if(array_key_exists($submenu->idmodulo,$permisos) && array_key_exists($submenu->idmodulo,$permisosN)){
  																	$tmp    = $permisos[$submenu->idmodulo];
  																	$tmpNom = $permisosN[$submenu->idmodulo];
  																	$tmpPer = array();
  																	if(array_key_exists($submenu->idmodulo,$permisosPerfil)){
  																		$tmpPer = $permisosPerfil[$submenu->idmodulo];
  																	}
  																	if(count($tmp) > 0){
  																		foreach($tmp as $idTmp => $idPermisoDefault){
  																			if(in_array($idPermisoDefault, $tmpPer)){
  																				echo'<input type="checkbox" name="p-'.$submenu->idmodulo.'-'.$idPermisoDefault.'" checked readonly id="p-'.$submenu->idmodulo.'-'.$idPermisoDefault.'" 
																				class="permisosModulos-'.$submenu->idmodulo.'">&nbsp;&nbsp;'.$tmpNom[$idTmp].'<br>';
  																			}else{
  																				echo'<input type="checkbox" name="p-'.$submenu->idmodulo.'-'.$idPermisoDefault.'" id="p-'.$submenu->idmodulo.'-'.$idPermisoDefault.'"
																				class="permisosModulos-'.$submenu->idmodulo.'">&nbsp;&nbsp;'.$tmpNom[$idTmp].'<br>';  																				
  																			}
  																		}  																		
  																	}
  																}
  																?>
  																</div>
															</div>
														</td>
				        							</tr>
				        						@endif		
				        					@endif
										@endforeach
										</table>
									@endif		      										      				
				      				</div>
				      			</div>
				      		</div>
				      		<?php $contador++;?>
				      	@endforeach
				      </div>
				    @else
				    <p style="text-align: center;font-size:12px;font-weight: bold;">Sin m&oacute;dulos para asignar</p>  
					@endif
				</div>
			</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default cerrarModal" data-dismiss="modal" id="cerrarModal">Cerrar</button>        
      		</div>
    	</div>
  	</div>
</div>