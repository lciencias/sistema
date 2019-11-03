<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Cache-control" content="max-age=0" />
    <meta http-equiv="Cache-control" content="no-cache" />
    <meta http-equiv="Cache-control" content="no-store" />
    <meta http-equiv="Cache-control" content="must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <title>{{Lang::get('leyendas.titulo')}}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="apple-touch-icon" href="{{asset('img/apple-touch-icon.png')}}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrapValidator.css')}}">
    <link rel="stylesheet" href="{{asset('css/seguros.css')}}">
    <script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/moment.js')}}"></script>
    <script src="{{asset('js/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('js/bootstrapValidator.min.js')}}"></script>
    <script src="{{asset('js/bootbox.min.js')}}"></script>
    <script src="{{asset('js/lang.js')}}"></script>
    <script src="{{asset('js/jquery.tablesorter.js')}}"></script>
    <script src="{{asset('js/jquery.tablesorter.widgets.js')}}"></script>
    <script src="{{asset('js/tablesorter.js')}}"></script>
    <script src="{{asset('js/seguros.js')}}"></script>
    <script src="{{asset('js/reset.js')}}"></script>
    <script src="{{asset('js/jquery.slimscroll.min.js')}}"></script>
</head>
<body class="hold-transition skin-red layout-top-nav">
<!-- <body class="hold-transition skin-red sidebar-mini"> -->
@inject('menu','sistema\Http\Controllers\MenuController')
<div class="wrapper">
	<header class="main-header">
    	<nav class="navbar navbar-static-top">
      	<div class="container">
	        <div class="navbar-header">
    	      <!--<a href="../../index2.html" class="navbar-brand"><b>Admin</b>LTE</a>-->
        	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            	<i class="fa fa-bars"></i>
          	</button>
        	</div>

        	<!-- Collect the nav links, forms, and other content for toggling -->
        	<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
	       	<?php
	            echo $menu->getMenuCompleto();
            ?>
			</div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
			
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <small class="bg-red"><b>{{Lang::get('leyendas.bienvenido')}}</b></small>
                            <span class="hidden-xs">{{$menu->getNombreUsuario()}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{$menu->getPathWeb()}}img/user.png" class="img-circle" alt="User Image">
                                <p><strong>{{$menu->getPerfil()}}</strong><br>
                                    <small>{{$menu->getEmpresa()}}<br>{{$menu->getIngreso()}}</small></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{url('/auth/reset/reset')}}" class="btn btn-default btn-sm" style="border-color: red;">{{Lang::get('leyendas.actualizarPassword')}}</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{url('/logout')}}" class="btn btn-default btn-flat btn-sm" style="border-color: red;">{{Lang::get('leyendas.cerrar')}}</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
			</div>
		</div>
   		</nav>
    </header>
	<div class="content-wrapper">
    	<div class="container" >
    	 	@include('layouts.breadcrumbh');
			<section class="content">
				<div class="box box-default">
					<input type="hidden" name="token" id="token" value="{{ csrf_token() }}">          			
          			<div class="box-body">
						<div id="procesando"></div>
						<div id="errorJs" class="errorJs"></div>
                        <div class="box-header with-border" style="position: absolute;top: -5px;right:25px;z-index:9999999999999;">
                            @include('alerts.success')
                            @include('alerts.errors')
                            @include('alerts.warnings')
                        </div>
                        <div class="row">
                        	<div class="col-md-12">@yield('contenido')</div>
                        </div>					
          			</div>
		        </div>
      		</section>
    	</div>    
  	</div>
    @include('layouts.modal')
    <footer class="main-footer">
    	<div class="container">
      		<div class="pull-right hidden-xs">
         		<b>{{Lang::get('leyendas.version')}}</b>
      		</div>
    	</div>
	</footer>
    @include('layouts.extra')
    <div class="control-sidebar-bg"></div>
</div>
<script src="{{asset('js/fastclick.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<script src="{{asset('js/demo.js')}}"></script>
</body>
</html>