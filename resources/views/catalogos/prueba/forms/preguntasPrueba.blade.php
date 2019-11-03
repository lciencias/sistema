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
    	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <div class="form-group">
              	<div class="rows">
                    <button 
                       type="button" 
                       class="btn btn-primary editable btn-sm glyphicon glyphicon-plus" 
                       data-toggle="modal" 
                       data-target="#agregaPreguntaModal" onclick="return abreAgregaPregunta();">Agregar</button>
                 </div>
        		<div class="table-responsive">
        			<table class="table table-striped table-bordered table-condensed table-hover"  id="tablaPreguntas">
        				<thead class="table-encabezado">
        					<th style="visibility: hidden">id</th>
        					<th class="centerText">No.</th>
        					<th class="centerText">Pregunta</th>
        					<th class="centerText">Acciones</th>
        					<th style="visibility: hidden" width="0">Opciones</th>
        				</thead>
        	               @foreach ($preguntasPrueba as $preguntaPrueba)
        					<tr id="fila-{{$preguntaPrueba->orden}}">
        						<td style="visibility: hidden">{{ $preguntaPrueba->idpregunta_prueba}}</td>
        						<td>{{ $preguntaPrueba->orden}}</td>
        						<td>{{ $preguntaPrueba->pregunta}}</td>
        						
        						<td style="width:16%; text-align:center;">
        							<a href="#" class="btn btn-default btn-xs"> <span data-toggle="tooltip" data-placement="top" title="Editar" class="glyphicon glyphicon-pencil"></span></a> 
                         			<a href="#" class="btn btn-default btn-xs modaleliminar"><span data-toggle="tooltip" data-placement="top" title="Eliminar" class="glyphicon glyphicon-trash"></span></a>
        						</td>
        						<td style="visibility: hidden" valor=""> </td>
        						
        					</tr>
        					@endforeach
        			</table>
        		</div>
			</div>
	    </div>
	</div>
       
        
   </div>     	
</div>    

