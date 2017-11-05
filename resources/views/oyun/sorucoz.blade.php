@extends('front.layouts.front')

@section('content')

    <div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
        @if(isset($baslik))
            <div class="panel ag-front-baslik-kutusu">
                <h1>{{$baslik}}</h1>
                <i class="fa fa-home fa-2x ag-anasayfa" aria-hidden="true"></i>
            </div>
        @endif
            <div class="panel ag-front-panel col-md-12">

<!--  -------------------------------------        FORM          --------------------------------------------  -->

                    {!! Form::open(['url' => '/sorucoz/filtreleriKaydet','id' => 'sorucozFrm']) !!}
                    {!! Form::label('dersler', 'Ders',['class' => 'ag-label']) !!}
                    {{ Form::select('dersler', $derslerArr,null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}
                    {!! Form::label('konu_id', 'Konu',['class' => 'ag-label']) !!}
                    {{ Form::select('konu_id', array(''=>'Önce Dersi Seçin'),'', ['class' =>  'form-control ag-form-control','required'=>'required']) }}
                    {!! Form::label('sinif', 'Sınıf',['class' => 'ag-label']) !!}
                    {{ Form::select('sinif', array(''=>'Sınıf Seçin','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14'),
                    null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}
                    {!! Form::label('zorluk', 'Zorluk Seviyesi',['class' => 'ag-label']) !!}
                    {{ Form::select('zorluk', array('0'=>'Karışık','1'=>'1-Çok kolay','2'=>'2','3'=>'3','4'=>'4','5'=>'5-Çok zor'),
                    null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}

                    <br><br>
                    {{ Form::submit('İleri',['class' => 'btn btn-primary ag-form-control','id'=>'ag-ileri-btn']) }}
                    {!! Form::close() !!}


<!--  -------------------------------------        SORU EKRANI          --------------------------------------------  -->
                <div id="ag-soru-ekrani" class="col-12">
                    <div id="ag-soru-kutusu">

                    </div>
                    <div id="ag-secenekler">
                        <div class="ag-secenek" value="A">A</div>
                        <div class="ag-secenek" value="B">B</div>
                        <div class="ag-secenek" value="C">C</div>
                        <div class="ag-secenek" value="D">D</div>
                        <div class="ag-secenek" value="E">E</div>
                    </div>
                </div>


<!--  -------------------------------------        GEÇİŞ EKRANI          --------------------------------------------  -->
                <div id="ag-gecis-ekrani">
                    <div id="ag-gecis-ekrani-baslik" class="panel-head">
                        Geçiş Ekranı
                    </div>
                    <div>
                        Burada başarı animasyonu konulacak.
                         <div class="ag-dogru-sayisi"></div>
                    </div>
                    <div class="btn btn-primary ag-btn-devam"  >Devam</div>
                </div>


<!--  -------------------------------------        SONUÇ EKRANI          --------------------------------------------  -->
                <div id="ag-sonuc-ekrani">
                    <div id="ag-sonuc-ekrani-baslik" class="panel-head">
                        Sonuc Ekranı
                    </div>
                    <div>
                        Burada kaybetme animasyonu konulacak.
                        <div class="ag-dogru-sayisi"></div>
                    </div>
                    <div class="btn btn-primary ag-btn-konuyu-degistir" >Konuyu Değiştir</div>
                </div>


            </div>
    </div>


    <script>

        //--------------------------------------------------------------
        $(document).ready(function () {
            $('#ag-gecis-ekrani').hide();
            $('#ag-soru-ekrani').hide();
            $('#ag-sonuc-ekrani').hide();
        })
        //---------------------------------------------------------------
        $('#dersler').change(function () {
            //dere ait konuları getir.
            ders_id = $(this).val();
            $.getJSON( "/admin/konu/getKonu/"+ders_id, function( data ) {

                $('#konu_id').each(function() {
                    $(this).find('option').remove();
                });

                $.each( data, function( key, val ) {
                    girinti = '';
                    for(var i=0;i<val.seviye;i++){
                        girinti += " ---- "
                    }
                    $('#konu_id').append($('<option>', {value: data[key].id,text: girinti+val.konu_adi}));
                });
            });
        })

        //---------------------------------------------------------------
        var $soruArr = new Array();
        var oyun_status = 'giris';
        $('#sorucozFrm').submit( function(e){
            e.preventDefault();
            form    = $(this);
            $.ajax( {
                type: "POST",
                url: form.attr( 'action' ),
                data: form.serialize(),
                success: function( response ) {
                    console.log(response)
                    if(response['soruyok']==1){
                        alert("Soru Yok")
                    }else{
                        $soruArr = response;
                        oyun_status = 'sorular_yuklendi';
                        $(form).hide();
                        $('#ag-soru-ekrani').show();
                        $('#ag-soru-kutusu').html();
                        $('#ag-soru-kutusu').html($soruArr['soru']['sorumetni']);
                    }
                    console.log($soruArr);
                }
            } );
        });

        //--------------------------------------------------------------
        $('.ag-secenek').click(function () {
            yanit = $(this).attr('value');
            $.getJSON( "/sorucoz/cevapKontrol/"+yanit, function( response ) {
                console.log(response)
                if(response['soruyok']==1){
                    alert("Soru Yok")
                }else if(response['yanlis_cevap']==1) {
                    $('.ag-dogru-sayisi').html(response['dogru_cevap_sayisi'])
                    $('#ag-soru-ekrani').hide();
                    $('#ag-sonuc-ekrani').show();
                }else if(response['dogru_cevap']==1) {
                    $('.ag-dogru-sayisi').html(response['dogru_cevap_sayisi'])
                    $('#ag-soru-ekrani').hide();
                    $('#ag-gecis-ekrani').show();
                }else{
                    $soruArr = response['soru'];
                    $('#ag-soru-kutusu').html();
                    $('#ag-soru-kutusu').html($soruArr['sorumetni']);
                }
            });
        })

        //--------------------------------------------------
        $('.ag-btn-konuyu-degistir').click(function () {
            $('#ag-gecis-ekrani').hide();
            $('#ag-soru-ekrani').hide();
            $('#ag-sonuc-ekrani').hide();
            $('#sorucozFrm').show();
        })


        $('.ag-btn-devam').click(function () {
            $.getJSON( "/sorucoz/soruVer", function( response ) {
                console.log(response)
                $soruArr = response;
                $('#ag-soru-kutusu').html();
                $('#ag-soru-kutusu').html($soruArr['soru']['sorumetni']);
                $('#ag-soru-ekrani').show();
                $('#ag-gecis-ekrani').hide();
            });
        })
    </script>

<style>

        #ag-gecis-ekrani{
            display: block;
            position: relative;
            min-height:300px;
        }

        #ag-btn-geri{
            position: absolute;
            bottom: 20px;
            left: 0;
        }

        #ag-soru-ekrani{
            min-height: 500px;
        }

        #ag-soru-kutusu{
            overflow: auto;
            height: 500px;
        }

        #ag-secenekler{
            position: relative;
            bottom: 0px;
            margin-left: auto;
            margin-right: auto;
            width: 200px;
        }

        .ag-secenek{
            width: 30px;
            height: 30px;
            border: 1px solid #1f648b;
            float: left;
            text-align: center;
            margin: 5px;
            padding: 5px;
            cursor: pointer;
        }

        #ag-ileri-btn{
            font-family: Anton;
        }

    </style>
@endsection
