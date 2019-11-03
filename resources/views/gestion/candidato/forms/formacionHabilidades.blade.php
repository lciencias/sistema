

<div class="box box-solid">
	<div class="box-header">
    	<div class="pull-right box-tools">
        	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
        <i class="fa fa-minus"></i></button>
	</div>
    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.formacion')}}</h3>
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
								<th style="visibility: hidden">id</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.candidato.tipoTelefono')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.candidato.telefono')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.candidato.notas')}}</th>
								<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
								<th style="visibility: hidden" width="0">Opciones</th>
							</tr>				
						</thead>
						
						<tbody>	
							@if (count($telefonos) > 0)
						    	@foreach ($telefonos as $tel)
									<tr id="fila-{{$tel->idtelefono_candidato}}">
										<td style="visibility: hidden">{{ $tel->idtelefono_candidato}}</td>
										<td>{{ $tel->tipo}}</td>
										<td>{{ $tel->telefono}}</td>
										<td>{{ $tel->notas}}</td>
										<td class="tdCenter">
											<a href="#" class="btn btn-default  btn-xs editaTelefono" id="{{$tel->idtelefono_candidato}}">
								 				<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil" id="{{$tel->idtelefono_candidato}}"></span>
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





<div class="box box-solid">
	<div class="box-header">
    	<div class="pull-right box-tools">
        	<button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
        <i class="fa fa-minus"></i></button>
	</div>
    <h3 class="box-title" style="color:blue;font-weight:bold;">{{Lang::get('leyendas.candidato.habilidades')}}</h3>
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
                       data-target="#modalEjercicio" onclick="return abreFamiliaresCandidato();">Agregar</button>
                 </div>
					<table class="table table-bordered" id="tablaEjercicios">
						<thead>
							<tr>
								<th style="visibility: hidden">id</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.candidato.parentesco')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.nombre')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.ocupacion')}}</th>
								<th class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.genero')}}</th>
								<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.opciones')}}</th>
								<th style="visibility: hidden" width="0">Opciones</th>
							</tr>				
						</thead>
						
						<tbody>	
							@if (count($telefonos) > 0)
						    	@foreach ($telefonos as $tel)
									<tr id="fila-{{$tel->idtelefono_candidato}}">
										<td style="visibility: hidden">{{ $tel->idtelefono_candidato}}</td>
										<td>{{ $tel->tipo}}</td>
										<td>{{ $tel->telefono}}</td>
										<td>{{ $tel->notas}}</td>
										<td class="tdCenter">
											<a href="#" class="btn btn-default  btn-xs editaTelefono" id="{{$tel->idtelefono_candidato}}">
								 				<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.editarRegistro')}}" class="glyphicon glyphicon-pencil" id="{{$tel->idtelefono_candidato}}"></span>
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
