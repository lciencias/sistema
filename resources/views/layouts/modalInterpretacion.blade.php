<div class="modal fade" id="modalInterpretacion" tabindex="-1" role="dialog" aria-labelledby="modalConsulta" data-backdrop="static">
	<div class="modal-dialog" role="document" style="width:50%;">
		<div class="modal-content">
			<div class="modal-header"> 
				<h4 class="modal-title" id="nombreCandidato"></h4>
			</div>
			<div class="modal-body" id="modalConsultabuffer">
				<table class="table table-striped">
					<tr>
						<td style="font-weight: bold:">{{Lang::get('leyendas.interpretacion.perfil')}}</td>
						<td><span id="perfilCandidato"></span></td>
					</tr>
					<tr>
						<td style="font-weight: bold:">{{Lang::get('leyendas.interpretacion.prueba')}}</td>
						<td><span id="pruebaCandidato"></span></td>
					</tr>
					<tr>
						<td style="font-weight: bold:">{{Lang::get('leyendas.interpretacion.resultado')}}</td>
						<td><span id="resultadoCandidato"></span></td>
					</tr>
					<tr>
						<td style="font-weight: bold:">{{Lang::get('leyendas.interpretacion.mensaje')}}</td>
						<td><span id="interpretacionCandidato"></span></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm cerrarModal"  data-dismiss="modal" id="cerrarModal">
					<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;{{Lang::get('leyendas.cerrar')}}
				</button>
			</div>
		</div>
	</div>
</div>