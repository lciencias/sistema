@extends ('layouts.home')
@section ('contenido')
<input type="hidden" name="idModulo" id="idModulo" value="{{$moduloId}}">
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<p class="tdCenter">{{$titulo}}</p>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive" id="result">		
    		<table class="table table-bordered">
    		@php
    		$contador = 1;
    		@endphp
    		@if(count($pruebas) > 0)
    			<thead>
    				<tr>
    					<th class="tdCenter">{{Lang::get('leyendas.pruebaId')}}</th>
    					<th width="40%" class="tdCenter">{{Lang::get('leyendas.pruebaNombre')}}</th>
    					<th width="40%" class="tdCenter">{{Lang::get('leyendas.pruebaIndicacion')}}</th>
    					<th width="10%" class="tdCenter" data-sorter="false" data-filter="false">{{Lang::get('leyendas.pruebaIrCuestionario')}}</th>
    				</tr>
    			</thead>
    			<tbody>
    				@foreach($pruebas as $ind =>$prueba)
        				<tr>
        					<td class="tdCenter">{{$contador}}</td>
        					<td class="tdJustify">{{$prueba->nombre}}</td>
        					<td class="tdJustify">{{$prueba->indicaciones}}</td>
        					<td class="tdCenter">
        					<a href="{{URL::action('EvaluacionController@evalua',$prueba->idprueba)}}" class="btn btn-default btn-xs">
    						<span data-toggle="tooltip" data-placement="top" title="{{Lang::get('leyendas.visualizarCuestionario')}}" class="glyphicon glyphicon-pencil">						
    						</span>
    						</a>
        					</td>
        				</tr>
        				@php
    	    				$contador = $contador + 1;
        				@endphp
    				@endforeach
    			</tbody>		
    		@endif
    		</table>
		</div>						
	</div>
</div>
@endsection