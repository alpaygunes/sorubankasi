<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>


    <link href="https://fonts.googleapis.com/css?family=Anton|Dosis:800|Hind:300|Indie+Flower|Gloria+Hallelujah" rel="stylesheet">


    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">



    <!-- include summernote css/js-->
    <link href="{{ asset('summernote/summernote.css') }}" rel="stylesheet">
    <script src="{{ asset('summernote/summernote.js') }}"></script>

    <!-- java scriptlerim -------------------- -->
    <script src="{{ asset('js/ag-scripts.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ag_front.css') }}" rel="stylesheet">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body>
<div id="app">



{{--///////////////////////////////////// NAV BAR //////////////////////////////////////////////////--}}

    <nav class="navbar navbar-default navbar-static-top">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->

                    <li><a href="/">Anasayfa</a></li>
                    @guest
                        <li><a href="/uyelik/giris">Giriş</a></li>
                        <li><a href="/uyelik/uyeol">Kaydol</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Çıkış
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                                <li>
                                    <a href="/profil">Profilim</a>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
    </nav>

    <div class="ust">
            <div class="ag-logo">
                SoruSor.co
            </div>
        @if (Auth::check())
            <div class="ag-dugmeler ">

                <div class="ag-buyuk-btn" onclick="window.location.href='/sorucoz/giris'" id="ag-buyuk-btn-soru-coz">
                    <div class="ag-btn-txt"> Soru çöz</div>
                </div>

                <div class="ag-buyuk-btn" onclick="window.location.href='/varliklarim'"  id="ag-buyuk-btn-varliklarim">
                    <div class="ag-btn-txt"> Valıklarım</div>
                </div>

                <div class="ag-buyuk-btn" onclick="window.location.href='/duello'"  id="ag-buyuk-btn-duello">
                    <div class="ag-btn-txt"> Duello </div>
                </div>

                <div class="ag-buyuk-btn" onclick="window.location.href='/arkadaslarim'"  id="ag-buyuk-btn-arkadaslarim">
                    <div class="ag-btn-txt"> Rakiplerim </div>
                </div>

            </div>
        @endif
    </div>

    <div class="container">
            @if (Auth::check())
                {{--@include('front.inc_menu')--}}
            @endif
            @yield('content')
    </div>


<!-- //////////////////////////////////////     MESAJ MODAL - TÜM SİTEDEN ERİŞİLİ   ////////////////////////////// -->
    <div id="agMesajBoxModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{config('app.name')}}</h4>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                </div>
            </div>

        </div>
    </div>


<!-- //////////////////////////////////////   İŞlEM DEVAME DİYOR   ////////////////////////////// -->
    <div id="ag-islem-devam-ediyor">
        <i class="fa fa-cog fa-spin fa-fw margin-bottom"></i>
    </div>


<!-- --------------------------------------- FOOTER ------------------ -->
    <div id="ag-footer">
        <div id="ag-iletisim">alpaygunes@gmail.com</div>
        <div id="ag-telif">2017 - HaydiSor.co</div>
    </div>
</div>

</body>
</html>

<script>
    $(".ag-logo").click(function () {
        window.location.href="/";
    })
    $(".ag-anasayfa").click(function () {
        window.location.href="/";
    })

    var islemBar = $('#ag-islem-devam-ediyor');

</script>

<style>


    .container{
        padding: 0px!important;
    }

    .ag-logo{
        position: relative;
        width: 400px;
        /*background-image: url("/bgimages/logo.png");*/
        background-repeat: no-repeat;
        left: 25px;
        cursor: pointer;
        font-size: 35px;
        color: #fff;
        font-family: 'Gloria Hallelujah', cursive;
    }

    .ust{
        height: 190px;
        width: 100%;
        background-repeat: repeat-x;
        background-color: #30626f;
        margin-bottom: 20px;
    }

    #ag-islem-devam-ediyor{
        top:0px;
        left: 0px;
        position: absolute;
        width: 100%;
        font-size: 200px!important;
        text-align: center;
        padding-top: 25%;
        display: none;
    }

    #ag-footer{
        position: relative;
        width: 100%;
        background-color: #0f2c3d;
        padding: 10px;
        color: #194a67;
        text-align: center;
        height: 90px;
        bottom:0px;
     }




    .ag-dugmeler{
        position: relative;
        margin-top: -20px;
        text-align: center;
        width: 100%;
        display: block;
        z-index: 999;
        min-width: 650px;

    }

    .ag-buyuk-btn{
        position: relative;
        width: 100px;
        height: 120px;
        background-repeat: no-repeat;
        display: inline-block;
        margin: 10px;
        cursor: pointer;
        background-size: 100%;
    }

    #ag-buyuk-btn-soru-coz{
        background-image: url("/bgimages/bt-soru-coz.png");
    }

    #ag-buyuk-btn-varliklarim{
        background-image: url("/bgimages/bt-varliklarim.png");
    }

    #ag-buyuk-btn-duello{
        background-image: url("/bgimages/bt-duello.png");
    }

    #ag-buyuk-btn-arkadaslarim{
        background-image: url("/bgimages/bt-arkadaslarim.png");
    }

    .ag-btn-txt{
        font-family: "Open Sans","Helvetica Neue","Helvetica","Roboto","Arial",sans-serif;
        font-size: 18px;
        color: #fff;
        position: absolute;
        bottom: 11px;
        text-align: center;
        width: 100%;
        text-shadow: 1px 1px 1px #000;
        font-family: 'Gloria Hallelujah', cursive;
        height: 30px;
        line-height: 25px;
        padding: 3px;
    }
</style>
