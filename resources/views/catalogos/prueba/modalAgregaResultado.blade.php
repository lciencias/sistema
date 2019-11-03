

<div class="modal fade"  id="agregaResultadoModal" name="agregaResultadoModal"   
     tabindex="-1" role="dialog"  data-keyboard="false" data-backdrop="static"
     aria-labelledby="favoritesModalLabel" >
  <div class="modal-dialog" role="document" style="width: 85%">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" 
        id="favoritesModalLabel">Agregar resultado</h4>
      </div>
      
      <div class="alert-warning alert-dismissible" id="alert"  hidden="false">
 
	</div>
      
       <div class="form-group">
       <input type="hidden" name="filaNo"  id="filaNo" >
      <input type="hidden" name="idEditaResultado"  id="idEditaREsultado" >
		</div>
		
    
      
     <div class="form-group">
		<label class="col-lg-3 control-label" for="nombre">Resultado</label> 
		<input 
		type="text" name="resultado" id="resultado" maxlength="150" class="form-control required">
	</div>
	
	 <div class="form-group">
		<label class="col-lg-3 control-label" for="nombre">Descripcion</label> 
		<textarea rows="7" cols="50" maxlength="1000"   id="descripcionResultado" name="descripcionResultado" class="form-control required" tabindex="2" > </textarea>
	</div>
	
	
	
	
	
	

      <div class="modal-footer">
        <button type="button" 
           class="btn btn-default" id="btnCloseModalResultados"
           data-dismiss="modal">Cerrar</button>
        <span class="pull-right">
          <button type="button" class="btn btn-info  editable btn-sm submi" id="confirmaAgregaResultado" name="confirmaAgregaRespuesta">Confirmar</button>
        </span>
      </div>
    </div>
  </div>
</div>
