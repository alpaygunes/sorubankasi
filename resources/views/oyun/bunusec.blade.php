@extends('front.layouts.front')

@section('content')
<div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
    @if(isset($baslik))
        <div class="panel ag-front-baslik-kutusu">
            <h1>{{$baslik}}</h1>
            <div style="position: absolute;right: 40px;top: 40px">
                <a href="/duello" style="color: #fff!important;;font-size: 16px!important;">Geri</a></div>
        </div>
    @endif

    <div class="panel ag-front-panel col-md-12">
        <div id="ag-rakibiniz"><span class="ag-rakip-adi">{{$rakip_bilgileri->name}}'a bir soru gönder</span></div>
        <h2>Göndermek istediğiniz soru için bir seviye seçin.</h2>
        <div id="ag-mesaj" class="ag-aciklama"></div>
        <div id="ag-gonder" class="btn btn-primary" style="display: none">
            <i class="fa fa-rocket fa-2x" aria-hidden="true"></i>
            Gönder</div>
<br>
        <div id="ag-soru-seviyesi-dugmleri" class="row duellolar-kutusu">
            <div class="ag-seviye-dugme" seviye="cok_kolay" id="ag-cok-kolay-btn">
                <div class="seviye-txt">Çok kolay</div>
            </div>
            <div class="ag-seviye-dugme" seviye="kolay" id="ag-kolay-btn">
                <div class="seviye-txt">Kolay</div>
            </div>
            <div class="ag-seviye-dugme" seviye="normal" id="ag-normal-btn">
                <div class="seviye-txt">Normal</div>
            </div>
            <div class="ag-seviye-dugme" seviye="zor" id="ag-zor-btn">
                <div class="seviye-txt">Zor</div>
            </div>
            <div class="ag-seviye-dugme" seviye="cok_zor" id="ag-cok-zor-btn">
                <div class="seviye-txt">Çok zor</div>
            </div>
        </div>


        <a href="/varliklarim" class="btn btn-success ag-soru-satinal" >
            <i class="fa fa-shopping-cart" style="margin-right: 20px" aria-hidden="true"></i>
            Soru satın alın</a>

    </div>

</div>


