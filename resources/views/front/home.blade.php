@extends('front.layouts.front')

@section('content')

<div class="col-sm-12 col-md-10 col-lg-10 col-xl-10" >
        @if(isset($baslik))
            <div class="panel ag-front-baslik-kutusu">
                {{$baslik}}
            </div>
        @endif
        <div class="panel ag-front-panel col-md-12">
            <div class="ag-dugmeler row">

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
            <h1>Bunlara cevap ver</h1>
            <div id="ag-sira-bende" class="row duellolar-kutusu"></div>
            <h2>Sordukların</h2>
            <div id="ag-sira-rakipte" class="row duellolar-kutusu"></div>
        </div>
</div>



<!-- GELEN DUELLO DETAYI MODAL -->
<div id="agDuelloDetayModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Duello</h4>
            </div>
            <div class="modal-body">
                <div id="ag-duello-bilgisi">
                    <img class="ag-profil-resmi" src="">
                    <div class="ag-name">
                    </div>
                    <div class="ag-odul">
                    </div>
                    <div class="ag-uyari"> 24 saat içinde soruya bakmak zorundasınız. Soruyu göster dedikten sonra iptal edemezsiniz.</div>
                    <div class="btn btn-primary" id="ag-soruyu-goster"> Soruyu Göster </div>
                </div>

                <div id="ag-soru">
                    <div id="ag-metin">

                    </div>

                    <div id="ag-secenekler">
                        <div class="ag-secenek" cevap="a">A</div>
                        <div class="ag-secenek" cevap="b">B</div>
                        <div class="ag-secenek" cevap="c">C</div>
                        <div class="ag-secenek" cevap="d">D</div>
                        <div class="ag-secenek" cevap="e">E</div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
            </div>
        </div>

    </div>
</div>



<!-- GÖNDERİLEN DUELLO DETAYI MODAL -->
<div id="agGonderilenDuelloDetayModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Duello</h4>
            </div>
            <div class="modal-body">
                <div id="ag-duello-bilgisi">
                    <img class="ag-profil-resmi" src="">
                    <div class="ag-name">
                    </div>
                    <div class="ag-odul">
                    </div>
                    <div class="ag-uyari"> Sonuç ? </div>
                    <div class="btn btn-primary" id="ag-soruyu-goster"> Soruyu Göster </div>
                </div>

                <div id="ag-soru">
                    <div id="ag-metin">

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
            </div>
        </div>

    </div>
