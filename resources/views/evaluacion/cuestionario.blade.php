@extends ('layouts.home')
@section ('contenido')
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<p class="tdCenter">{{$titulo}}</p>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		@php
    	$active = "";
    	$contador = 1;
    	@endphp
	@if(count($preguntas) > 0)
	<ul class="nav nav-tabs">
		@foreach($preguntas as $seccion => $pregunta)
			@php
	    	$active = "";
			if($seccion == 1){
			$active = "class=active";
			}
	    	@endphp	
		  <li {{$active}} ><a data-toggle="tab"  href="#seccion{{$seccion}}">{{Lang::get('leyendas.seccion')}} {{$seccion}}</a></li>		
		@endforeach
	</ul>
	<div class="tab-content">
		@foreach($preguntas as $seccion => $pregunta)
			@php			
	    	$active = "";
			if($seccion == 1){
				$active = " in active";
			}
	    	@endphp	
    		<div id="seccion{{$seccion}}" class="tab-pane fade{{$active}}">
    			<div class="table-responsive" id="result">		
        			<table style="border:0px;">
                    	<thead>
                    		<tr>
                    			<th class="tdCenter" data-sorter="false" >{{Lang::get('leyendas.no')}}</th>
                    			<th width="95%" class="tdLeft" data-sorter="false" >{{Lang::get('leyendas.preguntaNombre')}}</th>
                    		</tr>
                    	</thead>
                    	<tbody>        
        					@foreach($pregunta as $tmp)
        						@php
        							$idPregunta = $tmp['idpregunta_prueba'];
									$respuestas = $opciones[$idPregunta];
									$width = 100;
									if(count($respuestas) > 0){
										$width = round(100/count($respuestas));
									}
						    	@endphp	
        						<tr>
                					<td class="tdCenter">{{$contador}}</td>
                					<td class="tdJustify">{{$tmp['pregunta']}}</td>
                					</td>
                				</tr>
                				<tr>
                					<td class="tdCenter"></td>
                					<td class="tdCenter">
                						<table class="table table-bordered">
                    						<tr>
                        						@foreach($respuestas as $resp)
	                        						<td class="tdLeft" style="width:{{$width}}%">
	                        						<input type="radio" name="pregunta{{$resp['idpregunta_prueba']}}-{{$resp['idprueba_resultado']}}">
	                        						{{$resp['opcion']}}</td>
                        						@endforeach
                    						</tr>
                						</table>
										
                					</td>
                				</tr>
                				@php
            	    				$contador = $contador + 1;
                				@endphp
        					@endforeach       
    					</tbody>
    				</table>
    			</div>				
    		</div>
		@endforeach
	</div>
	<br><br>
	<div style="text-align: right;">
    	@if(count($preguntas) > 0)
    		<button class="btn btn-success  btn-sm" id="guardaUsuario" type="submit">
    			<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
    	@endif	
    	<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/evaluacion/evaluacion')}}" >
    	<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>		        
	</div>    	
   	@endif						
	</div>
</div>
@endsection