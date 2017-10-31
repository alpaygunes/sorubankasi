@extends('front.layouts.front')

@section('content')
    <div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
        @if(isset($baslik))
            <div class="panel ag-front-baslik-kutusu">
                {{$baslik}}
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
            Duello Nedir ? <br>
            Rakiplerin sırayla soru soruduğu ikili mücadele türünde yarışmadır.<br>
            Zor sorular sorun ve arkadaşınızın altınlarını kapın. Size sorulan soruları cevabını bilin arkadaşınızın altınlarına el koyun.

            <h4> Devam edenler</h4>
            <div id="ag-devam-eden-liste"></div>
            <h4> Yeni duello için rakip seç</h4>
            <div id="ag-rakip-sec-liste"></div>
        </div>
    </div>







    <!-- ------------------------------------  RAKİP DETAYLARINI GÖSTEREN MODAL ----------------------- -->

    <div class="modal fade" id="ag-rakip-datay" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Rakibinizin Detayları</h4>
                </div>
                <div class="modal-body">
                    <div id="ag-oduller"></div>
                    <div id="ag-sorular"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary" id="ag-bunu-sec">Bunu Seç</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- ---------------------------------------- son ----------------------------------------- -->















    <script>
        // arkadaş listesini alalım
        $(document).ready(function () {
            getArkadasListesi();
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
            $.ajax({
                url     : '/uyelik/getKullaniciVarliklari/'+rakip_user_id,
                dataType: 'json',
                success : function(veri) {
                    console.log(veri);
                    $('#ag-rakip-datay #ag-oduller').empty();
                    $.each( veri['oduller'], function( index, value ) {
                        $('#ag-rakip-datay #ag-oduller').append(value['odul_turu'])
                        $('#ag-rakip-datay #ag-oduller').append(value['miktar'])
                    })

                    zorlukArr = {cok_kolay:0, kolay:0, normal:0,zor:0,cok_zor:0};
                    $('#ag-rakip-datay #ag-sorular').empty();
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
                        $('#ag-rakip-datay #ag-sorular').empty();
                        $('#ag-rakip-datay #ag-sorular').append(zorlukArr['cok_kolay'])
                        $('#ag-rakip-datay #ag-sorular').append(zorlukArr['kolay'])
                        $('#ag-rakip-datay #ag-sorular').append(zorlukArr['normal'])
                        $('#ag-rakip-datay #ag-sorular').append(zorlukArr['zor'])
                        $('#ag-rakip-datay #ag-sorular').append(zorlukArr['cok_zor'])
                    })

                    $('#ag-rakip-datay').modal('toggle');
                },
                error   : function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })


        // rakip detaylarını gördükten sonra rakibi seçmeyi onayla
        $('#ag-bunu-sec').click(function () {
            url="/duello/bunusec/"+rakip_user_id
            window.location.href=url;
        })
    </script>

    <style>
        .ag-arkadas-kutu {
            position: relative;
            float: left;
            width: 100px;
            height: 125px;
            border: 1px solid #ccc;
            padding: 3px;
            margin: 5px;
            display: block;
            text-align: center;
            cursor: pointer;
        }
        .ag-arkadas-kutu img{
            width: 90%;
        }

        .ag-arkadas-adi{
            position: absolute;
            bottom: 0px;
            width: 100%;
            text-align: center;
            font-size: 10px;
        }
    </style>
@endsection
