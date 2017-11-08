@extends('front.layouts.front')

@section('content')
    <div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
        @if(isset($baslik))
            <div class="panel ag-front-baslik-kutusu">
               <h1> {{$baslik}}</h1>
                <i class="fa fa-home fa-2x ag-anasayfa" aria-hidden="true"></i>
            </div>
        @endif

        @if(Session::has('mesaj'))
            <div class="alert alert-success ag-alert">
                {{Session::get('mesaj')}}
            </div>
        @endif
        @if(Session::has('alert'))
            <div class="alert alert-danger ag-alert">
                {{Session::get('alert')}}
            </div>
        @endif

        <div class="panel ag-front-panel col-md-12">
            <div class="ag-aciklama">
            <h2>Duello Nedir ? </h2>
            Duello akiplerin sırayla soru soruduğu ikili mücadele türünde bir yarışma.<br>
            Zor sorular sorun ve rakiplerinizin altınlarını kapın.
                <br>Size sorulan soruları cevaplayın, rakiplerinizin altınlarına el koyun.

            </div>
            <h2>Bunlara cevap ver</h2>
            <div id="ag-sira-bende" class="row duellolar-kutusu"></div>
            <h2>Sordukların</h2>
            <div id="ag-sira-rakipte" class="row duellolar-kutusu"></div>

            <h2> Yeni duello için rakip seç</h2>
            <div id="ag-rakip-sec-liste" class="row  duellolar-kutusu"></div>
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
                    <div id="ag-sayac"></div>
                </div>
                <div class="modal-body">
                    <div id="ag-duello-bilgisi">
                        <table class="table borderless">
                            <tr>
                                <td style="vertical-align: top;width: 250px">
                                    <img class="ag-profil-resmi" src="">
                                </td>
                                <td>
                                    <div class="ag-name">
                                    </div>
                                    <div class="ag-odul">
                                    </div>
                                    <div class="ag-uyari">
                                    </div>
                                    <div class="btn btn-primary" id="ag-soruyu-goster"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Soruyu Göster </div>
                                </td>
                            </tr>
                        </table>
                        <div style="font-size: 12px;color:#f00;float: right">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                            Soruyu göster dedikten sonra geri sayım başlayacak.
                        </div>
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
                        <table class="table borderless">
                            <tr >
                                <td style="vertical-align: top;width: 250px">
                                    <img class="ag-profil-resmi" src="">
                                </td>
                                <td>
                                    <div class="ag-name">
                                    </div>
                                    <div class="ag-odul">
                                    </div>
                                    <div class="ag-uyari"></div>
                                    <div class="btn btn-primary" id="ag-soruyu-goster"> Soruyu Göster </div>
                                </td>
                            </tr>
                        </table>
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
        // arkadaş listesini alalım
        $(document).ready(function () {
            getArkadasListesi();
            duellolariGetir();

            // duello kutusuna tıklanınca detay penceresi açılsın
            onClickKutu();

            // soruyu göser
            onClickSoruyuGoster();

            // seçeneklere tıklanıca cevap verilmiş olacak
            onClickSecenek();
        })
        
        function getArkadasListesi() {
            $.ajax({
                url     : '/uyelik/getArkadasListesi/',
                dataType: 'json',
                success : function(veri) {
                    console.log(veri);
                    $.each( veri, function( index, value ){
                        if(value['profil_resmi']==null){
                            resimyolu = '/images/noimage.png';
                        }else{
                            resimyolu   = value['profil_resmi'];
                        }
                        kutu = '<div class="ag-arkadas-kutu" user_id="'+ value['id'] +'" >' +
                                '<img src="'+  resimyolu +'">'+
                                '<div class="ag-arkadas-adi">'+ value['name'] +'</div>'
                                '</div>';
                        $('#ag-rakip-sec-liste').append(kutu)
                    });
                },
                error   : function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }

        // rakibin resim kutusuna tıklanınca modalda detaylar ıksın
        var rakip_user_id=null;
        $('#ag-rakip-sec-liste').on('click','.ag-arkadas-kutu',function () {
            rakip_user_id = $(this).attr('user_id');
            url="/duello/bunusec/"+rakip_user_id;
            window.location.href=url;
        })


        $('#agDuelloDetayModal').on('hidden.bs.modal', function () {
            $('#ag-sayac').hide();
        })


        function duellolariGetir() {
            $.ajax({
                url: '/duello/getUserDuellos/',
                dataType: 'json',
                beforeSend: function() {
                    islemBar.show();
                },
                complete: function() {
                    islemBar.hide();
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
                    if(duellos['bana_gelenler'].length==0){
                        $("#ag-sira-bende").html("<div style='width: 100%;padding: 50px;text-align: center'>Size sorulan bir soru yok.</div>");
                    }

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

                    if(duellos['rakibe_sorduklarim'].length==0){
                        $("#ag-sira-rakipte").html("<div style='width: 100%;padding: 50px;text-align: center'>Rakibinize bir soru önderin. Listeniz şimdilik boş.</div>");
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }

        var duello_id=null;
        function onClickKutu() {
            $('#ag-sayac').hide();
            // bana gelenlere tıklanınca
            $('.panel').on('click','.ag-kutu-bana-gelenler',function () {
                bukutu = $(this);
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
                            $('#agMesajBoxModal').modal('show')
                            $('#agMesajBoxModal .modal-body').html("<img src='/bgimages/zamanbitti.gif'>")
                            $('*[duello_id='+duello_id+']').hide();
                            bukutu.hide();
                        }else {
                            if (veri['profil_resmi'] == null) {
                                veri['profil_resmi'] = '/images/noimage.png'
                            }
                            $('#agDuelloDetayModal').modal('show');
                            $('#agDuelloDetayModal .ag-profil-resmi').attr('src', veri['profil_resmi'])
                            $('#agDuelloDetayModal .ag-name').html("Soruyu gönderen <br> <div class='ag-gonderen'>" + veri['name']+"</div>")
                            $('#agDuelloDetayModal .ag-odul').html("Ödül   <br> <div class='ag-duello-odulu'>" + veri['odul']+" Altın</div>")
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
                bukutu = $(this);
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
                            $('#agGonderilenDuelloDetayModal .ag-name').html("Kime  <br> <div class='ag-gonderen'>" + veri['name']+"</div>")
                            $('#agGonderilenDuelloDetayModal .ag-odul').html("Ödül  <br> <div class='ag-duello-odulu'>" + veri['odul']+" Altın</div>")
                            $('#agGonderilenDuelloDetayModal #ag-soruyu-goster').attr('duello_id', duello_id);

                            $('#agGonderilenDuelloDetayModal #ag-duello-bilgisi').show();
                            $('#agGonderilenDuelloDetayModal #ag-soru').hide();
                        }else if(veri['kazandin']==1){
                            $('#agMesajBoxModal').modal('show')
                            $('#agMesajBoxModal .modal-body').html('<img src="/bgimages/kazandin1.gif">')
                            $('#agMesajBoxModal .modal-title').html('Kazandınız !')
                            bukutu.hide();
                        }else{
                            $('#agMesajBoxModal').modal('show')
                            $('#agMesajBoxModal .modal-body').html('<img src="/bgimages/kaybettin1.gif">')
                            $('#agMesajBoxModal .modal-title').html('Kaybettiniz !')
                            bukutu.hide();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })


        }

        var kalan_zaman=0;
        function onClickSoruyuGoster() {
            // gelen soruyü göser
            $('#agDuelloDetayModal #ag-soruyu-goster').click(function () {
                $('#ag-sayac').show();
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
                            $('#ag-sayac').html(veri['kalan_zaman']);
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
                            $('#agDuelloDetayModal').modal('hide')
                            $('#agMesajBoxModal').modal('show')
                            $('#agMesajBoxModal .modal-body').html('Burada başarı yada kaybetme ekranı olacak'+veri['sonuc'])
                            $('#agMesajBoxModal .modal-title').html('UYARI !')
                        }else{
                            $('#agGonderilenDuelloDetayModal #ag-metin').html(veri['hata'])
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
                        $('#agDuelloDetayModal').modal('hide')
                        $('#agMesajBoxModal').modal('show')
                        $('*[duello_id='+duello_id+']').hide();
                        if(veri['sonuc']=='dogru'){
                            $('#agMesajBoxModal .modal-body').html('<img src="/bgimages/kazandin1.gif">')
                        }else{
                            $('#agMesajBoxModal .modal-body').html('<img src="/bgimages/kaybettin1.gif">')
                        }
                        $('#agMesajBoxModal .modal-title').html('UYARI !')
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })

        }


    </script>

    <style>
        .ag-arkadas-kutu {
            position: relative;
            width: 150px;
            height: 170px;
            display: block;
            float: left;
            background-color: #b8d4f1;
            margin: 20px;
            border: 5px solid #609db8;
            cursor: pointer;
            text-align: center;
        }

        .ag-arkadas-kutu img{
            width: 100%;
            border: 1px solid #ccc;
            float: left;
            padding: 10px;
            max-height: 100%;
        }

        .ag-arkadas-kutu:hover{
            border: 5px solid #335466;
        }

        .ag-arkadas-adi{
            position: absolute;
            bottom: 0px;
            width: 100%;
            text-align: center;
            font-size: 14px;
            background-color: #6aadcb;
            text-shadow: 1px 1px 1px #000;
            font-family: 'Gloria Hallelujah', cursive;
        }

        .ag-rakip-sec-liste{
            background-color: #0b404b;
            border: 1px solid #14768a;
        }



/*--------------------------------------------------------------*/
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
            width: 100%!important;
        }

        .modal-body #ag-metin img{
            width: 100%!important;
        }

        .modal-body  .ag-profil-resmi{
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

        #ag-soruyu-goster{
            left:35px;
            position: relative;
            margin-top: 50px;
        }

        .ag-gonderen{
            font-family:'Gloria Hallelujah', cursive;
            color: #80b9c9;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .ag-duello-odulu{
            font-size: 40px;
            font-family: Anton;
            text-shadow: 2px 2px 2px #fff;
        }

        #ag-sira-rakipte{
            min-height: 200px;
        }

        #ag-sayac{
            float: right;
            position: absolute;
            right: 50%;
            top: 6px;
            font-size: 30px;
            font-family: monospace;
            color: #ccc;
        }

    </style>
@endsection
