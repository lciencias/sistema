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
    		<div  class="col-md-6">
        		<div class="form-group">
        			<label for="tipoInterprestacionPrueba" class="col-md-4 control-label">*{{Lang::get('leyendas.prueba.tipo')}}</label>
    				<select name="tipoInterprestacionPrueba" id="tipoInterprestacionPrueba" data-column="5" class="form-control searchs combo"  style="width:100%;" {{ ($prueba->idprueba != null) ? "disabled" : "" }}>
    					<option value="">Seleccione</option>
    					@foreach($catTipoInterprestacionPrueba as $key => $value)
    						@if ($key == $prueba->tipo_interpretacion)
    							<option selected value="{{$key}}">{{$value}}</option>
    						@else
    							<option value="{{$key}}">{{$value}}</option>
    						@endif
    					@endforeach						
    				</select>
        		</div>
        	</div>		
       </div> 
       
       
        <div class="row" {{$visible}} id="divIngresaInterpretacion" name="divIngresaInterpretacion">
        	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                <div class="form-group">
                  	<div class="rows">
                        <button 
                           type="button" 
                           class="btn btn-primary editable btn-sm glyphicon glyphicon-plus" 
                           data-toggle="modal" 
                           data-target="#agregaInterpretacionModal" onclick="return abreAgregaInterpretacion();">Agregar</button>
                     </div>
            		<div class="table-responsive">
            			<table class="table table-striped table-bordered table-condensed table-hover"  id="tablaInterpretacion">
            				<thead class="table-encabezado">
            					<th style="visibility: hidden">id</th>
            					<th class="centerText">Resultado</th>
            					<th class="centerText">Interpretación</th>
            					<th class="centerText">Acciones</th>
            				</thead>
            	               @foreach ($interpretacionesPrueba as $interpretacionPrueba)
            					<tr>
            						<td style="visibility: hidden">{{ $interpretacionPrueba->idprueba_interpretacion}}</td>
            						<td>{{ $interpretacionPrueba->resultado}}</td>
            						<td>{{ $interpretacionPrueba->interpretacion}}</td>
            						
            						<td style="width:16%; text-align:center;">
            							<a href="#" class="btn btn-default btn-xs"> <span data-toggle="tooltip" data-placement="top" title="Editar" class="glyphicon glyphicon-pencil"></span></a> 
                                     	<a href="#" class="btn btn-default btn-xs modaleliminar"><span data-toggle="tooltip" data-placement="top" title="Eliminar" class="glyphicon glyphicon-trash"></span></a>
            						</td>
            						
            					</tr>
            					@endforeach
            			</table>
            		</div>
    			</div>
    	    </div>
    	</div> 
        
   </div>     	
</div>    

@include('catalogos.prueba.modalConfirmaInterpretacion')
@include('catalogos.prueba.modalAgregaInterpretacion')