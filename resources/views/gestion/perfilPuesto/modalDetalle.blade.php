<div class="modal fade modal-slide-in-right" aria-hidden="true" data-backdrop="static" 
    role="dialog" tabindex="-1" id="modalComportamiento" style="width: 100%">
    	<div class="modal-dialog" style="width: 85%">
    		<div class="form-group">
          		<input type="hidden" name="filaNo"  id="filaNo" >
          		<input type="hidden" name="idComportamiento"  id="idComportamiento" >
          		<input type="hidden" name="idNivelDestacado"  id="idNivelDestacado" >
          		<input type="hidden" name="idNivelSuperior"  id="idNivelSuperior" >
          		<input type="hidden" name="idNivelSatisfactorio"  id="idNivelSatisfactorio" >
          		<input type="hidden" name="idNivelEnDesarrollo"  id="idNivelEnDesarrollo" >
          		<input type="hidden" name="idNivelLimitado"  id="idNivelLimitado" >
          		<input type="hidden" name="idNivelNoObservado"  id="idNivelNoObservado" >
    		</div>
    		<div class="modal-content">
    			<div class="modal-header">
                    <h4 class="modal-title">{{Lang::get('leyendas.perfilPuesto.agregaComportamiento')}}</h4>
    			</div>
    			<div class="modal-body">
    				<div class="row">
                    	<div  class="col-md-12">	
                    		<div class="form-group">
                    			<label for="nombreComportamiento" class="control-label">*{{Lang::get('leyendas.perfilPuesto.comportamiento')}}</label> 
                    			<input type="text"  tabindex="1" id="nombreComportamiento"  name="nombreComportamiento" value="" class="form-control required alfa" maxlength="50">
                    		</div>
                    	</div>
                    </div>
                    
                    <div class="row">
                    	<div class="form-group">
                        	<div  class="col-md-2">	
                        			<label class="control-label">*{{Lang::get('leyendas.comportamiento.nivelDestacado')}}</label> 
                        	</div>
                        	<div  class="col-md-9">	
                        			<input type="text"  tabindex="2" id="nivelDestacadoT"  name="nivelDestacadoT" value="" class="form-control required alfa" maxlength="300">
                        	</div>
                    	</div>
                    </div>
                    
                    
                    <div class="row">
                    	<div class="form-group">
                        	<div  class="col-md-2">	
                        			<label class="control-label">*{{Lang::get('leyendas.comportamiento.nivelSuperior')}}</label> 
                        	</div>
                        	<div  class="col-md-9">	
                        			<input type="text"  tabindex="4" id="nivelSuperiorT"  name="nivelSuperiorT" value="" class="form-control required alfa" maxlength="300">
                        	</div>
                    	</div>
                    </div>
                    
                    <div class="row">
                    	<div class="form-group">
                        	<div  class="col-md-2">	
                        			<label  class="control-label">*{{Lang::get('leyendas.comportamiento.nivelSatisfactorio')}}</label> 
                        	</div>
                        	<div  class="col-md-9">	
                        			<input type="text"  tabindex="6" id="nivelSatisfactorioT"  name="nivelSatisfactorioT" value="" class="form-control required alfa" maxlength="300">
                        	</div>
                    	</div>
                    </div>
                    
                    <div class="row">
                    	<div class="form-group">
                        	<div  class="col-md-2">	
                        			<label class="control-label">*{{Lang::get('leyendas.comportamiento.nivelEnDesarrollo')}}</label> 
                        	</div>
                        	<div  class="col-md-9">	
                        			<input type="text"  tabindex="8" id="nivelEnDesarrolloT"  name="nivelEnDesarrolloT" value="" class="form-control required alfa" maxlength="300">
                        	</div>
                    	</div>
                    </div>
                    
                    <div class="row">
                    	<div class="form-group">
                        	<div  class="col-md-2">	
                        			<label class="control-label">*{{Lang::get('leyendas.comportamiento.nivelLimitado')}}</label> 
                        	</div>
                        	<div  class="col-md-9">	
                        			<input type="text"  tabindex="10" id="nivelLimitadoT"  name="nivelLimitadoT" value="" class="form-control required alfa" maxlength="300">
                        	</div>
                    	</div>
                    </div>
                    
                    <div class="row">
                    	<div class="form-group">
                        	<div  class="col-md-2">	
                        			<label class="control-label">*{{Lang::get('leyendas.comportamiento.nivelNoObservado')}}</label> 
                        	</div>
                        	<div  class="col-md-9">	
                        			<input type="text"  tabindex="12" id="nivelNoObservadoT"  name="nivelNoObservadoT" value="" class="form-control required alfa" maxlength="300">
                        	</div>
                    	</div>
                    </div>
    				
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-default btn-xs"  tabindex="14" data-dismiss="modal">{{Lang::get('leyendas.cerrar')}}</button>
    				<button type="button" class="btn btn-success btn-xs" tabindex="15" id="confirmaAgregaComportamiento" name="confirmaAgregaComportamiento">{{Lang::get('leyendas.confirmar')}}</button>
    			</div>
    		</div>
    	</div>
    </div>
