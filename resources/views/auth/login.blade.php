@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>{{Lang::get('leyendas.bienvenidoSistema')}}</b></div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" name="auth" method="post" action="{{route('login')}}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">{{Lang::get('leyendas.usuario')}}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">{{Lang::get('leyendas.password')}}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">
                                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i> {{Lang::get('leyendas.acceder')}}
                                    </button>
                                    <a class="btn btn-link linkBam" href="{{ url('/password/reset') }}">{{Lang::get('leyendas.olvidaste')}}</a>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection