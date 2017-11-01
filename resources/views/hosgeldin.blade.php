@extends('front.layouts.front')

@section('content')

{{--    <div class="ust">
        <div class="logo">
        </div>
        <div class="ag-giris" onclick="window.location.href='/uyelik/giris'">
            Giriş
        </div>

    </div>--}}


<div class="ag-giris" onclick="window.location.href='/uyelik/giris'">
    Giriş
</div>

    <div class="orta row">
            <div class="kutu sol-text">
                <h1>Eğlenerek öğrenin</h1>
                Soru çözmek size heyecan verecek.
            </div>
            <div class="kutu sag-text">
                <h1>Mücadeleye hazır mısınız ?</h1>
                Rakibini seç. Soru gönder. Altınlarını kap.
            </div>
    </div>

    <div class="alt">
        <div class="btn btn-primary uye-ol">
            Üye Ol
        </div>
    </div>


    <style>

        .navbar{
            display: none;
        }

        body{
            background-color: #156c80!important;
        }

        .orta{
            width: 100%;
            background-image: url("/bgimages/masa.png");
            background-repeat: no-repeat;
            background-position-x: center;
            height: 500px;
            text-align: center;
            top: -50px;
            position: relative;
            z-index: -1;
            padding: 50px;
            max-width: 1900px;
        }

        .kutu{
            position: relative;
            width: 275px;
            height: 175px;
            text-align: left;
            font-size: 20px;
            color: #fff;
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
            position: relative;
            top: -220px;
        }

        .ag-giris{
            float: right;
            position: relative;
            top: -165px;
            right: 50px;
            font-size: 16px;
            border: 1px solid #fff;
            width: 150px;
            text-align: center;
            cursor: pointer;
            color: #fff;
        }

        .alt{
            position: relative;
            width: 100%;
            display: block;
            text-align: center;
            height: 50px;
        }

        @media screen and (max-width: 850px) {
            .kutu{
                display: none;
            }
            .orta{
                background-size: 100%!important;
            }
            .uye-ol{
                top: -450px;
                position: relative;
            }
        }


        @media screen and (max-width: 1260px) {
            .orta{
                background-size: 50%;
            }
            .kutu{
                font-size: 15px;
            }
            .kutu h1{
                font-size: 1.8em;
            }

            .uye-ol{
                top:-350px;
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
