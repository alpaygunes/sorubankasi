@extends('front.layouts.front')

@section('content')
<div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
    @if(isset($baslik))
        <div class="panel ag-front-baslik-kutusu">
            {{$baslik}}
        </div>
    @endif

    <div class="panel ag-front-panel col-md-12">
        <div>Rakibiniz : {{$rakip_bilgileri->name}}</div>
        <div id="ag-soru-seviyesi-dugmleri" class="row">
            <div>Sorularınızın zorluk seviyelerine göre sayıları</div>
            <div class="ag-seviye-dugme" seviye="cok_kolay" id="ag-cok-kolay-btn"></div>
            <div class="ag-seviye-dugme" seviye="kolay" id="ag-kolay-btn"></div>
            <div class="ag-seviye-dugme" seviye="normal" id="ag-normal-btn"></div>
            <div class="ag-seviye-dugme" seviye="zor" id="ag-zor-btn"></div>
            <div class="ag-seviye-dugme" seviye="cok_zor" id="ag-cok-zor-btn"></div>
        </div>
        <div id="ag-mesaj"></div>
        <div id="ag-gonder" class="btn btn-primary" style="display: none">Soruyu Rakibine Gönder</div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $sorumlarim = getSorularim();
    })

    function getSorularim() {
        $.ajax({
            url: '/varliklarim/json',
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

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
                $('#ag-cok-kolay-btn').html(zorlukArr['cok_kolay']);
                $('#ag-cok-kolay-btn').attr('soru_sayisi',zorlukArr['cok_kolay']);
                $('#ag-kolay-btn').html(zorlukArr['kolay']);
                $('#ag-kolay-btn').attr('soru_sayisi',zorlukArr['kolay']);
                $('#ag-normal-btn').html(zorlukArr['normal']);
                $('#ag-normal-btn').attr('soru_sayisi',zorlukArr['normal']);
                $('#ag-zor-btn').html(zorlukArr['zor']);
                $('#ag-zor-btn').attr('soru_sayisi',zorlukArr['zor']);
                $('#ag-cok-zor-btn').html(zorlukArr['cok_zor']);
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
        }else if(seviye=="zok_zor"){
            seviye_txt  = "çok zor";
            seviye_int  = 5;
        }
        mesaj = "Rakibinize "+ seviye_txt +" bir soru göndereceksiniz." +
            "<br> Bilemezse rakibinizin altınkarından bir kısmını alacaksınız." +
            "Doğru cevap verirse sizin altınlarınızın bir kısmını kazacak.<br>"

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
                    alert("Soru sorma sırası rakibinizde");
                }else{
                    alert("soru gönderildi")
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    })

</script>

    
<style>
    .ag-seviye-dugme{
        width: 100px;
        height: 125px;
        margin: 25px;
        padding: 10px;
        float: left;
        border: 1px solid #ccc;
    }
    
</style>

@endsection
