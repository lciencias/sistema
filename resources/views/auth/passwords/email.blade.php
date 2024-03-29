@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>{{Lang::get('leyendas.reseteoPorUsuario')}}</b></div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('password/email') }}">
                        {{Form::token()}}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{Lang::get('leyendas.correo')}}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-envelope"></i> {{Lang::get('leyendas.reseteoPorUsuario')}}
                                </button>
                            	<a class="btn btn-default " href="{{ url('login') }}">
                            	<i class="glyphicon glyphicon-home"></i>&nbsp;
                            	{{Lang::get('leyendas.regresalogin')}}</a>
                            </div>
                            
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection