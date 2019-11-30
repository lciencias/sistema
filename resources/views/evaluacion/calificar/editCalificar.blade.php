@extends ('layouts.home')
@section ('contenido')
	{!!Form::model($candidatoProyectoEjercicio,['method'=>'post','id'=>'validateFormCalificar','data-toggle'=>'validator', 'role'=>'form',
	'url'=>['evaluacion.calificar.update',Crypt::encrypt($idProyectoEjercicio)]])!!}
	{{Form::token()}}
	<script src="{{asset('js/calificar.js')}}"></script>
	<input type="hidden" id="id"  name="id"  value="{{$idProyectoEjercicio}}">
		<div class="row" style="margin-left:10px;margin-right: 10px;">			
			<div class="col-md-12" >
				<div style="position: fixed;">
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
        		<div class="content" style="margin-top:130px;">
            		<div class="observaciones">
            			<h4>{{Lang::get('leyendas.calificar.observaciones')}}</h4>
            			<textarea class="form-control" rows="3" style="width:100%;" name="observaciones" id="observaciones"> </textarea>
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
    												<tr class="comportamientos">
    												<td>{{$data['comportamiento']}}</td>
    												@foreach($data['calif'] as $dato)
    													<td class="tdCenter">
    													{{$dato['calificacion_texto']}}<br>
    													<input type="radio" class="radios" name="radio-{{$id}}" id="r-{{$id}}-{{$data['idcomportamiento']}}-{{$data['idtipo_ejercicio_cliente_comportamiento']}}">
    													</td>
    												@endforeach
    												<td style="width:100px;">
    													<select name="s-{{$id}}-{{$data['idcomportamiento']}}-{{$data['idtipo_ejercicio_cliente_comportamiento']}}"
    													id="s-{{$id}}-{{$data['idcomportamiento']}}-{{$data['idtipo_ejercicio_cliente_comportamiento']}}" class="form-control calificar"
    													style="width:100px;font-size:11px;">
    												@if($data['combo'] != null)
    													@if( count($data['combo']) > 1)
    													<option value="0">Seleccione</option>
    													@endif
    													@foreach($data['combo'] as $kk => $vv)
    													<option value="{{$kk}}">{{$vv}}</option>
    													@endforeach
    												@else
    													<option value="{{$kk}}">0</option>
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
					<button class="btn btn-success btn-sm" id="guardaCalifica" type="submit" tabindex="37" >
					<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
					<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/evaluacion/calificar')}}" tabindex="38" >				
					<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
					<br><br>
				</div>        		
			</div>
		</div>
	{!!Form::close()!!}
@endsection