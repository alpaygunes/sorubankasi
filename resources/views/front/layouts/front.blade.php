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
                            <li><a href="/uyelik/kayit">Kaydet</a></li>
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
            </div>

        <div class="container">
                @if (Auth::check())
                    {{--@include('front.inc_menu')--}}
                @endif
                @yield('content')
        </div>


<!-- //////////////////////////////////////     GÖNDERİLEN DUELLO DETAYI MODAL   ////////////////////////////// -->
        <div id="agMesajBoxModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Duello</h4>
                    </div>
                    <div class="modal-body">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                    </div>
                </div>

            </div>
        </div>
<!--         ------------------ MODAL SONU ------------ -->




    </div>

</body>
</html>

<script>
    $(".ag-logo").click(function () {
        window.location.href="/";
    })
</script>

<style>


    .container{
        padding: 0px!important;
    }

    .ag-logo{
        position: relative;
        height: 150px;
        width: 400px;
        /*background-image: url("/bgimages/logo.png");*/
        background-repeat: no-repeat;
        top: 30px;
        left: 25px;
        cursor: pointer;
        font-size: 50px;
        color: #fff;
        font-family: 'Gloria Hallelujah', cursive;
    }

    .ust{
        height: 190px;
        width: 100%;
        background-image: url("/bgimages/ust.png");
        background-repeat: repeat-x;

    }



</style>
