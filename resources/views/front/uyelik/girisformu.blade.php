@extends('front.layouts.front')

@section('content')
    <div class="container">

        @if(Session::has('alert'))
            <div class="alert alert-danger ag-alert">
                {{Session::get('alert')}}
            </div>
        @endif

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default  ag-form-panel">
                    <div class="panel-heading">Giriş</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="/uyelik/giris/yap">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Epost Adresi</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"  required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Parola</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" > Beni hatırla
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Giriş
                                    </button>

                                    <a class="btn btn-link" href="{{'/uyelik/parolamiunuttum'}}">
                                        Parolamı Unuttum
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>

        .ag-form-panel {
            position: relative;
            box-shadow: 10px 10px 50px #000;
        }

        .ag-form-panel label {
            color: #8c8c8c;
        }

        #ag-footer{
            position: absolute!important;
        }
    </style>
@endsection
