@if (Session::get('noTareas') > 0)
<li class="dropdown messages-menu"><a href="#" class="dropdown-toggle"
	data-toggle="dropdown" data-toggle="tooltip" data-placement="left"
	title="Tareas Pendientes"> <i class="glyphicon glyphicon-list-alt"></i>
		<span class="label label-success" id="" style="font-weight:bold;" id="noTareasHeader"">{{Session::get('noTareas')}}</span>
</a>
	<ul class="dropdown-menu">
		<li class="header">{{Lang::get('leyendas.home.tareas.pendientes')}}:&nbsp;&nbsp;<b>{{Session::get('noTareas')}}</b></li>
		<li>
			<ul class="menu">
				@foreach (Session::get('tareas') as $tarea)
				<li><a href="{{$menu->getPathWeb()}}{{$tarea->path}}" id="{{$tarea->idmi_tarea}}" class="seleccionaIdtarea">
						<div class="pull-left tdCenter">
							<span class="glyphicon glyphicon-list-alt"></span> &nbsp;
						</div>
						<p style="font-size: 13px; font-weight: bold;">{{$tarea->actividad}}
						
						
						<p style="font-size: 12px;">
							<small><i class="fa fa-clock-o"></i>
								{{substr($tarea->fecha_limite,8,2)}}-{{substr($tarea->fecha_limite,5,2)}}-{{substr($tarea->fecha_limite,0,4)}}
								{{substr($tarea->fecha_limite,11,8)}} <br>{{$tarea->nombre}}</small>
						</p>
				</a></li> @endforeach
			</ul>
		</li>
		<li class="footer"><a href="{{$menu->getPathWeb()}}misTareas">{{Lang::get('leyendas.home.tareas')}}</a></li>
	</ul></li>
@endif
