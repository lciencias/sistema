<div class="box box-solid">
	<div class="box-header">
		<div class="pull-right box-tools">
			<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
        <i class="fa fa-minus"></i></button>
		</div>
    	<h3 class="box-title" style="color:blue;font-weight:bold;">Talentos</h3>
	</div>
 	<div class="box-body">
 	
 		<div class="rows">
        	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        		<div class="form-group">
        			<label  class="col-lg-4 control-label" for="nombre">*{{Lang::get('leyendas.perfilPuesto.talentos')}}</label>  
        				@if(count($talentos) > 0)   		
        				<select name="idTalentoAgrega" id="idTalentoAgrega"  class="form-control talento" tabindex="3">
          					<option value="">{{Lang::get('leyendas.seleccione')}}</option>
        						@foreach($talentos as $talento)
        							<option value="{{$talento->idtalento}}" >{{$talento->nombre}}</option>
        						@endforeach  				
        				</select>
        				@endif
        		</div>
        	</div>	
        	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        		<div class="form-group">
        			<button id="btnAgregaTalento" name="btnAgregaTalento" disabled="disabled">{{Lang::get('leyendas.agregar')}}</button>  
        		</div>
        	</div>	
        </div>
 	
 	
		
        <div class="row">
        	<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                <div class="form-group">
            		<div class="table-responsive">
            			<table class="table table-striped table-bordered table-condensed table-hover"  id="tablaTalentos">
            				<thead class="table-encabezado">
            					<th style="visibility: hidden">id</th>
            					<th style="visibility: hidden"></th>
            					<th class="centerText">Talento</th>
            					<th class="centerText">Acciones</th>
            				</thead>
            	               @foreach ($talentosPerfilPuesto as $talentoPerfilPuesto)
            					<tr>
            						<td style="visibility: hidden">{{ $talentoPerfilPuesto->idperfil_puesto_talento}}</td>
            						<td style="visibility: hidden">{{ $talentoPerfilPuesto->id_talento}}</td>
            						<td>{{ $talentoPerfilPuesto->talento->nombre}}</td>
            						<td style="width:16%; text-align:center;">
            							<a href="#" class="btn btn-default btn-xs"> <span data-toggle="tooltip" data-placement="top" title="Editar" class="glyphicon glyphicon-pencil"></span></a> 
                                     	<a href="#" class="btn btn-default btn-xs"> <span data-toggle="tooltip" data-placement="top" title="Eliminar" class="glyphicon glyphicon-trash"></span></a>
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

