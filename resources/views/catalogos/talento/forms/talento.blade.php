
<input type="hidden" id="idmodulo" name="idmodulo" value="{{$idModulo}}">
<input name="idelemento" id="idelemento" type="hidden" value="{{$talento->idtalento}}" />
<input name="idtalento" id="idtalento" type="hidden" value="{{$talentoIdUser}}" />
	
	<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">*{{Lang::get('leyendas.talento.nombre')}}</label>
				<input id="nombreTalento" name="nombreTalento"  class="form-control required" maxlength="40" value="{{$talento->nombre}}" tabindex="1" >
    	    </div>
    	</div>
    	
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="definicionTalento" class="col-md-4 control-label">*{{Lang::get('leyendas.talento.definicion')}}</label>
				<input type="text" id="definicionTalento" name="definicionTalento" class="form-control required" maxlength="1000"  value="{{$talento->definicion}}" tabindex="2" >
    	    </div>
    	</div>
    </div>
    
 
    

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group tdRight">
		@if ($talento->nombre != '')
			@if (in_array('Editar',$sessionPermisos))
				<button class="btn btn-success btn-sm" id="guardaTalento" type="submit" tabindex="10" >
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@else
			@if (in_array('Crear',$sessionPermisos))
				<button class="btn btn-success btn-sm" id="guardaTalento" type="submit" tabindex="10" >
				<span class="fa fa-floppy-o"></span>&nbsp;{{Lang::get('leyendas.guardar')}}</button>
			@endif
		@endif
			<button type="button" class="btn btn-danger btn-sm cancelaRegistro"  id="{{url('/catalogos/talento')}}" tabindex="11" >
			<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cancelar')}}</button>
		</div>
	</div>
