<div class="modal fade modal-slide-in-right" aria-hidden="true" data-backdrop="static" 
    role="dialog" tabindex="-1" id="modalEjercicio" style="width: 100%">
    	<div class="modal-dialog" style="width: 85%">
    		<div class="form-group">
          		<input type="hidden" name="filaNo"  id="filaNo" >
          		<input type="hidden" name="idEjercicio"  id="idEjercicio" >
    		</div>
    		<div class="modal-content">
    			<div class="modal-header">
                    <h4 class="modal-title">{{Lang::get('leyendas.tipoEjercicio.agregaEjercicio')}}</h4>
    			</div>
    			<div class="modal-body">
    				<div class="row">
                    	<div  class="col-md-12">	
                    		<div class="form-group">
                    			<label for="nombreEjercicio" class="control-label">*{{Lang::get('leyendas.tipoEjercicio.ejercicios')}}</label> 
                    			<input type="text"  tabindex="1" id="nombreEjercicio"  name="nombreEjercicio" value="" class="form-control required alfa" maxlength="50">
                    		</div>
                    	</div>
                    </div>
                    
                    <div class="row">
                    	<div class="form-group">
                        	<div  class="col-md-2">	
                        			<label class="control-label">*{{Lang::get('leyendas.tipoEjercicio.descripcion')}}</label> 
                        	</div>
                        	<div  class="col-md-9">	
                        			<input type="text"  tabindex="2" id="descripcionEjercicio"  name="descripcionEjercicio" value="" class="form-control required alfa" maxlength="300">
                        	</div>
                    	</div>
                    </div>
                    
                    
    				
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-default btn-xs"  tabindex="14" data-dismiss="modal">{{Lang::get('leyendas.cerrar')}}</button>
    				<button type="button" class="btn btn-success btn-xs" tabindex="15" id="confirmaAgregaEjercicio" name="confirmaAgregaEjercicio">{{Lang::get('leyendas.confirmar')}}</button>
    			</div>
    		</div>
    	</div>
    </div>
