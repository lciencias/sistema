


<div class="box box-solid">
	<div class="box-header">
    	<div class="pull-right box-tools">
        	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
        <i class="fa fa-minus"></i></button>
	</div>
    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.datosMedicos')}}</h3>
	</div>
	
	 <div class="box-body">
		<div class="row">
			<div class="col-md-9">
				<div class="form-group">
					<label for="calleCandidato" class="control-label">{{Lang::get('leyendas.candidato.alergias')}}</label>
					<input type="text" tabindex="21" id="calleCandidato" value="{{$direccion->calle}}"
						name="calleCandidato" maxlength="100"
						class="form-control alfa">
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<label for="tipoSangreCandidato" class="control-label">{{Lang::get('leyendas.candidato.tipoSangre')}}</label>
					<select name="tipoSangreCandidato" id="tipoSangreCandidato" data-column="5" class="form-control searchs combo"  style="width:100%;">
					<option value="">Seleccione</option>
					@foreach($catTipoSangre as $key => $value)
						@if ($key == $candidato->tipo_sangre)
							<option selected value="{{$key}}">{{$value}}</option>
						@else
							<option value="{{$key}}">{{$value}}</option>
						@endif
					@endforeach						
				</select>
				</div>
			</div>
		</div>
		
	</div>
</div>



<div class="box box-solid">
	<div class="box-header">
    	<div class="pull-right box-tools">
        	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
        <i class="fa fa-minus"></i></button>
	</div>
    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.enCasoEmergencia')}}</h3>
	</div>
	
	 <div class="box-body">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label for="parentescoCandidato" class="control-label">{{Lang::get('leyendas.candidato.parentesco')}}</label>
					<select name="parentescoCandidato" id="parentescoCandidato" data-column="5" class="form-control searchs combo"  style="width:100%;">
    					<option value="">Seleccione</option>
    					@foreach($catParentescos as $key => $value)
    						@if ($key == $candidato->parentesco)
    							<option selected value="{{$key}}">{{$value}}</option>
    						@else
    							<option value="{{$key}}">{{$value}}</option>
    						@endif
    					@endforeach						
    				</select>
					
				</div>
			</div>
			
			<div class="col-md-3">
    			<div class="form-group">
    				<label for="tipoSangreCandidato" class="control-label">{{Lang::get('leyendas.nombre')}}</label>
    				<input type="text" tabindex="21" id="calleCandidato" value="{{$direccion->calle}}"
    					name="calleCandidato" maxlength="100"
    					class="form-control alfa">
    			</div>
    		</div>
    		
    		<div class="col-md-3">
    			<div class="form-group">
    				<label for="tipoSangreCandidato" class="control-label">{{Lang::get('leyendas.candidato.telefono')}}</label>
    				<input type="text" tabindex="21" id="calleCandidato" value="{{$direccion->calle}}"
    					name="calleCandidato" maxlength="100"
    					class="form-control alfa">
    			</div>
    		</div>
		</div>
		
		
		
	</div>
</div>



