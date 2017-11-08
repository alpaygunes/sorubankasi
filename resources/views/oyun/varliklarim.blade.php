@extends('front.layouts.front')

@section('content')

    <div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
        @if(isset($baslik))
            <div class="panel ag-front-baslik-kutusu">
               <h1> {{$baslik}} </h1>
                <i class="fa fa-home fa-2x ag-anasayfa" aria-hidden="true"></i>
            </div>
        @endif
            <div class="panel ag-front-panel col-md-12">

                <h2>Altınlarım</h2>
                    <div class="ag-odullerim duellolar-kutusu">
                        <?php $odullerArr=$varliklarimArr['oduller'] ?>
                        @foreach ( $odullerArr as $key => $value )
                            @if($value->miktar==0)
                                Hiç {{ $value->odul_turu }} ödülüz yok, {{ $value->odul_turu }} kazanmak için soru çözün.
                            @else
                                {{--{{ $value->odul_turu }}--}}
                                <div class="ag-odul-txt">{{ $value->miktar }}</div>
                            @endif
                        @endforeach
                        @if($odullerArr->count()==0)
                            Hiç ödülüz yok, kazanmak için soru çözün.
                        @endif
                    </div>


                <h2>Sorularım</h2>
                    <div class="duellolar-kutusu">
                        <?php
                        $sorularArr         = $varliklarimArr['sorular'];
                        $zorlukArr          = array('cok_kolay'=>0,'kolay'=>0,'normal'=>0,'zor'=>0,'cok_zor'=>0);
                        ?>
                        Size ait {!!   count($sorularArr) !!} tane soru bulunmakta.<br>
                        @foreach ( $sorularArr as $key => $value )
                            @if($value->zorluk==1)
                                <?php $zorlukArr['cok_kolay']++?>
                            @elseif($value->zorluk==2)
                                <?php $zorlukArr['kolay']++?>
                            @elseif($value->zorluk==3)
                                <?php $zorlukArr['normal']++?>
                            @elseif($value->zorluk==4)
                                <?php $zorlukArr['zor']++?>
                            @elseif($value->zorluk==5)
                                <?php $zorlukArr['cok_zor']++?>
                            @endif
                        @endforeach

                        <ul class="ag-seviye-dugme">
                            <li id="ag-cok-kolay-btn">
                               {{ $zorlukArr['cok_kolay'] }}
                                <div class="seviye-txt">Çok Kolay</div>
                            </li>
                            <li id="ag-kolay-btn">
                                 {{ $zorlukArr['kolay'] }}
                                <div class="seviye-txt">Kolay</div>
                            </li>
                            <li id="ag-normal-btn">
                                {{ $zorlukArr['normal'] }}
                                <div class="seviye-txt">
                                    Normal</div>
                            </li>
                            <li id="ag-zor-btn">
                                {{ $zorlukArr['zor'] }}
                                <div class="seviye-txt">
                                    Zor
                                    </div>
                            </li>
                            <li id="ag-cok-zor-btn">
                                {{ $zorlukArr['cok_zor'] }}
                                <div class="seviye-txt">
                                    Çok zor
                                    </div>
                            </li>
                        </ul>
                    </div>






                <div class="ag-aciklama">Rakiplerinize sormak için soru satın alın. Unutmayın zor sorular için daha fazla altın gerekli.
                        <br><a href="#" id="ag-soru-satinal">Soru satın alın</a></div>
                </div>
    </div>


    <!-- --------------------------------------- SORU MODALI ---------------------------------------- -->

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Soru Satın Alın</h4>
                </div>
                <div class="modal-body">
                    <div id="ag-fiyat-tablolari">
                        <h4>Zeka ve Mantık Soruları</h4>
                        <table class="table" id="ag-fiyat-listesi">
                            <tr>
                                <td>Çok Kolay </td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_cok_kolay:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_cok_kolay:null }}" tur="fiyat_zm_cok_kolay" seviye="1" >Al</div></td>
                            </tr>
                            <tr>
                                <td>Kolay</td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_kolay:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_kolay:null }}" tur="fiyat_zm_kolay" seviye="2">Al</div></td>
                            </tr>
                            <tr>
                                <td>Normal</td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_normal:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_normal:null }}" tur="fiyat_zm_normal" seviye="3">Al</div></td>
                            </tr>
                            <tr>
                                <td>Zor</td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_zor:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_zor:null }}" tur="fiyat_zm_zor" seviye="4" >Al</div></td>
                            </tr>
                            <tr>
                                <td>Çok Zor</td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_cok_zor:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_zm_cok_zor:null }}" tur="fiyat_zm_cok_zor" seviye="5">Al</div></td>
                            </tr>
                        </table>

                        <h4>Okul Dersleri</h4>
                        <table class="table" id="ag-fiyat-listesi">
                            <tr>
                                <td>Çok Kolay </td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_cok_kolay:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_cok_kolay:null }}" tur="fiyat_ds_cok_kolay" seviye="1">Al</div></td>
                            </tr>
                            <tr>
                                <td>Kolay</td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_kolay:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_kolay:null }}" tur="fiyat_ds_kolay" seviye="2">Al</div></td>
                            </tr>
                            <tr>
                                <td>Normal</td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_normal:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_normal:null }}" tur="fiyat_ds_normal" seviye="3">Al</div></td>
                            </tr>
                            <tr>
                                <td>Zor</td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_zor:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_zor:null }}" tur="fiyat_ds_zor" seviye="4">Al</div></td>
                            </tr>
                            <tr>
                                <td>Çok Zor</td>
                                <td>{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_cok_zor:null }} Altın</td>
                                <td><div class="btn btn-success btn-xs ag-satin-al-btn" fiyat="{{  isset($ayarlarArr)?$ayarlarArr->fiyat_ds_cok_zor:null }}" tur="fiyat_ds_cok_zor" seviye="5">Al</div></td>
                            </tr>
                        </table>
                    </div>
                    <div id="ag-onay_ekrani">
                        <br>
                        <table class="table borderless">
                            <tr>
                                <td colspan="2">Satınalmak istediğiniz soru sayısını yazın.<br>
                                    <input type="number" value="0" id="ag-istenen-miktar" min="1" class="form-control"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3><div class="label label-success label-lg" id="ag-bilgilendirme"></div></h3>
                                </td>
                            </tr>

                            <tr>
                                <td><button class="btn btn-success" id="ag-onayla">Onayla</button></td>
                                <td></td>
                            </tr>
                        </table>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="ag-geri" >Geri</button>
                    <button type="button" class="btn btn-alert" data-dismiss="modal">Kapat</button>
                </div>
            </div>

        </div>
    </div>
    <!-- --------------------------------------- SORU MODALI SON ---------------------------------------- -->

