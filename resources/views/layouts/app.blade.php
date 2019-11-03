<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
	<link rel="shortcut icon" href="{{asset('img/favicon.ico')}}">
	<link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
	<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/bootstrapValidator.css')}}">	
	<link rel="stylesheet" href="{{asset('css/seguros.css')}}">
</head>
<body id="app-layout" class="hold-transition skin-red sidebar-mini">
<nav class="navbar navbar-default navbar-static-top" style="background-color: #71BBDD;height: 68px;">
	<div class="container">
		<div class="navbar-header">
			<!-- Collapsed Hamburger -->
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<!-- Branding Image -->
			<a class="navbar-brand" href="{{ url('/') }}" >
				<img src="/{{sistema\Policies\Constantes::THIS_SERVER_NAME}}/public/img/logo.png" height="40" border="0">
			</a>
		</div>
		<div class="collapse navbar-collapse" id="app-navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="{{ url('/login') }}"><span style="color:#ffffff;font-size:18px;">{{Lang::get('leyendas.subtitulo')}}</span></a></li>
			</ul>
		</div>
	</div>
</nav>
@yield('content')
<script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/bootstrapValidator2.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<script src="{{asset('js/lang.js')}}"></script>
<script src="{{asset('js/reset.js')}}"></script>
</body>
</html>