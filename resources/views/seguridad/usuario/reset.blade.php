<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-reset" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
				aria-label="Close">
                     <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">{{Lang::get('leyendas.reseteo')}}</h4>
			</div>
			<div class="modal-body">
				<p>{{Lang::get('leyendas.confirmareseteo')}}</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-xs" data-dismiss="modal">{{Lang::get('leyendas.cerrar')}}</button>
				<button type="button" class="btn btn-success btn-xs resetear" >{{Lang::get('leyendas.confirmar')}}</button>
			</div>
		</div>
	</div>
</div>