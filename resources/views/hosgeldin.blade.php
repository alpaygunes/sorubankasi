@extends('front.layouts.front')

@section('content')

    <div class="ust">
        <div class="logo">
        </div>

    </div>

    <div class="content orta">
        <div class="row">
            <div class="kutu sol-text">
                <h1>Eğlenerek öğrenin</h1>
                Soru çözmek size heyecan verecek.
            </div>
            <div class="kutu sag-text">
                <h1>Mücadeleye hazır mısınız ?</h1>
                Rakibini seç. Soru gönder. Altınlarını kap.
            </div>
        </div>

        <div class="btn btn-primary uye-ol">
            Üye Ol
        </div>

    </div>


    <style>
        .navbar{
            display: none;
        }

        body{
            background-color: #156c80;
            color: #fff;
        }

        .container{
            width: 100%!important;
        }

        .logo{
            position: relative;
            height: 150px;
            width: 400px;
            background-image: url("bgimages/logo.png");
            background-repeat: no-repeat;
            top: 30px;
            left: 25px;
        }

        .ust{
            height: 207px;
            width: 100%;;
            background-image: url("bgimages/ust.png");
            background-repeat: no-repeat;
        }

        .orta{
            width: 100%;
            background-image: url(bgimages/masa.png);
            background-repeat: no-repeat;
            background-position-x: center;
            height: 500px;
            text-align: center;
            top: -50px;
            position: relative;
            z-index: -1;
        }

        .kutu{
            width: 275px;
            height: 175px;
            margin: 75px;
            text-align: left;
            font-size: 20px;
        }
        
        .sol-text{
            float: left;
        }

        .sag-text{
            float: right;
        }

        .uye-ol{
            margin-top: 200px;
            font-size: 20px;
            width: 200px;
            background: #4ba1db;
            -webkit-border-radius: 3;
            -moz-border-radius: 3;
            border-radius: 3px;
            -webkit-box-shadow: 0px 1px 3px #666666;
            -moz-box-shadow: 0px 1px 3px #666666;
            box-shadow: 0px 1px 3px #666666;
            font-family: ;
            color: #ffffff;
            font-size: 20px;
            padding: 10px 20px 10px 20px;
            text-decoration: none;
            text-shadow: 1px 1px 1px #000;
        }



        @media screen and (max-width: 850px) {
            .kutu{
                display: none;
            }

            .uye-ol{
                top: 95px;
                position: relative;
            }
        }
    </style>

    <script>
        $('.uye-ol').click(function () {
            window.location.href='/uyelik/uyeol';
        })
    </script>

@endsection





{{--<a href="{{ '/uyelik/giris' }}">Giriş</a>--}}
{{--<a href="{{ '/uyelik/uyeol' }}">Üye ol</a>--}}
