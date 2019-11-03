<script src="{{asset('js/candidato.js')}}"></script>
<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
<input name="idelemento" id="idelemento" type="hidden" value="{{$candidato->idcandidato}}" />
<input name="idcandidato" id="idcandidato" type="hidden" value="{{$candidatoIdUser}}" />

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
    	<li class="active"><a href="#tab_1" data-toggle="tab">{{Lang::get('leyendas.candidato.datosGenerales')}}</a></li>
    	@if($candidato->idcandidato != null)
    	<li><a href="#tab_2" data-toggle="tab">{{Lang::get('leyendas.candidato.personales')}}</a></li>
    	<li><a href="#tab_3" data-toggle="tab">{{Lang::get('leyendas.candidato.adicionales')}}</a></li>
    	<li><a href="#tab_4" data-toggle="tab">{{Lang::get('leyendas.candidato.experienciasIntereses')}}</a></li>
    	<li><a href="#tab_5" data-toggle="tab">{{Lang::get('leyendas.candidato.formacionHabilidades')}}</a></li>
    	<li><a href="#tab_6" data-toggle="tab">{{Lang::get('leyendas.candidato.cv')}}</a></li>
    	<li><a href="#tab_7" data-toggle="tab">{{Lang::get('leyendas.candidato.documentos')}}</a></li>
    	<li><a href="#tab_7" data-toggle="tab">{{Lang::get('leyendas.candidato.proyectos')}}</a></li>
    	@endif
	</ul>
	 <div class="tab-content">
    	<div class="tab-pane active" id="tab_1">
        	@include('gestion.candidato.forms.generales')
        </div>
        <div class="tab-pane" id="tab_2">
        	@include('gestion.candidato.forms.personales')
        </div>
         <div class="tab-pane" id="tab_3">
        	@include('gestion.candidato.forms.adicionales')
        </div>
        <div class="tab-pane" id="tab_4">
        	@include('gestion.candidato.forms.experienciasIntereses')
        </div>
         <div class="tab-pane" id="tab_5">
        	@include('gestion.candidato.forms.formacionHabilidades')
        </div>
        <div class="tab-pane" id="tab_7">
        	@include('gestion.candidato.forms.documentos')
        </div>
	</div>	
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="form-group tdRight">
	@if ($candidato->nombre != '')
		@if (in_array('Editar',$sessionPermisos))
			<button class="btn btn-success btn-sm" id="guardaCandidato" type="submit" tabindex="37" >
			<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
		@endif
	@else
		@if (in_array('Crear',$sessionPermisos))
			<button class="btn btn-success btn-sm" id="guardaCandidato" type="submit" tabindex="37" >
			<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
		@endif
	@endif
		<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/gestion/candidato')}}" tabindex="38" >
		<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
	</div>
</div>
