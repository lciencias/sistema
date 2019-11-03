<div class="modal fade" id="modalConsulta" tabindex="-1" role="dialog" aria-labelledby="modalConsulta" data-backdrop="static">
	<div class="modal-dialog" role="document" style="width:75%;">
		<div class="modal-content">
			<div class="modal-header">
				<input type="hidden" id="numeroModulos" name="numeroModulos" value=""> 
				<h4 class="modal-title" id="modalConsultaLabel"></h4>
			</div>
			<div class="modal-body" id="modalConsultabuffer">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm cerrarModal"  data-dismiss="modal" id="cerrarModal">
					<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cerrar')}}
				</button>
			</div>
		</div>
	</div>
</div>