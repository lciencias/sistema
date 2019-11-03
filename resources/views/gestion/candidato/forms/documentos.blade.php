

<div class="box box-solid">
	<div class="box-header">
    	<div class="pull-right box-tools">
        	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
        <i class="fa fa-minus"></i></button>
	</div>
    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.documentos')}}</h3>
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
                       data-target="#modalEjercicio" onclick="return abreTelefonosCandidato();">Agregar</button>
                 </div>
					<table class="table table-bordered" id="tablaEjercicios">
						<thead>
							<tr>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.nombre')}}</th>
								<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.descargar')}}</th>
							</tr>				
						</thead>
						
						<tbody>	
							@if (count($documentos) > 0)
						    	@foreach ($documentos as $doc)
									<tr>
										<td>{{ $doc->nombre}}</td>
										<td class="tdCenter">
											<a href="#" class="btn btn-default  btn-xs editaTelefono" id="{{$doc->idarchivo}}">
								 				<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil" id="{{$doc->idarchivo}}"></span>
								 			</a>
								 		</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>	
			</div>
	    </div>
	</div>
		
	</div>
</div>


