@extends('front.layouts.front')

@section('content')

    <div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
        @if(isset($baslik))
            <div class="panel ag-front-baslik-kutusu">
                {{$baslik}}
            </div>
        @endif
            <div class="panel ag-front-panel col-md-12">
                <div class="row">
                    <h2>Ödülleriniz</h2>

                    <?php $odullerArr=$varliklarimArr['oduller'] ?>
                    @foreach ( $odullerArr as $key => $value )
                        @if($value->miktar==0)
                            Hiç {{ $value->odul_turu }} ödülüz yok, {{ $value->odul_turu }} kazanmak için soru çözün.
                        @else
                            {{ $value->odul_turu }}
                            {{ $value->miktar }}
                        @endif
                    @endforeach
                    @if($odullerArr->count()==0)
                        Hiç ödülüz yok, kazanmak için soru çözün.
                    @endif


                </div>
                <div class="row">
                    <h2>Sorularınız</h2>

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

                    <ul>
                        <li>Çok Kolay {{ $zorlukArr['cok_kolay'] }}</li>
                        <li>Kolay {{ $zorlukArr['kolay'] }}</li>
                        <li>Normal{{ $zorlukArr['normal'] }}</li>
                        <li>Zor{{ $zorlukArr['zor'] }}</li>
                        <li>Çok Zor{{ $zorlukArr['cok_zor'] }}</li>
                    </ul>







                    Rakiplerinize sormak için soru satın alın. Unutmayın zor sorular için daha fazla altın gerekli.
                    <a href="#" id="ag-soru-satinal">Soru satın alın</a>
                </div>
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
            },
            complete: function() {
                $('#ag-onayla').attr("disabled", false);
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
</style>
@endsection
