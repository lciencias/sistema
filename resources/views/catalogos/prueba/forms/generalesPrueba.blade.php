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
    	
        	<div  class="col-md-3">	
        		<div class="form-group">
        			<label for="nombrePrueba" class="col-md-2 control-label">*{{Lang::get('leyendas.prueba.nombre')}}</label>
				<input id="nombrePrueba" name="nombrePrueba"  class="form-control required" maxlength="50" value="{{$prueba->nombre}}" tabindex="1" >
        		</div>
        	</div>
        </div>
    
    
	<div class="row">
    	
    	<div class="col-md-12">
            <div class="form-group">
                <label for="descripcionPrueba" class="col-md-4 control-label">*{{Lang::get('leyendas.descripcion')}}</label>
				<textarea rows="3" cols="50" maxlength="1000"   id="descripcionPrueba" name="descripcionPrueba" class="form-control required" tabindex="2" > {{$prueba->descripcion}}</textarea> 
    	    </div>
    	</div>
	</div>
	
		<div class="row">
    	
    <div class="col-md-12">
           <div class="form-group">
                <label for="indicacionesPrueba" class="col-md-4 control-label">*{{Lang::get('leyendas.indicaciones')}}</label>
				<textarea rows="3" cols="50" maxlength="1000"  id="indicacionesPrueba" name="indicacionesPrueba" class="form-control required"  tabindex="3" >{{$prueba->indicaciones}}</textarea> 
    	    </div>
    	</div>
	</div>
         
       
        
   </div>     	
</div>    

