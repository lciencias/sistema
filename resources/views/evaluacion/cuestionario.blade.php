@extends ('layouts.home')
@section ('contenido')

<script src="{{asset('js/cuestionario.js')}}"></script>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-5"><h3>{{Lang::get('leyendas.pruebaIrCuestionario')}}</h3></div>
	<div class="col-md-5 tdRight">
		<h3>{{Lang::get('leyendas.iniciaCuestionario')}} {{$horaActual}}</h3>
	</div>
	<div class="col-md-1"></div>
</div>
{!!Form::open(array('url'=>'evaluacion/store','method'=>'POST','autocomplete'=>'off', 'role'=>'form' ))!!}
    {{Form::token()}}
	<input type="hidden" id="valores" name="valores" value="">
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		@php
    	$active   = "";
		$contador = 1;
		$noTabs   = count($preguntas);
    	@endphp
		@if(count($preguntas) > 0)
		<div id="tabs" class="tabs">
		<ul class="nav nav-tabs">
			@foreach($preguntas as $seccion => $pregunta)
				@php
				$active = "";
				if($seccion == 1){
				$active = "class=active";
				}
				@endphp	
			<li {{$active}} ><a data-toggle="tab"  href="#seccion-{{$seccion}}"  class="linkTab" id="tab{{$seccion}}">{{Lang::get('leyendas.seccion')}} {{$seccion}}</a></li>		
			@endforeach
		</ul>
		<input type="hidden" id="noTabs" name="noTabs" value="{{$noTabs}}">
		<div class="tab-content">
			<br /><br />
			@foreach($preguntas as $seccion => $pregunta)
				@php			
				$active = "";
				if($seccion == 1){
					$active = " in active disabled";
				}
				@endphp	
				<div id="seccion-{{$seccion}}" class="tab-pane fade{{$active}} ">
					@foreach($pregunta as $tmp)
						@php
							$idPregunta = $tmp['idpregunta_prueba'];
							$respuestas = $opciones[$idPregunta];
							$width = 100;
							if(count($respuestas) > 0){
								$width = round(100/count($respuestas));
							}
						@endphp	
						<div class="form-group">
							<label for="seccionPregunta-{{$seccion}}" class="tdJustify seccionPregunta-{{$seccion}}">{{$contador}}.- {{$tmp['pregunta']}}</label>
							<div class="row">
							@foreach($respuestas as $resp)								
								<div class="col-md-6">
									<div class="iradio_minimal-blue checked" aria-checked="false" aria-disabled="false" style="position: relative;">
										<input type="radio"  name="pregunta-{{$resp['idpregunta_prueba']}}"  id="p-{{$resp['idprueba']}}-{{$resp['idopcion_pregunta']}}" class="radios-{{$seccion}} opciones">											
										<label class="custom-control-label" for="defaultUnchecked{{$contador}}">{{$resp['opcion']}}</label>
									</div> 									
								</div>
							@endforeach
								<br /><br />
							</div>
						</div>
						@php
							$contador = $contador + 1;
						@endphp
					@endforeach       
				</div>
			@endforeach
		</div>
		</div>
		<div class="col-md-1"></div>
		<br><br>
		<div style="text-align: right;">
		
			<button type="button" class="btn btn-default btn-sm anterior" id="anterior"   >
			<span class="glyphicon glyphicon-backward"></span>&nbsp;{{Lang::get('leyendas.anterior')}}</button>		        

			<button type="button" class="btn btn-default btn-sm siguiente" id="siguiente"  >
			<span class="glyphicon glyphicon-forward"></span>&nbsp;{{Lang::get('leyendas.siguiente')}}</button>		        
			@if(count($preguntas) > 0)
				<button class="btn btn-success  btn-sm" id="guardaEvaluacion" type="submit">
					<span class="glyphicon glyphicon-floppy-save"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif	
			<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/evaluacion/evaluacion')}}" >
			<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>		        
		</div>    	
   	@endif						
	</div>
</div>
{!!Form::close()!!}	
@endsection