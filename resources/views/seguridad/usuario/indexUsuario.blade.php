@extends ('layouts.home')
@section ('contenido')
<input type="hidden" name="idModulo" id="idModulo" value="{{$moduloId}}">
<input type="hidden" name="controller" id="controller" value="{{$controller}}">
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive" id="result">		
		</div>						
	</div>
</div>
@endsection