@extends('admin.home')
@section('content')
    @if(isset($baslik))
        <div class="panel ag-baslik-kutusu">
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

    <div class="panel panel-default ag-panel">

            {!! Form::open(['url' => '/admin/soru/kaydet','id' => 'soruFrm']) !!}
        <div class="row">
            <div id="editor" class="col-sm-8 col-md-8 col-lg-8">
                {!! Form::label('sorumetni', '',['class' => 'ag-label']) !!}
                <br>
                {!! Form::textarea('sorumetni',isset($soruArr->sorumetni)?$soruArr->sorumetni:null) !!}
            </div>
            <div id="secenekler" class="col-sm-4 col-md-4 col-lg-4">
                {!! Form::label('dersler', 'Ders',['class' => 'ag-label']) !!}
                {{ Form::select('dersler', $derslerArr,isset($soru_dersi)?$soru_dersi:null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}
                {!! Form::label('konu_id', 'Konu',['class' => 'ag-label']) !!}
                @if(isset($soruArr))
                    {{ Form::select('konu_id', $konularArr ,$soruArr->konu_id, ['class' =>  'form-control ag-form-control','required'=>'required']) }}
                @else
                    {{ Form::select('konu_id', array(''=>'Önce Dersi Seçin'),'', ['class' =>  'form-control ag-form-control','required'=>'required']) }}
                @endif
                {!! Form::label('yanit', 'Doğru Seçenek',['class' => 'ag-label']) !!}
                {{ Form::select('yanit', array(''=>'Doğru seçenek','a'=>'A','b'=>'B','c'=>'C','d'=>'D','e'=>'E'),
                isset($soruArr->yanit)?$soruArr->yanit:null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}
                {!! Form::label('zorluk', 'Zorluk Seviyesi',['class' => 'ag-label']) !!}
                {{ Form::select('zorluk', array('1'=>'1-Çok kolay','2'=>'2','3'=>'3','4'=>'4','5'=>'5-Çok zor'),
                isset($soruArr->zorluk)?$soruArr->zorluk:null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}
                {!! Form::label('sinif', 'Sınıf',['class' => 'ag-label']) !!}
                {{ Form::select('sinif', array(''=>'Sınıf Seçin','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14'),
                isset($soruArr->sinif)?$soruArr->sinif:null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}
                {!! Form::label('market_sorusu', 'Markette Satılsın',['class' => 'ag-label']) !!}
                <?php
                if(isset($soruArr)){
                    $market_sorusu = $soruArr->market_sorusu;
                }else{
                    $market_sorusu = 0;
                }
                ?>
                {{ Form::checkbox('market_sorusu', $market_sorusu==1?1:0,$market_sorusu==1?true:false) }}
                {!! Form::hidden('soru_turu','coktan_secmeli') !!}

            </div>
        </div>
            <br><br>
            {{ Form::submit('Kaydet',['class' => 'btn btn-primary ag-form-control']) }}
            @if(isset($soruArr->id))
                {!! Form::hidden('id',$soruArr->id) !!}
            @endif
            {!! Form::close() !!}


    <div>

        <script language="JavaScript">
            $(document).ready(function() {
                $('#sorumetni').summernote({
                    minHeight: 300,             // set minimum height of editor
                    maxHeight: 700,             // set maximum height of editor
                    focus: true                  // set focus to editable area after initializing summernote
                });
            });

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

            $('#soruFrm').submit( function(e){
                e.preventDefault();
                form    = $(this);
                $.ajax( {
                    type: "POST",
                    url: form.attr( 'action' ),
                    data: form.serialize(),
                    success: function( response ) {
                        alert(response[0])
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                } );

                console.log( $( this ).serialize() );
            });


            $('#market_sorusu').click(function () {
                if($(this).prop( "checked" )){
                    $(this).val(1);
                }else{
                    $(this).val(0);
                }
            })
        </script>
@stop