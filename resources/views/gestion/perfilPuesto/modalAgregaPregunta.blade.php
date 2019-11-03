

<div class="modal fade"  id="agregaPreguntaModal" name="agregaPreguntaModal"   
     tabindex="-1" role="dialog"  data-keyboard="false" data-backdrop="static"
     aria-labelledby="favoritesModalLabel" >
  <div class="modal-dialog" role="document" style="width: 85%">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" 
        id="favoritesModalLabel">Agregar pregunta</h4>
      </div>
      
      <div class="alert-warning alert-dismissible" id="alert"  hidden="false">
 
	</div>
      
       <div class="form-group">
      <input type="hidden" name="filaNo"  id="filaNo" >
      <input type="hidden" name="idEdita"  id="idEdita" >
		</div>
		
       <div class="form-group">
	<label class="col-lg-3 control-label" for="ordenPregunta">Orden</label> 
	<input
		type="text" name="ordenPregunta"  id="ordenPregunta" maxlength="3" class="form-control" disabled="disabled">
	</div>
      
     <div class="form-group">
	<label class="col-lg-3 control-label" for="nombre">Pregunta</label> 
		<input 
		type="text" name="pregunta" id="pregunta" maxlength="150" class="form-control required"
		 placeholder="Nombre...">
	</div>
	
	

	
	<div class="row" >
	 	<div class="col-md-6 col-md-offset-5">
            	<button type="button"  name="btnAgregaOpcion"
           			class="btn btn-default" id="btnAgregaOpcion">Agregar opción</button>
           	</div>  		
           			
           			<div class="span12"><br>
						 <table id="tablaOpciones" class="bordered-table">							
			  			 </table>
			  		</div>  
           			
	</div>    
	
	
	

      <div class="modal-footer">
        <button type="button" 
           class="btn btn-default" id="btnCloseModal"
           data-dismiss="modal">Cerrar</button>
        <span class="pull-right">
          <button type="button" class="btn btn-info  editable btn-sm submi" id="confirmaAgregaPregunta" name="confirmaAgregaPregunta">Confirmar</button>
        </span>
      </div>
    </div>
  </div>
</div>