<script>
    var odullerArr = new Array();
    // php den gelen verileri jqueryde kullanmak için
    @foreach ( $odullerArr as $key => $value )
            odullerArr['{{ $value->odul_turu }}']= {{ $value->miktar }}
    @endforeach

    @if($odullerArr->count()==0)
        odullerArr['altin']= 0
    @endif
</script>






<script>
    $(document).ready(function () {
        $('#ag-fiyat-tablolari').show();
        $('#ag-onay_ekrani').hide();
        $('#ag-geri').hide();
    })
    $('#ag-soru-satinal').click(function () {
        $('#myModal').modal('show');
        $('#ag-fiyat-tablolari').show();
        $('#ag-onay_ekrani').hide();
        $('#ag-geri').hide();
    })

    $('.ag-satin-al-btn').click(function () {
        fiyat       = $(this).attr('fiyat');
        tur         = $(this).attr('tur');
        seviye      = $(this).attr('seviye');
        $('#ag-fiyat-tablolari').hide();
        $('#ag-onay_ekrani').show();
        $('#ag-geri').show();
        $('#ag-istenen-miktar').val('0');
        $('#ag-bilgilendirme').hide();
        $('#ag-onayla').attr("disabled", true);
    })

    $('#ag-geri').click(function () {
        $('#ag-fiyat-tablolari').show();
        $('#ag-onay_ekrani').hide();
        $('#ag-geri').hide();
    })


    $('#ag-istenen-miktar').on('click',function () {
        miktar = $(this).val()
        toplam_bedeli_hesapla(miktar)

    })
    $('#ag-istenen-miktar').on('change',function () {
        miktar = $(this).val()
        toplam_bedeli_hesapla(miktar)
    })

    function toplam_bedeli_hesapla(miktar) {
        $('#ag-bilgilendirme').show();
        toplam_bedel = miktar*fiyat;
        //altinYeterlimi
        if(odullerArr['altin']<toplam_bedel){
            $('#ag-bilgilendirme').removeClass('label-success')
            $('#ag-bilgilendirme').addClass('label-warning')
            $('#ag-bilgilendirme').html('Altın miktarınız yetersiz !')
            $('#ag-onayla').attr("disabled", true);

        }else{
            $('#ag-bilgilendirme').html('Toplam '+toplam_bedel+' altın harcayacaksınız.')
            $('#ag-bilgilendirme').addClass('label-success')
            $('#ag-bilgilendirme').removeClass('label-warning')
            $('#ag-onayla').attr("disabled", false);
        }
        if(miktar<=0){
            $('#ag-onayla').attr("disabled", true);
        }
    }

    var fiyat;
    var tur;
    var seviye;
    var miktar;
    var toplam_bedel;
    var sayfayi_guncelle=0;
    // satın almayı onayla
    $('#ag-onayla').click(function () {
        data    ='?fiyat='+fiyat
        data    +='&tur='+tur
        data    +='&miktar='+miktar
        data    +='&seviye='+seviye
        $.ajax({
            url: '/varliklarim/satinalmayiOnayla/'+data,
            dataType: 'json',
            beforeSend: function() {
                $('#ag-onayla').attr("disabled", true);
                islemBar.show();
            },
            complete: function() {
                $('#ag-onayla').attr("disabled", false);
                islemBar.hide();
            },
            success: function(json) {
                console.log(json)
                if(json['hata']){
                    alert(json['hata']);
                }else{
                    alert('Satın alındı');
                    sayfayi_guncelle    =1;
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    })


    $('#myModal').on('hidden.bs.modal', function () {
        if(sayfayi_guncelle){
            location.reload();
        }
    })
</script>








<style>
    #ag-onay_ekrani input[type="number"]{
        font-size: 20px;
        border: none;
        text-align: center;
    }

    .ag-seviye-dugme{
        position: relative;
        display: inline-block;
    }

    .ag-seviye-dugme li{
        position: relative;
        list-style-type: none;
        float: left;
        width: 100px;
        height: 100px;
        margin: 25px;
        border: 3px solid #3b81cc;
        background-size: 100%;
        background-repeat: no-repeat;
        background-color: #b8d4f1;
        border: 5px solid #609db8;
        text-align: center;
        font-size: 40px;
        font-family: Anton;
        vertical-align: middle;
        text-shadow: 1px 1px 1px #000;
        border-radius: 50px;
    }

    .seviye-txt{
        font-family: 'Gloria Hallelujah', cursive;
        font-size: 15px;
        position: relative;
        width: 100%;
        bottom: -40px;
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

    .ag-odullerim{
        display: block;
        background-image: url("/bgimages/altin.png");
        background-repeat: no-repeat;
        background-position: center;
        min-height: 300px;
        margin-bottom: 50px;
        border-bottom: 10px solid #2f4f4f;
    }



    .ag-odul-txt{
        font-size: 80px;
        font-family: Anton;
        width: 100%;
        text-align: center;
        text-shadow: 2px 2px 5px #000;
    }
</style>
@endsection
