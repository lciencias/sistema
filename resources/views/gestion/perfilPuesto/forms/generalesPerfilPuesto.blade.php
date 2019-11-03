<div class="box box-solid">
	<div class="box-header">
		<div class="pull-right box-tools">
			<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
        <i class="fa fa-minus"></i></button>
		</div>
    	<h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.datosGenerales')}}</h3>
	</div>
 	<div class="box-body">
		<div class="row">
    		<div  class="col-md-4">	
        		<div class="form-group">
        			<label for="idclientePerfilPuesto" class="control-label">*{{Lang::get('leyendas.cliente')}}</label> 
        			<select name="idclientePerfilPuesto" id="idclientePerfilPuesto" data-column="1" class="form-control searchs combo">
        				<option value="">{{Lang::get('leyendas.seleccione')}}</option>
        				@foreach($clientes as $cliente)
        						@if ($cliente->idcliente == $perfilPuesto->idcliente)
        							<option selected value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial }}</option>
        						@else
        							<option value="{{$cliente->idcliente }}">{{$cliente->nombre_comercial}}</option>
        						@endif
        				@endforeach						
        			</select>
        		</div>
        	</div>
        	
        	<div  class="col-md-4">	
        		<div class="form-group">
        			<label for="nombrePerfilPuesto" class="col-md-2 control-label">*{{Lang::get('leyendas.puesto')}}</label>
				<input id="nombrePerfilPuesto" name="nombrePerfilPuesto"  class="form-control required" maxlength="50" value="{{$perfilPuesto->nombre}}" tabindex="1" >
        		</div>
        	</div>
        	
        	<div  class="col-md-4">	
        		<label for="nivelPerfilPuesto" class="col-md-2 control-label">*{{Lang::get('leyendas.nivel')}}</label>
        		<select name="nivelPerfilPuesto" id="nivelPerfilPuesto" data-column="1" class="form-control searchs combo">
    				<option value="">{{Lang::get('leyendas.todos')}}</option>
    				@foreach($catNiveles as $key => $value)
							@if ($key == $perfilPuesto->nivel_puesto)
								<option selected value="{{$key}}">{{$value}}</option>
							@else
								<option value="{{$key}}">{{$value}}</option>
							@endif
					@endforeach						
    			</select>
    		</div>
        	
        </div>
    
    
	<div class="row">
    	<div class="col-md-12">
            <div class="form-group">
                <label for="funcionGenericaPerfilPuesto" class="col-md-4 control-label">{{Lang::get('leyendas.perfilPuesto.funcionGenerica')}}</label>
				<textarea rows="3" cols="50" maxlength="1000"   id="funcionGenericaPerfilPuesto" name="funcionGenericaPerfilPuesto" class="form-control required" tabindex="2" > {{$perfilPuesto->funcion_generica}}</textarea> 
    	    </div>
    	</div>
	</div>
	
	<div class="row">
    	<div class="col-md-12">
            <div class="form-group">
                <label for="funcionesGeneralesPerfilPuesto" class="col-md-4 control-label">{{Lang::get('leyendas.perfilPuesto.funcionesGenerales')}}</label>
				<textarea rows="3" cols="50" maxlength="1000"   id="funcionesGeneralesPerfilPuesto" name="funcionesGeneralesPerfilPuesto" class="form-control required" tabindex="2" > {{$perfilPuesto->funciones_generales}}</textarea> 
    	    </div>
    	</div>
	</div>
	
	
	<div class="row">
    	<div class="col-md-12">
            <div class="form-group">
                <label for="actividadesPerfilPuesto" class="col-md-4 control-label">{{Lang::get('leyendas.perfilPuesto.actividades')}}</label>
				<textarea rows="3" cols="50" maxlength="1000"   id="actividadesPerfilPuesto" name="actividadesPerfilPuesto" class="form-control required" tabindex="2" > {{$perfilPuesto->actividades}}</textarea> 
    	    </div>
    	</div>
	</div>
	
</div>    

</div> 