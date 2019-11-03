@extends ('layouts.home')
@section ('contenido')
<div class="panel panel-default">
	<div class="panel-heading">
		<strong>
			{{Lang::get('leyendas.error')}}
		</strong>
	</div>
	 <div class="panel-body">
		<div class="row">
			<div class="col-md-12">						
				<p>{{Lang::get('leyendas.errorDescripcion')}}</p>
			</div>
		</div>
	</div>
</div>
@endsection