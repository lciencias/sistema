<script src="{{asset('js/perfilPuesto.js')}}"></script>
<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
<input type="hidden" id="etapa" name="etapa" value="{{$etapa}}">
<input name="idelemento" id="idelemento" type="hidden" value="{{$perfilPuesto->idperfilPuesto}}" />
<input name="idperfilPuesto" id="idperfilPuesto" type="hidden" value="{{$perfilPuestoIdUser}}" />
<input type="hidden" name="preguntas" id="preguntas" >
<input type="hidden" name="resultados" id="resultados" >
<input type="hidden" name="eliminadas" id="eliminadas" >
<input type="hidden" name="opcionesEliminadas" id="opcionesEliminadas" >
<input type="hidden" name="respuestasEliminadas" id="respuestasEliminadas" >
<input type="hidden" name="catResultados"  id="catResultados"  class="resultados">



<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
    	<li {{ ($perfilPuesto->etapa == '0') ? "class=active" : "" }}><a href="#tab_1" data-toggle="tab">{{Lang::get('leyendas.perfilPuesto.generales')}}</a></li>
    	@if($perfilPuesto->idperfil_puesto!= null)
    	<li {{ ($perfilPuesto->etapa == '1') ? "class=active" : "" }}><a href="#tab_2" data-toggle="tab">{{Lang::get('leyendas.perfilPuesto.talentos')}}</a></li>
    	<li {{ ($perfilPuesto->etapa == '2') ? "class=active" : "" }}><a href="#tab_3" data-toggle="tab">{{Lang::get('leyendas.perfilPuesto.pruebas')}}</a></li>
    	<li {{ ($perfilPuesto->etapa == '3') ? "class=active" : "" }}><a href="#tab_4" data-toggle="tab">{{Lang::get('leyendas.perfilPuesto.competencias')}}</a></li>
    	@endif
	</ul>
	 <div class="tab-content">
    	<div class="tab-pane {{($perfilPuesto->etapa == '0') ? 'active' : '' }}" id="tab_1">
        	@include('gestion.perfilPuesto.forms.generalesPerfilPuesto')
        </div>
        <div class="tab-pane {{($perfilPuesto->etapa == '1') ? 'active' : '' }}" id="tab_2">
        	@include('gestion.perfilPuesto.forms.talentosPerfilPuesto')
        </div>
        <div class="tab-pane {{($perfilPuesto->etapa == '2') ? 'active' : '' }}" id="tab_3">
        	@include('gestion.perfilPuesto.forms.pruebasPerfilPuesto')
        </div>
         <div class="tab-pane {{($perfilPuesto->etapa == '3') ? 'active' : '' }}" id="tab_4">
        	@include('gestion.perfilPuesto.forms.competenciasPerfilPuesto')
        </div>
	</div>	
</div>



	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group tdRight">
		@if ($perfilPuesto->nombre != '')
			@if (in_array('Editar',$sessionPermisos))
				<button class="btn btn-success btn-sm"  type="submit" tabindex="10"  onclick="return generaPerfilPuesto();">
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@else
			@if (in_array('Crear',$sessionPermisos))
				<button class="btn btn-success btn-sm"  type="submit" tabindex="10"  onclick="return generaPerfilPuesto();">
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@endif
			<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/gestion/perfilPuesto')}}" tabindex="11" >
			<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
		</div>
	</div>
	

	
	@include('gestion.perfilPuesto.modalAgregaPregunta')
	@include('gestion.perfilPuesto.modalAgregaResultado')	