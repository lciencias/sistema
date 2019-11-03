<script src="{{asset('js/ejercicio.js')}}"></script>

<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
<input name="idelemento" id="idelemento" type="hidden" value="{{$ejercicio->idejercicio}}" />
<input name="idempresa" id="idempresa" type="hidden" value="{{$empresaIdUser}}" />

<div class="rows">
	<div  class="col-md-6">	
		<div class="form-group">
			<label for="idclienteEjercicio" class="control-label">*{{Lang::get('leyendas.cliente')}}</label> 
			<select name="idclienteEjercicio" id="idclienteEjercicio" data-column="1" class="form-control searchs combo">
				<option value="">{{Lang::get('leyendas.seleccione')}}</option>
				@foreach($clientes as $cliente)
						@if ($cliente->idcliente == $ejercicio->idcliente)
							<option selected value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial }}</option>
						@else
							<option value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial}}</option>
						@endif
				@endforeach						
			</select>
		</div>
	</div>
        	
	<div  class="col-md-6">	
		<div class="form-group">
			<label  class="col-lg-4 control-label" for="idTipoEjercicio">*{{Lang::get('leyendas.ejercicio.nombre')}}</label>  
			<select name="idTipoEjercicio" id="idTipoEjercicio" data-column="1" class="form-control searchs combo">
				<option value="">{{Lang::get('leyendas.seleccione')}}</option>
				@foreach($tiposEjercicios as $tipoEje)
						@if ($tipoEje->idtipo_ejercicio == $ejercicio->idtipo_ejercicio)
							<option selected value="{{$tipoEje->idtipo_ejercicio}}">{{$tipoEje->nombre}}</option>
						@else
							<option value="{{$tipoEje->idtipo_ejercicio }}">{{$tipoEje->nombre}}</option>
						@endif
				@endforeach						
			</select>
		</div>
	</div>	
</div>
<div class="rows">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="form-group">
			<label  class="col-lg-4 control-label" for="nombre">*{{Lang::get('leyendas.ejercicio.competencias')}}</label>  
				@if(count($competencias) > 0)   		
				<select name="idCompetenciaAgrega" id="idCompetenciaAgrega"  class="form-control competencia" tabindex="3">
  					<option value="">{{Lang::get('leyendas.seleccione')}}</option>
						@foreach($competencias as $competencia)
							<option value="{{$competencia->idcompetencia}}" >{{$competencia->nombre}}</option>
						@endforeach  				
				</select>
				@endif
		</div>
	</div>	
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="form-group">
			<button id="btnAgregaCompetencia" name="btnAgregaCompetencia" disabled="disabled">{{Lang::get('leyendas.agregar')}}</button>  
		</div>
	</div>	
</div>
<div class="rows">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group">
			<label class="col-lg-3 control-label" for="descripcion">*{{Lang::get('leyendas.ejercicio.competencias')}}</label>
		</div>
	</div>
</div>	
<div class="rows">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="panel-group" id="accordion"   name="accordion"  role="tablist" aria-multiselectable="true">
		 @foreach($ejercicioCompetenciasComportamientos as $ejerCompetenciaComportamientos)	 
		 	<div class="panel panel-default">
	    		<div class="panel-heading" role="tab" id="competencia{{$ejerCompetenciaComportamientos[0]->idcompetencia}}">
	      			<h4 class="panel-title">
	      				      				
	        			<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$ejerCompetenciaComportamientos[0]->idcompetencia}}" aria-expanded="false" aria-controls="collapse{{$ejerCompetenciaComportamientos[0]->idcompetencia}}">
	          				&nbsp;&nbsp;<label for="mods"><b>{{$ejerCompetenciaComportamientos[0]->nombre}}</b></label>
	        			</a>
	        			<button class="quitarCompetencia" id="btnCompetencia-{{$ejerCompetenciaComportamientos[0]->idcompetencia}}">Quitar</button> 
	      			</h4>
	    		</div>
	    		<div id="collapse{{$ejerCompetenciaComportamientos[0]->idcompetencia}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="competencia{{$ejerCompetenciaComportamientos[0]->idcompetencia}}">
	      			<div class="panel-body">
	      				@if(count($ejerCompetenciaComportamientos[1]) > 0)  
	      					<table class="table table-bordered">
		        			@foreach($ejerCompetenciaComportamientos[1] as $comportamientoCompetencia)
		        				<?php 
		        				$checked= "";
		        				if(in_array($comportamientoCompetencia->idcomportamiento, $ejercicioComportamientos)){
		        						$checked = " checked ";
		        					}
		        					 
		        				?>  
		        			
		        				<tr><td class="warning">
		        				<input class="test-checkbox comportamientos" id="comportamiento-{{$ejerCompetenciaComportamientos[0]->idcompetencia}}-{{$comportamientoCompetencia->idcomportamiento}}"
		        				value="comportamiento-{{$ejerCompetenciaComportamientos[0]->idcompetencia}}-{{$comportamientoCompetencia->idcomportamiento}}"  
								data-toggle="tooltip" data-placement="left" {{$checked}}
								name="comportamientos[]" type="checkbox">
		        				<b>{{$comportamientoCompetencia->nombre}}</b></td></tr>
		        			@endforeach
		        			</table>
	      				@endif
	      			</div>
	      		</div>
	      			
		    </div>
		 
		@endforeach
		</div>
		<div class="form-group tdRight">
		@if ($ejercicio->nombre != null)		
			@if (in_array('Editar',$sessionPermisos))
				<button class="btn btn-success btn-sm" type="submit"  id="actualizaEjercicio" tabindex="99">
					<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}
				</button>
			@endif			
		@else
			@if (in_array('Crear',$sessionPermisos))
				<button class="btn btn-success btn-sm" type="submit" id="creaEjercicio" tabindex="99">
					<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}
				</button>
			@endif		
		@endif		
			<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/catalogos/ejercicio')}}" tabindex="100">
				<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}
			</button>
		</div>
	</div>
</div>