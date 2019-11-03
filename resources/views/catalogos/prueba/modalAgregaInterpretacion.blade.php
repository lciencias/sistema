

<div class="modal fade"  id="agregaInterpretacionModal" name="agregaInterpretacionModal"   
     tabindex="-1" role="dialog"  data-keyboard="false" data-backdrop="static"
     aria-labelledby="favoritesModalLabel" >
  <div class="modal-dialog" role="document" id="divAgregaInterpretacion" name="divAgregaInterpretacion" >
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" 
        id="favoritesModalLabel">Agregar interpretación</h4>
      </div>
      
      <div class="alert-warning alert-dismissible" id="alert"  hidden="false">
 
	</div>
      
       <div class="form-group">
       <input type="hidden" name="filaNo"  id="filaNo" >
      <input type="hidden" name="idEditaResultado"  id="idEditaREsultado" >
		</div>
		
    
      
     <div class="form-group">
		<label class="col-lg-3 control-label" for="nombre">Resultado</label> 
		<input type="text" name="interpretacion" id="interpretacion" maxlength="150" class="form-control required">
	</div>
	
	 <div class="form-group">
		<label class="col-lg-3 control-label" for="nombre">Descripcion</label> 
		<textarea rows="3" cols="50" maxlength="1000"   id="descripcionInterpretacion" name="descripcionInterpretacion" class="form-control required" tabindex="2" > </textarea> 
	</div>
	
	<div id="divCombinacion" name="divCombinacion" >
    	 <div class="form-group">
    		<label class="col-lg-3 control-label" for="nombre">Estilo de liderazgo</label> 
    		<textarea rows="3" cols="50" maxlength="1000"   id="estiloResultado" name="estiloResultado" class="form-control required" tabindex="2" > </textarea> 
    	</div>
    	
    	 <div class="form-group">
    		<label class="col-lg-3 control-label" for="nombre">Contribuciones a la organización</label> 
    		<textarea rows="3" cols="50" maxlength="1000"   id="contribucionesResultado" name="estiloResultado" class="form-control required" tabindex="2" > </textarea> 
    	</div>
    	
    	
    	 <div class="form-group">
    		<label class="col-lg-3 control-label" for="nombre">Dificultades potenciales</label> 
    		<textarea rows="3" cols="50" maxlength="1000"   id="dificultadesResultado" name="estiloResultado" class="form-control required" tabindex="2" > </textarea> 
    	</div>
    	
    	 <div class="form-group">
    		<label class="col-lg-3 control-label" for="nombre">Ambientes de trabajo preferidos </label> 
    		<textarea rows="3" cols="50" maxlength="1000"   id="ambienteResultado" name="estiloResultado" class="form-control required" tabindex="2" > </textarea> 
    	</div>
    </div>

      <div class="modal-footer">
        <button type="button" 
           class="btn btn-default" id="btnCloseModalResultados"
           data-dismiss="modal">Cerrar</button>
        <span class="pull-right">
          <button type="button" class="btn btn-info  editable btn-sm submi" id="confirmaAgregaInterpretacion" name="confirmaAgregaInterpretacion">Confirmar</button>
        </span>
      </div>
    </div>
  </div>
</div>
