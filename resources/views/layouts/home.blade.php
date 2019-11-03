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
    <link rel="stylesheet" href="{{asset('css/theme.bootstrap_3.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrapValidator.css')}}">
    <link rel="stylesheet" href="{{asset('css/seguros.css')}}">
    <script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/moment.js')}}"></script>
    <script src="{{asset('js/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('js/bootstrapValidator2.js')}}"></script>
    <script src="{{asset('js/es_ES.js')}}"></script>
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('js/bootbox.min.js')}}"></script>
    <script src="{{asset('js/lang.js')}}"></script>
    <script src="{{asset('js/jquery.tablesorter.js')}}"></script>
    <script src="{{asset('js/jquery.tablesorter.widgets.js')}}"></script>
    <script src="{{asset('js/tablesorter.js')}}"></script>
    <script src="{{asset('js/seguros.js')}}"></script>
    <script src="{{asset('js/reset.js')}}"></script>
    <script src="{{asset('js/validaciones.js')}}"></script>
    <script src="{{asset('js/jquery.slimscroll.min.js')}}"></script>
</head>
<body class="hold-transition skin-red sidebar-mini">
@inject('menu','sistema\Http\Controllers\MenuController')
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="{{$menu->getPathWeb()}}" class="logo">
            <span class="logo-mini"><img src="{{$menu->getPathWeb()}}img/logoc.png" width="50" border="0"></span>
            <span class="logo-lg">
          	<img src="{{$menu->getPathWeb()}}img/logo.png" height="40" border="0">
          	</span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Navegación</span>
            </a>
            @if( $menu->getLogotipo() != '')
                <img src="{{$menu->getPathWeb()}}imagenes/empresas/{{$menu->getIdEmpresa()}}/{{$menu->getLogotipo()}}" height="45" border="0">
            @endif
            <p style="float:left;margin: 12px 20px 0 5px;color:#fff;font-size:18px;">{{Lang::get('leyendas.subtituloMovil')}}</p>
         	<div class="navbar-custom-menu">                            
                <ul class="nav navbar-nav">
                @include('layouts.notificaciones')
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <small class="bg-red"><b>{{Lang::get('leyendas.bienvenido')}}</b></small>
                            <span class="hidden-xs">{{$menu->getNombreUsuario()}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{$menu->getPathWeb()}}img/user.png" class="img-circle" alt="User Image">
                                <p><strong>{{$menu->getPerfil()}}</strong><br>
                                    <small>{{$menu->getIdentificadorUsuario()}}<br>{{$menu->getIngreso()}}</small></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left" style="border-color: blue;">
                                    <a href="{{url('/auth/reset/reset')}}" class="btn btn-default btn-sm" style="border-color: red;">{{Lang::get('leyendas.actualizarPassword')}}</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{url('/logout')}}" class="btn btn-default btn-flat btn-sm" style="border-color: red;">{{Lang::get('leyendas.cerrarSesion')}}</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                   
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <?php
            echo $menu->getMenuCompleto();
            ?>
        </section>
    </aside>
    <div class="content-wrapper"  >
        <!-- Main content -->
        <section class="content">
            <input type="hidden" name="token" id="token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="col-md-12">
                    <div id="procesando"></div>
                    <div class="box">
                        <div id="errorJs" class="errorJs"></div>
                        <div class="box-header with-border" style="position: absolute;top: -5px;right:25px;z-index:9999999999999;display:block;border:0px;background:transparent;">
                            @include('alerts.success')
                            @include('alerts.errors')
                            @include('alerts.warnings')
                        </div>
                        @include('layouts.breadcrumb')
                        <div class="row">
                            <div class="col-md-12">@yield('contenido')</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('layouts.modal')
    @include('layouts.modalConsulta')
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>{{Lang::get('leyendas.version')}}</b>
        </div>
    </footer>
</div>
<div id="errorModal" class="modal"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document" style="width: 340px;">
    <div class="modal-content" >
      <div class="modal-header" style="background-color: #dd4b39;color:#fff;">
        <h6 class="modal-title"><strong>{{Lang::get('leyendas.titulo')}}</strong></h6>
      </div>
      <div class="modal-body">
        <p id="leyendaError"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">{{Lang::get('leyendas.configuracion.Btitulo26')}}</button>
      </div>
    </div>
  </div>
</div>

<script src="{{asset('js/fastclick.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<script src="{{asset('js/demo.js')}}"></script>
 <script src="{{asset('js/cliente.js')}}"></script>
</body>
</html>