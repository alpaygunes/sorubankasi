@extends('front.layouts.front')

@section('content')

<div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
        @if(isset($baslik))
            <div class="panel ag-front-baslik-kutusu">
                {{$baslik}}
            </div>
        @endif
        <div class="panel ag-front-panel col-md-12">
            <div>Hamle bekleyen duellolarım</div>
            <div id="ag-sira-bende" class="row"></div>
            <div>Hamle yaptığım duellolarım</div>
            <div id="ag-sira-rakipte" class="row"></div>
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
            width: 90px;
            height: 100px;
            display: block;
            float: left;
            border: 1px solid #ccc;
            padding: 5px;
        }


        .ag-kutu-gonderiklerim{
            width: 90px;
            height: 100px;
            display: block;
            float: left;
            border: 1px solid #ccc;
            padding: 5px;
        }

        .ag-profil-resmi{
            width: 75px;
            border: 1px solid #ccc;
            float: left;
            padding: 10px;
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

    </style>

@endsection
