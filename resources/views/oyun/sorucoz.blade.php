@extends('front.layouts.front')

@section('content')

    <div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >
        @if(isset($baslik))
            <div class="panel ag-front-baslik-kutusu">
                <h1>{{$baslik}}</h1>
            </div>
        @endif
            <div class="panel ag-front-panel col-md-12 " id="ag-soru-paneli">

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
                    {{ Form::select('zorluk', array('0'=>'Farketmez','1'=>'1-Çok kolay','2'=>'2','3'=>'3','4'=>'4','5'=>'5-Çok zor'),
                    null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}

                    <br><br>
                    {{ Form::submit('İleri',['class' => 'btn btn-primary ag-form-control','id'=>'ag-ileri-btn']) }}
                    {!! Form::close() !!}


<!--  -------------------------------------        SORU EKRANI          --------------------------------------------  -->
                <div id="ag-soru-ekrani" class="col-12">
                    <div id="ag-soru-odulu"></div>
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

                    </div>
                    <table class="table borderless" style="height: 450px">
                        <tr>
                            <td colspan="3">
                                <div>
                                    <img id="ag-kazandin-img" src="/bgimages/kazandin1.gif">
                                    <div class="ag-dogru-sayisi"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 75px" id="anim-alani-sol"></td>
                            <td style="height: 75px" id="anim-alani-orta"></td>
                            <td style="height: 75px" id="anim-alani-sag"></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="btn btn-primary ag-btn-devam"  >Devam</div>
                            </td>
                        </tr>
                    </table>


                </div>


<!--  -------------------------------------        SONUÇ EKRANI          --------------------------------------------  -->
                <div id="ag-sonuc-ekrani">
                    <div id="ag-sonuc-ekrani-baslik" class="panel-head">

                    </div>
                    <table class="table borderless" style="height: 450px">
                        <tr>
                            <td>
                                <img  id="ag-kaybettin-img"  src="/bgimages/kaybettin0.gif">
                                <div class="ag-dogru-sayisi"></div>
                            </td>
                        </tr>
                        <tr>
                            <td id="ag-kaybettin-orta">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="btn btn-primary ag-btn-konuyu-degistir" >Yeniden</div>
                            </td>
                        </tr>
                    </table>


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

        //--------------------------------------------------------------
        $(document).ready(function () {
            // kaybetme ve kaznama resimlerinin resagele belirmlenmesi içn
            resim_no = Math.floor((Math.random() * 11) + 1);
            $('#ag-kaybettin-img').attr('src','/bgimages/kaybettin'+resim_no+'.gif')
            $('#ag-kazandin-img').attr('src','/bgimages/kazandin'+resim_no+'.gif')

            $('#ag-gecis-ekrani').hide();
            $('#ag-soru-ekrani').hide();
            $('#ag-sonuc-ekrani').hide();

            $('html, body').animate({
                scrollTop: $(document).height()
            }, 1500);
        })
        //---------------------------------------------------------------
        $('#dersler').change(function () {
            //dere ait konuları getir.
            ders_id = $(this).val();
            $.getJSON( "/admin/konu/getKonu/"+ders_id, function( data ) {
                console.log(data)
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
        var soru_odulu=0;
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
                        $('#agMesajBoxModal').modal('show')
                        $('#agMesajBoxModal .modal-body').html("Seçtiğiniz kriterlere uygun soru bulamadık.")
                    }else{
                        $soruArr            = response;
                        oyun_status         = 'sorular_yuklendi';
                        $(form).hide();
                        $('#ag-soru-ekrani').show();
                        $('#ag-soru-kutusu').html();
                        $('#ag-soru-kutusu').html($soruArr['soru']['sorumetni']);
                        $('#ag-soru-odulu').html($soruArr['soru']['odul'] +" Altın");
                        soru_odulu          = $soruArr['soru']['odul'];
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
                    $('#ag-soru-ekrani').hide();
                    $('#ag-sonuc-ekrani').show();
                    kaybettin_animasyonu();
                }else if(response['dogru_cevap']==1) {
                    $('#ag-soru-ekrani').hide();
                    $('#ag-gecis-ekrani').show();
                    kazandin_animasyonu();
                }else{
                    $soruArr = response['soru'];
                    $('#ag-soru-kutusu').html();
                    $('#ag-soru-kutusu').html($soruArr['sorumetni']);
                }
            });
        })
        
        function kaybettin_animasyonu() {
            $('#ses-yanlis')[0].play();
            (function myLoop (i) {
                setTimeout(function () {
                    i = i - 10;
                    $('#ag-kaybettin-orta').html(-(soru_odulu-i));
                    if (i>0) myLoop(i);      //  decrement i and call myLoop again if i > 0
                }, 10)
            })(soru_odulu+10);
        }

        function kazandin_animasyonu() {
            kazandin_gif = "<img id='ag-kazindin-gif' " +
                "style='width:100%'" +
                "src='/bgimages/yildiz_sacilmasi.gif'>"  ;

            $('#anim-alani-sol').html(kazandin_gif);
            $('#anim-alani-sag').html(kazandin_gif);
            $('#ses-sihir')[0].play();

            (function myLoop (i) {
                setTimeout(function () {
                    i = i - 10;
                    $('#anim-alani-orta').html(soru_odulu-i);
                    if (i>0) myLoop(i);      //  decrement i and call myLoop again if i > 0
                }, 10)
            })(soru_odulu+10);                        //  pass the number of iterations as an argument
        }

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
            width: 400px;
        }

        .ag-secenek{
            width: 60px;
            height: 60px;
            border: 2px solid #30626f;
            float: left;
            text-align: center;
            margin: 7px;
            cursor: pointer;
            font-size: 35px;
            background-color: #70b3d1;
            text-shadow: 1px 1px 1px #000;
            border-radius: 50px;
            color: #fff;
        }

        .ag-secenek:hover{
            background-color: #6aaac6;
        }

        #ag-soru-kutusu{
            font-size: 25px;
        }

        #ag-ileri-btn, .ag-btn-devam, .ag-btn-konuyu-degistir{
            font-family: Anton;
            font-size: 25px;
        }

        .ag-btn-konuyu-degistir{

        }


        .form-control{
            font-size: 20px!important;
            height: 40px!important;
        }

        .ag-label{
            margin-top: 25px!important;
        }

        #ag-gecis-ekrani, #ag-sonuc-ekrani{
            width: 100%;
            display: inline-block;
            text-align: center;
        }

        #ag-soru-paneli{

            background-color: #fff;
            color: #000;
            border:5px solid #71b9d9;
        }

        #ag-gecis-ekrani img, #ag-sonuc-ekrani img{
            max-width: 200px;
            max-height: 200px;
        }

        #ag-soru-odulu{
            position: absolute;
            right: 10px;
            margin-top: -97px;
            font-size: 27px;
            font-family: Anton;
            color: #ffea10;
            text-shadow: 1px 1px 1px #726e39;
        }

        #anim-alani-orta{
            text-align: center;
            font-size: 120px;
            font-family: Anton;
            color: #ffea10;
            text-shadow: 1px 1px 1px #726e39;
        }
        #ag-kaybettin-orta{
            text-align: center;
            font-size: 120px;
            font-family: Anton;
            color: #ffea10;
            text-shadow: 1px 1px 1px #726e39;
        }

        .ag-front-baslik-kutusu{
            margin-bottom: 0px!important;
        }
    </style>
@endsection
