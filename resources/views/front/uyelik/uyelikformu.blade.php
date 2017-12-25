@extends('front.layouts.front')

@section('content')
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default ag-form-panel">
                    <div class="panel-heading">Üyelik Formu</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Adı</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">Eposta Adresi</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Parola</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Tekrar Parola</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('guvenlik_kodu') ? ' has-error' : '' }}">
                                <label for="guvenlik-kodu" class="col-md-4 control-label">Güvenlik kodu</label>
                                <div class="col-md-6">
                                    <img src="/uyelik/getGuvenlikResmi/" width="200" height="40">
                                    <input id="guvenlik-kodu" type="text" class="form-control" name="guvenlik_kodu" required placeholder="Güvenlik kodunu yazın">
                                    @if ($errors->has('guvenlik_kodu'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('guvenlik_kodu') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Tamam
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    <style>


        .ag-form-panel {
            position: relative;
            margin-top: 50px;
        }

        .ag-form-panel label {
            color: #8c8c8c;
        }

        #ag-footer{
            position: absolute!important;
        }


    </style>


    <script>

        /*$.ajax({
            url: '/uyelik/getGuvenlikResmi/',
            dataType: 'json',
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(data) {
                console.log(data)
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });*/

    </script>

@endsection