<script>
    $(document).ready(function () {
        $sorumlarim = getSorularim();
    })

    function getSorularim() {
        //$('.ag-seviye-dugme').empty();
        $.ajax({
            url: '/varliklarim/json',
            dataType: 'json',
            beforeSend: function() {
                islemBar.show();
            },
            complete: function() {
                islemBar.hide();
            },
            success: function(veri) {
               console.log(veri)
                zorlukArr = {cok_kolay:0, kolay:0, normal:0,zor:0,cok_zor:0};
                $.each( veri['sorular'], function( index, value ) {
                    if(value['zorluk']==1){
                        zorlukArr['cok_kolay']++;
                    }else if(value['zorluk']==2){
                        zorlukArr['kolay']++;
                    }else if(value['zorluk']==3){
                        zorlukArr['normal']++;
                    }else if(value['zorluk']==4){
                        zorlukArr['zor']++;
                    }else if(value['zorluk']==5){
                        zorlukArr['cok_zor']++;
                    }
                })
                $('#ag-cok-kolay-btn').prepend(zorlukArr['cok_kolay']);
                $('#ag-cok-kolay-btn').attr('soru_sayisi',zorlukArr['cok_kolay']);
                $('#ag-kolay-btn').prepend(zorlukArr['kolay']);
                $('#ag-kolay-btn').attr('soru_sayisi',zorlukArr['kolay']);
                $('#ag-normal-btn').prepend(zorlukArr['normal']);
                $('#ag-normal-btn').attr('soru_sayisi',zorlukArr['normal']);
                $('#ag-zor-btn').prepend(zorlukArr['zor']);
                $('#ag-zor-btn').attr('soru_sayisi',zorlukArr['zor']);
                $('#ag-cok-zor-btn').prepend(zorlukArr['cok_zor']);
                $('#ag-cok-zor-btn').attr('soru_sayisi',zorlukArr['cok_zor']);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    var seviye_int ='';
    $('.ag-seviye-dugme').click(function () {
        seviye      = $(this).attr('seviye');
        seviye_txt  = '';
        soru_sayisi = $(this).attr('soru_sayisi');
        if(seviye=="cok_kolay"){
            seviye_txt  = "çok kolay";
            seviye_int  = 1;
        }else if(seviye=="kolay"){
            seviye_txt  = "kolay";
            seviye_int  = 2;
        }else if(seviye=="normal"){
            seviye_txt  = "normal seviye";
            seviye_int  = 3;
        }else if(seviye=="zor"){
            seviye_txt  = "zor";
            seviye_int  = 4;
        }else if(seviye=="cok_zor"){
            seviye_txt  = "çok zor";
            seviye_int  = 5;
        }
        mesaj = "Rakibinize "+ seviye_txt +" bir soru göndereceksiniz." +
            "<br> Bilemezse rakibinizin altınlarının bir kısmını alacaksınız." +
            "Doğru cevap verirse  altınlarınızın bir kısmını kaybedeceksiniz.<br>"

        if(soru_sayisi==0){
            mesaj ="Bu seviyede hiç sorunuz yok.";
            $('#ag-gonder').hide()
        }else{
            $('#ag-gonder').show()
        }
        $('#ag-mesaj').html(mesaj);
    })

    $('#ag-gonder').click(function () {
        $.ajax({
            url: '/duello/rakibebirosugonder/'+seviye_int,
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(veri) {
                console.log(veri);
                if(veri['hata']=='sira_rakipte'){
                    //alert("Soru sorma sırası rakibinizde");
                    $('#agMesajBoxModal').modal('show')
                    $('#agMesajBoxModal .modal-body').html('Daha önce gönderilmiş bir sorunuz var.<br> Rakibinizin cevaplaması bekleniyor.')
                    $('#agMesajBoxModal .modal-title').html('UYARI !')

                }else{
                    $('#agMesajBoxModal').modal('show')
                    $('#agMesajBoxModal .modal-body').html('Soru gönderildi.')
                    $('#agMesajBoxModal .modal-title').html('Mesaj !')
                    getSorularim();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    })

</script>

    
<style>

    #ag-soru-seviyesi-dugmleri{
        padding: 20px;
    }
    .ag-seviye-dugme{
        width: 100px;
        height: 100px;
        margin: 25px;
        float: left;
        border: 3px solid #3b81cc;
        background-size: 100%;
        background-repeat: no-repeat;
        background-color: #b8d4f1;
        border: 5px solid #609db8;
        text-align: center;
        font-size: 50px;
        font-family: Anton;
        vertical-align: middle;
        text-shadow: 1px 1px 1px #000;
        padding-top: 15px;
        border-radius: 50px;
        cursor: pointer;
    }

    .seviye-txt{
        font-family: 'Gloria Hallelujah', cursive;
        font-size: 15px;
        position: relative;
        width: 100%;
        text-align: center;
    }
    
    
    #ag-kolay-btn{
        background-image: url("/bgimages/kolay.png");
    }

    #ag-cok-kolay-btn{
        background-image: url("/bgimages/cokkolay.png");
    }

    #ag-normal-btn{
        background-image: url("/bgimages/normal.png");
    }

    #ag-zor-btn{
        background-image: url("/bgimages/zor.png");
    }

    #ag-cok-zor-btn{
        background-image: url("/bgimages/cokzor.png");
    }


    #ag-mesaj{
        margin-top: 50px;
    }

    #ag-gonder{
        margin-top: 20px;
        margin-bottom:20px;
        font-size: 18px;
        font-family: Anton;
    }

    #ag-rakibiniz{

    }

    .ag-rakip-adi{
    }

    .ag-soru-satinal{
        font-family: Anton;
        font-size: 16px;
    }






</style>


@endsection
