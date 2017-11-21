@extends('front.layouts.front')

@section('content')
    <div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
        @if(isset($baslik))
            <div class="panel ag-front-baslik-kutusu">
               <h1> {{$baslik}}</h1>
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
                    <div id="ag-soru-odulu"></div>
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

    <audio id="ses-sihir">
        <source src="/sesler/sihir.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
    <audio id="ses-yanlis">
        <source src="/sesler/yanlis.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>













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

        // rakibin resim kutusuna tıklanınca modalda detaylar çıksın
        var rakip_user_id=null;
        $('#ag-rakip-sec-liste').on('click','.ag-arkadas-kutu',function () {
            rakip_user_id = $(this).attr('user_id');
            url="/duello/bunusec/"+rakip_user_id;
            window.location.href=url;
        })


        $('#agDuelloDetayModal').on('hidden.bs.modal', function () {
            $('#ag-sayac').hide();
            window.clearInterval(sayac_id);
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
        var modal_obj;
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
                        duello_odul     = veri['duello_odul'];
                        if(veri['hata']){
                            $('#agMesajBoxModal').modal('show')
                            $('#agMesajBoxModal .modal-body').html("<img src='/bgimages/zamanbitti"+  Math.floor((Math.random() * 4) + 1) +".gif'>")
                            $('#agMesajBoxModal .modal-title').html('KAYBETTİN !')
                            $('#agMesajBoxModal .modal-body').append('<div id="ag-kaybettin-orta"></div>')
                            $('#agMesajBoxModal .modal-footer').html("<div class='ag-gonderen'>" + veri['gonderen_adi']+" senin altınlarını kaptı </div>")
                            kaybettin_animasyonu();
                            $('*[duello_id='+duello_id+']').hide();
                            bukutu.hide();
                        }else {
                            if (veri['profil_resmi'] == null) {
                                veri['profil_resmi'] = '/images/noimage.png'
                            }
                            $('#agDuelloDetayModal').modal('show');
                            modal_obj = $('#agDuelloDetayModal');
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
                            console.log("-------------- onClickKutu ---------")
                            console.log(veri['hata'])
                        }else if(veri['kazandin']==0) {
                            if (veri['profil_resmi'] == null) {
                                veri['profil_resmi'] = '/images/noimage.png'
                            }
                            $('#agGonderilenDuelloDetayModal').modal('show');
                            $('#agGonderilenDuelloDetayModal .ag-profil-resmi').attr('src', veri['profil_resmi'])
                            $('#agGonderilenDuelloDetayModal .ag-name').html("Rakip  <br> <div class='ag-gonderen'>" + veri['name']+"</div>")
                            $('#agGonderilenDuelloDetayModal .ag-odul').html("Ödül  <br> <div class='ag-duello-odulu'>" + veri['odul']+" Altın</div>")
                            $('#agGonderilenDuelloDetayModal #ag-soruyu-goster').attr('duello_id', duello_id);

                            $('#agGonderilenDuelloDetayModal #ag-duello-bilgisi').show();
                            $('#agGonderilenDuelloDetayModal #ag-soru').hide();
                        }else if(veri['kazandin']==1){
                            $('#agMesajBoxModal').modal('show')
                            $('#agMesajBoxModal .modal-body').html('<img src="/bgimages/kazandin'+resim_no+'.gif">')
                            $('#agMesajBoxModal .modal-body').append("<div class='ag-odul-miktari'>" + veri['odul'] + "</div>")
                            $('#agMesajBoxModal .modal-footer').html("<div class='ag-gonderen'>" + veri['name']+" nın altınlarını kaptın</div>")
                            $('#agMesajBoxModal .modal-title').html('ALTINLARI KAPTIN !')
                            bukutu.hide();
                        }else{
                            $('#agMesajBoxModal').modal('show')
                            $('#agMesajBoxModal .modal-body').html('<img src="/bgimages/kaybettin'+resim_no+'.gif">')
                            $('#agMesajBoxModal .modal-body').append("<div class='ag-odul-miktari'>-" + veri['odul'] + "</div>")
                            $('#agMesajBoxModal .modal-footer').html("<div class='ag-gonderen'>" + veri['name']+" senin altınlarını kaptı </div>")
                            $('#agMesajBoxModal .modal-title').html('KAYBETTİN !')
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
        var sayac_id    = 0;
        var duello_odul    = 0;
        function onClickSoruyuGoster() {
            // gelen soruyü göser
            $('#agDuelloDetayModal #ag-soruyu-goster').click(function () {
                window.clearInterval(sayac_id);
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
                            console.log("------ onClickSoruyuGoster ------")
                            console.log(veri['hata'])
                        }else{
                            $('#ag-metin').html(veri['duello_sorumetni']);
                            kalan_zaman     = veri['kalan_zaman'];
                            duello_odul     = veri['duello_odul'];
                            $('#ag-duello-bilgisi').hide();
                            $('#ag-soru-odulu').html(duello_odul + ' Altın');
                            $('#ag-sayac').show();
                            $('#ag-soru-odulu').show();
                            $('#ag-soru').show();
                            sayac_id = startTimer(kalan_zaman, document.querySelector('#ag-sayac '));
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
                            $('#agMesajBoxModal .modal-body').html(veri['hata'])
                            $('#agMesajBoxModal .modal-title').html('UYARI !')
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




        var resim_no = Math.floor((Math.random() * 11) + 1);
        function onClickSecenek() {
            $('#ag-secenekler').on('click','.ag-secenek',function () {
                window.clearInterval(sayac_id);
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
                            $('#agMesajBoxModal .modal-body').html('<img src="/bgimages/kazandin'+resim_no+'.gif">')
                            $('#agMesajBoxModal .modal-title').html('ALTINLARI KAPTIN !')
                            $('#agMesajBoxModal .modal-body').append('<div id="ag-kazandin-orta"></div>')
                            kazandin_animasyonu();
                        }else{
                            $('#agMesajBoxModal .modal-body').html('<img src="/bgimages/kaybettin'+resim_no+'.gif">')
                            $('#agMesajBoxModal .modal-title').html('KAYBETTİN !')
                            $('#agMesajBoxModal .modal-body').append('<div id="ag-kaybettin-orta"></div>')
                            kaybettin_animasyonu();
                        }

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })

        }

        function kaybettin_animasyonu() {
            $('#ses-yanlis')[0].play();
            (function myLoop (i) {
                setTimeout(function () {
                    i = i - 10;
                    $('#ag-kaybettin-orta').html(-(duello_odul-i));
                    if (i>0) myLoop(i);      //  decrement i and call myLoop again if i > 0
                }, 100)
            })(duello_odul);
        }

        function kazandin_animasyonu() {
            kazandin_gif = "<img id='ag-kazindin-gif' " +
                "style='width:100%'" +
                "src='/bgimages/yildiz_sacilmasi.gif'>"  ;

            $('#ses-sihir')[0].play();

            (function myLoop (i) {
                setTimeout(function () {
                    i = i - 10;
                    $('#ag-kazandin-orta').html(duello_odul-i);
                    if (i>0) myLoop(i);     //  decrement i and call myLoop again if i > 0
                }, 100)
            })(duello_odul);             //  pass the number of iterations as an argument
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




        /*.ag-buyuk-btn{
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
        }*/

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

        #ag-soru-odulu{
            position: absolute;
            right: 0px;
            margin-top: -80px;
            font-size: 27px;
            font-family: Anton;
            color: #ffea10;
            text-shadow: 1px 1px 1px #726e39;
        }

        .modal-dialog{
            margin-top: 100px;
        }

        #ag-kaybettin-orta{
            text-align: center;
            font-size: 120px;
            font-family: Anton;
            color: #ffea10;
            text-shadow: 1px 1px 1px #726e39;
            float: right;
        }

        #ag-kazandin-orta{
            text-align: center;
            font-size: 120px;
            font-family: Anton;
            color: #ffea10;
            text-shadow: 1px 1px 1px #726e39;
            float: right;
        }

        .ag-odul-miktari{
            text-align: center;
            font-size: 120px;
            font-family: Anton;
            color: #ffea10;
            text-shadow: 1px 1px 1px #726e39;
            float: right;
        }

        .modal-footer .ag-gonderen{
            float: left;
        }

    </style>
@endsection
