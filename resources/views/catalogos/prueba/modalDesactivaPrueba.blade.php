<div class="modal fade modal-slide-in-right" aria-hidden="true" data-backdrop="static"
role="dialog" tabindex="-1" id="modal-delete">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <h4 class="modal-title">{{Lang::get('leyendas.eliminarPrueba')}}</h4>
			</div>
			<div class="modal-body">
				<p>{{Lang::get('leyendas.confirmaeliminarPrueba')}}<span class="txtId"></span></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-xs" data-dismiss="modal">{{Lang::get('leyendas.cerrar')}}</button>
				<button type="button" class="btn btn-success btn-xs eliminarPrueba" >{{Lang::get('leyendas.confirmar')}}</button>
			</div>
		</div>
	</div>
</div>