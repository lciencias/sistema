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
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/seguros.css')}}">
</head>
<body class="hold-transition skin-red sidebar-mini">
@inject('menu','sistema\Http\Controllers\MenuController')
<div class="wrapper">
    <header class="main-header">
        <a href="#" class="logo">
            <span class="logo-mini"></span>
            <span class="logo-lg"></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
        </nav>
    </header>
    <aside class="main-sidebar">
        
    </aside>
    <div class="content-wrapper" style="background-color:#ffffff;">
        <section class="content">
            <div class="row">
                <div class="col-md-12 tdCenter">
	                <p></p>
	                <p></p>
                	<p><H1>LO SENTIMOS ESTA PAGINA NO EXISTE</H1></p>
				</div>
            </div>
        </section>
    </div>
</div>
</body>
</html>