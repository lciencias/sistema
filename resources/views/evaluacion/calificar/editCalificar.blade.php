@extends ('layouts.home')
@section ('contenido')
	{!!Form::model($candidatoProyectoEjercicio,['method'=>'post','id'=>'validateFormCalificar','data-toggle'=>'validator', 'role'=>'form',
	'url'=>['evaluacion.calificar.update',Crypt::encrypt($idProyectoEjercicio)]])!!}
	{{Form::token()}}
	<script src="{{asset('js/calificar.js')}}"></script>
	<input type="hidden" id="id"  name="id"  value="{{$idProyectoEjercicio}}">
	<input type="hidden" id="estatus"  name="estatus"  value="{{$idEstatus}}">
	<?php 
    	$read = "";    	
    	$temIdRes = $temIdDet = $temCal = $temSel = $temIdRes = $temIdDet = 0;
    	if($idEstatus == 3){
    		$read = " disabled ";
    	}
	?>
		<div class="row" style="position:fixed;margin-left:10px;margin-right: 10px;background-color:#fff;top:0px;z-index:1000000000;">			
			<div class="col-md-12" >
				<h4>{{Lang::get('leyendas.calificar.titulo')}}</h4>
        		<div class="row">
                   	<div class="col-md-6 col-sm-6 col-xs-12">
                       	<div class="info-box">
                           	<span class="info-box-icon bg-aqua"><i class="glyphicon glyphicon-user"></i></span>
                           	<div class="info-box-content">
		           				<span class="info-box-text">{{$candidatoProyectoEjercicio['nombre']}} {{$candidatoProyectoEjercicio['paterno']}} {{$candidatoProyectoEjercicio['materno']}}</span><br>
        		                   <span class="info-box-text">{{Lang::get('leyendas.calificar.gerente')}}</span>
                            </div>
						</div>
                	</div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    	<div class="info-box">
                        	<div class="info-box-content">
                            	<span class="info-box-text">{{Lang::get('leyendas.calificar.ejercicio')}} {{$candidatoProyectoEjercicio['ejercicio']}}</span><br>
                              	<span class="info-box-text">{{$cliente['nombre_comercial']}}</span>
                            </div>
                    	</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="overflow-y: auto;overflow-x: hidden;max-height: 650px;margin-top:10px;">
			<div class="col-md-12">        		
        		<div class="content">
            		<div class="observaciones">
            			<h4>{{Lang::get('leyendas.calificar.observaciones')}}</h4>
            			<textarea {{$read}} class="form-control" rows="3" style="width:100%;" name="observaciones" id="observaciones">{{$candidatoProyectoEjercicio['observaciones']}}</textarea>
            			<hr>
            		</div>
            		<h4>{{Lang::get('leyendas.calificar.competencia')}}</h4>
            		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            			@foreach($registros as $id => $dataCompetencia)
    						<div class="panel panel-default">
                            	<div class="panel-heading" role="tab" id="heading{{$id}}">
                              		<h4 class="panel-title">
                                		<a class="linkBam" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$id}}" aria-expanded="true" aria-controls="collapse{{$id}}">
                                  			{{$competencias[$id]}}
                                		</a>
                              		</h4>
                            	</div>
                            	<div id="collapse{{$id}}" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading{{$id}}">
                              		<div class="panel-body">
                              			<div class="table-responsive">
                                  			<table class="table-striped table-bordered">
                                  				<thead>
                                  					<tr>
                                  						<td data-sorter="false" data-filter="false">{{Lang::get('leyendas.calificar.comportamiento')}}</td>
                                  						<td data-sorter="false" data-filter="false" colspan="{{$totalCalific + 1}}" >{{Lang::get('leyendas.calificar.texto')}}</td>                                  						
                                  					</tr>
                                  				</thead>
                                  				<tbody>
    											@foreach($dataCompetencia  as $data)
    												<?php
    												$idClave = $id."-".$data['idcomportamiento'];
    												$temIdRes = $temIdDet = 0;
    												if(count($calificaciones)> 0 && array_key_exists($idClave, $calificaciones)){
    												    $temCal = $calificaciones[$idClave]['calificacion'];
    												    $temSel = $calificaciones[$idClave]['idcalificacion_comportamiento'];
    												    $temIdRes = $calificaciones[$idClave]['idresultado_candidato_ejercicio'];
    												    $temIdDet = $calificaciones[$idClave]['iddetalle_resultado_candidato_ejercicio'];    												    
    												}
    												?>
    												<tr class="comportamientos">
    												<td>{{$data['comportamiento']}}</td>
    												@foreach($data['calif'] as $dato)
    													<td class="tdCenter">
    													{{$dato['calificacion_texto']}}<br>
    													<?php 
    													$tmpChecked = "";
    													if($dato['idcalificacion_comportamiento'] == $temSel){
    													    $tmpChecked = " checked ";
    													}
    													?>
    													<input {{$read}} type="radio" {{$tmpChecked}} class="radios selectorRadios" name="r-{{$idProyectoEjercicio}}-{{$id}}-{{$data['idcomportamiento']}}" id="r-{{$idProyectoEjercicio}}-{{$id}}-{{$data['idcomportamiento']}}-{{$dato['idcalificacion_comportamiento']}}" value="{{$dato['idcalificacion_comportamiento']}}">
    													</td>
    												@endforeach
    												<td style="width:100px;">
    													<select {{$read}}  name="s-{{$idProyectoEjercicio}}-{{$id}}-{{$data['idcomportamiento']}}"
    													id="s-{{$idProyectoEjercicio}}-{{$id}}-{{$data['idcomportamiento']}}" class="form-control calificar"    													
    													style="width:100px;font-size:11px;">
    													@if($idEstatus == 0)
    														<option value="-1">Seleecione</option>
    													@else
															<?php							
															$valorSeleccionado = -1;
															if(count($calificaciones)> 0 && array_key_exists($idClave, $calificaciones)){
															    $valorDefault = $calificaciones[$idClave];
															    $valorSeleccionado = $valorDefault['calificacion'];
															}
															
															if(count($caliNumerica)> 0 && array_key_exists($idClave, $caliNumerica)){															
    															$califTemporal = $caliNumerica[$idClave];
			     												if($califTemporal != null &&  count($califTemporal)> 0){
				    											    if(count($califTemporal) > 1){
															?>
																	<option value="-1">Seleecione</option>
															<?php      															       
	       														    }		
			     												    foreach($califTemporal as $califData){
			     												        $selected = "";
			     												        if($califData['calificacion'] == $valorSeleccionado){
			     												            $selected = " selected ";
			     												        }
				    											    ?>
																    <option {{$selected}} value="{{$califData['calificacion']}}-{{$califData['idresultado_candidato_ejercicio']}}-{{$califData['iddetalle_resultado_candidato_ejercicio']}}">{{$califData['calificacion']}}</option>
																    <?php 
							     								    }
			     												}
															}else{
															?>
															<option value="-1">Seleecione</option>
															<?php 
															}
															?>
    													@endif
    												</select></td>
    												</tr>
                                    			@endforeach
                                    			</tbody>
                                    		</table>
                                    	</div>
                            		</div>
                            	</div>
    	                	</div>
    	                @endforeach
    				</div>
				</div>
				<hr>
				<div class="tdRight">
				<?php 
				if($idEstatus < 3){
				?>
				    <button class="btn btn-success btn-sm" id="guardaCalifica" type="submit" tabindex="37" >
				    <span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
				<?php
                }				
				?>
					<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/evaluacion/calificar')}}" tabindex="38" >				
					<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
					<br><br>
				</div>        		
			</div>
		</div>
	{!!Form::close()!!}
@endsection