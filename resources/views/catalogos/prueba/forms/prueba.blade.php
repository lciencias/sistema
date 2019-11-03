<script src="{{asset('js/pruebas.js')}}"></script>
<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
<input type="hidden" id="etapa" name="etapa" value="{{$etapa}}">
<input name="idelemento" id="idelemento" type="hidden" value="{{$prueba->idprueba}}" />
<input name="idprueba" id="idprueba" type="hidden" value="{{$pruebaIdUser}}" />
<input type="hidden" name="preguntas" id="preguntas" >
<input type="hidden" name="resultados" id="resultados" >
<input type="hidden" name="interpretaciones" id="interpretaciones" >
<input type="hidden" name="eliminadas" id="eliminadas" >
<input type="hidden" name="opcionesEliminadas" id="opcionesEliminadas" >
<input type="hidden" name="respuestasEliminadas" id="respuestasEliminadas" >
<input type="hidden" name="catResultados"  id="catResultados"  class="resultados">



<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
    	<li {{ ($prueba->etapa == '0') ? "class=active" : "" }}><a href="#tab_1" data-toggle="tab">{{Lang::get('leyendas.prueba.generales')}}</a></li>
    	@if($prueba->idprueba!= null)
    	<li {{ ($prueba->etapa == '1') ? "class=active" : "" }}><a href="#tab_2" data-toggle="tab">{{Lang::get('leyendas.prueba.resultados')}}</a></li>
    	<li {{ ($prueba->etapa == '2') ? "class=active" : "" }}><a href="#tab_3" data-toggle="tab">{{Lang::get('leyendas.prueba.preguntas')}}</a></li>
    	<li {{ ($prueba->etapa == '3') ? "class=active" : "" }}><a href="#tab_4" data-toggle="tab">{{Lang::get('leyendas.prueba.interpretacion')}}</a></li>
    	@endif
	</ul>
	 <div class="tab-content">
    	<div  class="tab-pane {{($prueba->etapa == '0') ? 'active' : '' }}" id="tab_1">
        	@include('catalogos.prueba.forms.generalesPrueba')
        </div>
        <div class="tab-pane {{($prueba->etapa == '1') ? 'active' : '' }}" id="tab_2">
        	@include('catalogos.prueba.forms.resultadosPrueba')
        </div>
         <div class="tab-pane {{($prueba->etapa == '2') ? 'active' : '' }}" id="tab_3">
        	@include('catalogos.prueba.forms.preguntasPrueba')
        </div>
        <div class="tab-pane {{($prueba->etapa == '3') ? 'active' : '' }}" id="tab_4">
        	@include('catalogos.prueba.forms.interpretacionPrueba')
        </div>
	</div>	
</div>



	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group tdRight">
		@if ($prueba->nombre != '')
			@if (in_array('Editar',$sessionPermisos))
				<button class="btn btn-success btn-sm"  type="submit" tabindex="10"  onclick="return generaPrueba();">
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@else
			@if (in_array('Crear',$sessionPermisos))
				<button class="btn btn-success btn-sm"  type="submit" tabindex="10"  onclick="return generaPrueba();">
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@endif
			<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/catalogos/prueba')}}" tabindex="11" >
			<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
		</div>
	</div>
	

	
	@include('catalogos.prueba.modalAgregaPregunta')
	@include('catalogos.prueba.modalAgregaResultado')	