<script src="{{asset('js/ajax.js')}}"></script>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<?php $contador = 1;?>
			<?php $columnas = 5;?>
			<?php $ancho = round(100/$columnas) ?>
			<?php $contadorTd = 1 ?>
			<?php $checkModulo = $checkModuloS = "";?>
			<?php $asigna  = "Oprimir para quitar todo el módulo al usuario";?>		
			<?php $asigna2 = "Oprimir para asignar o desasignar permisos al módulo";?>			
			@if(count($parents) > 0)
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">			
       			@foreach($parents as $menu)
       				@if (in_array($menu->idmodulo,$registrados))
						<div class="panel panel-default">
			    			<div class="panel-heading" role="tab" id="heading{{$contador}}">
			      				<h4 class="panel-title">
									<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$contador}}" aria-expanded="true" aria-controls="collapse{{$contador}}">   			
		        						<strong>{{$menu->nombre}}</strong>
		        					</a>	      						
		        				</h4>
		        			</div>
		        			<div id="collapse{{$contador}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$contador}}">
			      				<div class="panel-body">
			      					@if(count($childrens) > 0)   
			      						<?php $contadorTd = 1 ?>    		      					
			      						<table class="table table table-striped"">
			        					@foreach($childrens as $submenu)
			        						@if($menu->idmodulo == $submenu->parent)	
			        							@if (in_array($submenu->idmodulo,$registrados))		        						
				        							<tr>
				        								<td>
				        									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															{{$submenu->nombre}}
				        								</td>
				        								<td>
															<button type="button" data-toggle="tooltip" data-placement="left" title="{{$asigna}}" id="modulo-{{$submenu->parent}}-{{$submenu->idmodulo}}" class="btn btn-danger btn-sm eliminaModulo">
		 														<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;Eliminar
															</button>
															<button type="button" data-toggle="tooltip" data-placement="left" title="{{$asigna2}}" id="permiso-{{$submenu->parent}}-{{$submenu->idmodulo}}" class="btn btn-warning btn-sm verPermisos">
		  														<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;Permisos
		  													</button>
				        								</td>
				        							</tr>
				        							<?php $contadorTd = $contadorTd + 1;?>
				        						@endif		
			        						@endif
										@endforeach
										</table>
									@endif		      						
			      				</div>
			      			</div>
			      		</div>
		      			<?php $contador++ ?>
		      		@endif				
       			@endforeach       			
       			</div>
			@endif
		</div>
		@if( count($noRegistrados) > 0 )
			<div style="text-align:right;">			
				<button type="button" id="agregaModulos" name="agregaModulos" class="btn btn-success btn-sm">Agregar M&oacute;dulos</button>
				<br>			
			</div>
		@endif
	</div>
</div>
<hr>
<input type="hidden" id="submodulosSeleccionados" name="submodulosSeleccionados" value="{{ implode(',' , $registrados)}}">
<input type="hidden" id="submodulosNoSeleccionados" name="submodulosNoSeleccionados" value="{{ implode(',' , $noRegistrados)}}">
<input type="hidden" id="submodulosSeleccionadosPermisos" name="submodulosSeleccionadosPermisos" value="{{$cadena}}">
@include('seguridad.usuario.agregaModulo')
