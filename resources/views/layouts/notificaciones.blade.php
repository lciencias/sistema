@if (Session::get('totalInterpretaciones') > 0)
<li class="dropdown messages-menu"><a href="#" class="dropdown-toggle"
	data-toggle="dropdown" data-toggle="tooltip" data-placement="left"
	title="Tareas Pendientes"> <i class="glyphicon glyphicon-list-alt"></i>
		<span class="label label-success" id="" style="font-weight:bold;" id="noTareasHeader">{{Session::get('totalInterpretaciones')}}</span>
</a>
	<ul class="dropdown-menu">
		<li class="header">{{Lang::get('leyendas.home.tareas.pendientes')}}:&nbsp;&nbsp;<b>{{Session::get('totalInterpretaciones')}}</b></li>
		<li>
			<ul class="menu">
				@foreach (Session::get('interpretaciones') as $inter)
				<li>
					<input type="hidden" name="inter{{$inter->idprueba_interpretacion}}" id="inter{{$inter->idprueba_interpretacion}}" 
					value="{{$inter->candidato}}|{{$inter->paterno}}|{{$inter->materno}}|{{$inter->prueba}}|{{$inter->perfil}}|{{$inter->resultado}}|{{$inter->interpretacion}}">
					<a href="#" id="{{$inter->idprueba_interpretacion}}" class="seleccionaIdInterpretacion" >
				
					<div class="pull-left tdCenter">
						<span class="glyphicon glyphicon-list-alt"></span> &nbsp;
					</div>
					<p style="font-size: 13px; font-weight: bold;">{{$inter->prueba}}
						<p style="font-size: 12px;">
							<small><i class="glyphicon glyphicon-pencil">{{$inter->resultado}}</i></small>
						</p>
					</p>
				</a></li> 
				@endforeach
			</ul>
		</li>
		<!--  <li class="footer"><a href="{{$menu->getPathWeb()}}misTareas">{{Lang::get('leyendas.home.tareas')}}</a></li>-->
	</ul></li>
@endif