</div>







    <script>
        $(document).ready(function () {
            duellolariGetir();

            // duello kutusuna tıklanınca detay penceresi açılsın
            onClickKutu();

            // soruyu göser
            onClickSoruyuGoster();

            // seçeneklere tıklanıca cevap verilmiş olacak
            onClickSecenek();
        })

        function duellolariGetir() {
            $.ajax({
                url: '/duello/getUserDuellos/',
                dataType: 'json',
                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(duellos) {
                    console.log(duellos)
                    $.each( duellos['bana_gelenler'], function( ind, val ){
                        if(val['profil_resmi']==null){
                            val['profil_resmi']='/images/noimage.png'
                        }
                        btn_duello =  document.createElement('div');
                        $(btn_duello).attr('duello_id',val['id']);
                        $(btn_duello).addClass('ag-kutu-bana-gelenler');
                        $(btn_duello).html('<img class="ag-profil-resmi" src="'+ val['profil_resmi'] +'">');
                        $(btn_duello).append('<div class="ag-profil-resmi-kizmizi-vs"></div>');
                        $("#ag-sira-bende").append($(btn_duello));
                    });

                    $.each( duellos['rakibe_sorduklarim'], function( ind, val ){
                        btn_duello =  document.createElement('div');
                        kazanan = null;
                        if(val['kazandin']==1){
                            kazanan ='kazandin';
                            $(btn_duello).addClass('ag-kazandin');
                        }
                        $(btn_duello).attr('duello_id',val['duello_id']);
                        $(btn_duello).attr('kazanan',kazanan);
                        $(btn_duello).addClass('ag-kutu-gonderiklerim');
                        $(btn_duello).html('<img class="ag-profil-resmi" src="'+ val['profil_resmi'] +'">');
                        $(btn_duello).append('<div class="ag-profil-resmi-sari-vs"></div>');
                        $("#ag-sira-rakipte").append($(btn_duello));
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }

        function onClickKutu() {
            // bana gelenlere tıklanınca
            $('.panel').on('click','.ag-kutu-bana-gelenler',function () {
                duello_id= $(this).attr('duello_id');
                $.ajax({
                    url: '/duello/genelenHamleyiGor/'+duello_id,
                    dataType: 'json',
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(veri) {
                        if(veri['hata']){
                            alert(veri['hata'])
                        }else {
                            if (veri['profil_resmi'] == null) {
                                veri['profil_resmi'] = '/images/noimage.png'
                            }
                            $('#agDuelloDetayModal').modal('show');
                            $('#agDuelloDetayModal .ag-profil-resmi').attr('src', veri['profil_resmi'])
                            $('#agDuelloDetayModal .ag-name').html("Soruyu gönderen " + veri['name'])
                            $('#agDuelloDetayModal .ag-odul').html("Duello ödülü  " + veri['odul'])
                            $('#agDuelloDetayModal #ag-soruyu-goster').attr('duello_id', duello_id);

                            $('#agDuelloDetayModal #ag-duello-bilgisi').show();
                            $('#agDuelloDetayModal #ag-soru').hide();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })

            // gönderiklerime tıklanınca
            $('.panel').on('click','.ag-kutu-gonderiklerim',function () {
                duello_id= $(this).attr('duello_id');
                $.ajax({
                    url: '/duello/gonderdigimHamleyiGor/'+duello_id,
                    dataType: 'json',
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(veri) {
                        console.log(veri)
                        if(veri['hata']){
                            alert(veri['hata'])
                        }else if(veri['kazandin']==0) {
                            if (veri['profil_resmi'] == null) {
                                veri['profil_resmi'] = '/images/noimage.png'
                            }
                            $('#agGonderilenDuelloDetayModal').modal('show');
                            $('#agGonderilenDuelloDetayModal .ag-profil-resmi').attr('src', veri['profil_resmi'])
                            $('#agGonderilenDuelloDetayModal .ag-name').html("Kime  " + veri['name'])
                            $('#agGonderilenDuelloDetayModal .ag-odul').html("Duello ödülü  " + veri['odul'])
                            $('#agGonderilenDuelloDetayModal #ag-soruyu-goster').attr('duello_id', duello_id);

                            $('#agGonderilenDuelloDetayModal #ag-duello-bilgisi').show();
                            $('#agGonderilenDuelloDetayModal #ag-soru').hide();
                        }else if(veri['kazandin']==1){
                            alert("kazandin")
                        }else{
                            alert("Kaybettin")
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })


        }

        function onClickSoruyuGoster() {
            // gönderilen soruyü göser
            $('#agDuelloDetayModal #ag-soruyu-goster').click(function () {
                duello_id= $(this).attr('duello_id');
                $.ajax({
                    url: '/duello/soruyuGoster/sureyi_baslat',
                    dataType: 'json',
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(veri) {
                        console.log(veri)
                        if(veri['hata']){
                            alert(veri['hata'])
                        }else{
                            $('#ag-metin').html(veri['duello_sorumetni'])
                            $('#ag-duello-bilgisi').hide();
                            $('#ag-soru').show();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })


            // gönderidiğim soruya tıklayınca
            $('#agGonderilenDuelloDetayModal #ag-soruyu-goster').click(function () {
                duello_id= $(this).attr('duello_id');
                $.ajax({
                    url: '/duello/soruyuGoster/onizle',
                    dataType: 'json',
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(veri) {
                        console.log(veri)
                        if(veri['hata']){
                            alert(veri['hata'])
                        }else{
                            $('#agGonderilenDuelloDetayModal #ag-metin').html(veri['duello_sorumetni'])
                            $('#agGonderilenDuelloDetayModal #ag-duello-bilgisi').hide();
                            $('#agGonderilenDuelloDetayModal #ag-soru').show();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })


        }

        function onClickSecenek() {
            $('#ag-secenekler').on('click','.ag-secenek',function () {
                cevap = $(this).attr('cevap');
                $.ajax({
                    url: '/duello/cevapKontrol/'+cevap,
                    dataType: 'json',
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(veri) {
                        console.log(veri)
                        alert(veri['sonuc'] + " Burada başarı ekranı gelecek yada başarısızlık. altınlar birinden diğerine geçecek" )
                        $('#agDuelloDetayModal').modal('hide')
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })
        }

    </script>

    <style>

        .ag-kutu-bana-gelenler{
            position: relative;
            width: 150px;
            height: 170px;
            display: block;
            float: left;
            background-color: #b8d4f1;
            margin: 20px;
            border: 5px solid #609db8;
            cursor: pointer;
        }
        .ag-kutu-bana-gelenler:hover{
            border: 5px solid #335466;
        }


        .ag-kutu-gonderiklerim{
            position: relative;
            width: 150px;
            height: 170px;
            display: block;
            float: left;
            background-color: #b8d4f1;
            margin: 20px;
            border: 5px solid #609db8;
            cursor: pointer;
        }
        .ag-kutu-gonderiklerim:hover{
            border: 5px solid #0b404b;
        }

        .ag-profil-resmi{
            width: 100%;
            border: 1px solid #ccc;
            float: left;
            padding: 10px;
            max-height: 100%;;
        }

        .ag-profil-resmi-kizmizi-vs{
            position: absolute;
            width: 100px;
            height: 100px;
            display: block;
            float: right;
            right: 0;
            background-image: url("/bgimages/duello-kirmizi.png");
            background-size: 100%;
            background-repeat: no-repeat;
        }

        .ag-profil-resmi-sari-vs{
            position: absolute;
            width: 100px;
            height: 100px;
            display: block;
            float: right;
            right: 0;
            background-image: url("/bgimages/duello-sari.png");
            background-size: 100%;
            background-repeat: no-repeat;
        }

        .ag-name{
            position: relative;
            left:35px;
         }

        .ag-odul{
            position: relative;
            left:35px;
        }

        .ag-uyari{
            position: relative;
            left:35px;
        }

        .modal-body {
            display: inline-block;
        }

        .modal-body img:not(.ag-profil-resmi){
            width: 100%!important;
        }

        .ag-secenek{
            float: left;
            width: 32px;
            margin: 5px;
            padding: 5px;
            border-radius: 30px;
            border: 1px solid #ccc;
            text-align: center;
            cursor: pointer;
        }

        .ag-kazandin{
            border-color: red;
        }


        .ag-dugmeler{
            margin-bottom: 0px;
            border-bottom:10px solid #2f4f4f;
        }

        .ag-buyuk-btn{
            position: relative;
            width: 150px;
            height: 160px;
            background-repeat: no-repeat;
            display: block;
            float: left;
            margin: 20px;
            cursor: pointer;
        }

        #ag-buyuk-btn-soru-coz{
            width: 150px;
            height: 160px;
            background-image: url("/bgimages/bt-soru-coz.png");
            background-size: 100%;
            position: relative;
            display: block;
        }

        #ag-buyuk-btn-varliklarim{
            width: 150px;
            height: 160px;
            background-image: url("/bgimages/bt-varliklarim.png");
            background-size: 100%;
            position: relative;
            display: block;
        }

        #ag-buyuk-btn-duello{
            width: 150px;
            height: 160px;
            background-image: url("/bgimages/bt-duello.png");
            background-size: 100%;
            position: relative;
            display: block;
        }



        #ag-buyuk-btn-arkadaslarim{
            width: 150px;
            height: 160px;
            background-image: url("/bgimages/bt-arkadaslarim.png");
            background-size: 100%;
            position: relative;
            display: block;
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

@endsection
