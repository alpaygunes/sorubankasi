@extends('admin.layouts.admin')
@section('content')
    @if(isset($baslik))
        <div class="panel ag-baslik-kutusu">
            {{$baslik}}
            <div class="ust-menu-ogesi" id="menu-ekle">
                <a href="#" id="ag-filtre">
                    <span class="glyphicon glyphicon glyphicon-filter" aria-hidden="true"></span>
                </a>
                <a href="/admin/soru/form">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
            </div>
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

<?php $sayac=0;?>
@foreach ( $sorularArr as $key => $value )
    <?php $sayac++;?>
    <div class="panel panel-default ag-panel ag-soru-panel col-lg-6 col-md-6 col-xs-12">
        <div class="ag-soru-no">{{ $sorularArr->perPage()*($sorularArr->currentPage()-1)+$sayac . "." }}</div>
        <div class="ag-sorumetni">{!! $value->sorumetni !!}</div>

        <div class="ag-soru-arac-cubugu">
            <div class="ag-sil pull-left ag-soru-sil-duzenle" soru-id="{{ $value->id }}">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </div>
            <div class="ag-duzenle pull-left ag-soru-sil-duzenle" soru-id="{{ $value->id }}">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            </div>
        </div>
    </div>
@endforeach
    <div class="clearfix "></div>
    <div class="ag-sayfalama-cubugu panel ag-panel">
        {!! $sorularArr->render() !!}
    </div>


    <!-- -------------------------------- FİLİTRE MODALI -------------------------------- - - -->
    <div class="modal fade" id="ag-filtreModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Süzgeç</h4>
                </div>
                {!! Form::open(['url' => '/admin/soru/liste','id' => 'soruFrm','class'=>'form-horizontal']) !!}
                <div class="modal-body">
                    <div class="row">
                        {!! Form::label('dersler', 'Ders',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            {{ Form::select('dersler', $derslerArr,null, ['class' =>  'form-control ag-form-control']) }}
                        </div>
                        {!! Form::label('konu_id', 'Konu',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            {{ Form::select('konu_id', array(''=>'Önce Dersi Seçin'),'', ['class' =>  'form-control ag-form-control']) }}
                        </div>
                        {!! Form::label('zorluk', 'Zorluk Seviyesi',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            {{ Form::select('zorluk', array(''=>'Seviye Seçin','1'=>'1-Çok kolay','2'=>'2','3'=>'3','4'=>'4','5'=>'5-Çok zor'),
                            null, ['class' =>  'form-control ag-form-control']) }}
                        </div>
                        {!! Form::label('sinif', 'Sınıf',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            {{ Form::select('sinif', array(''=>'Sınıf Seçin','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14'),
                            null, ['class' =>  'form-control ag-form-control']) }}
                        </div>
                            {!! Form::hidden('soru_turu','coktan_secmeli') !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary" id="ag-uygula">Uygula</button>
                </div>
                {!! Form::close() !!}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- -- -------------------------------- FİLİTRE MODALI SON -------------------------------- -->




<script>
    $('.ag-duzenle').click(function () {
        id = $(this).attr('soru-id');
        window.location.href = "/admin/soru/duzenle/"+id
    })
    $('.ag-sil').click(function () {
        if(confirm('Öğeyi silmek istediğinizden emin misiniz ?')){
            id = $(this).attr('soru-id');
            window.location.href = "/admin/soru/sil/"+id
        }
    })

    $('#ag-filtre').click(function () {
        $('#ag-filtreModal').modal('show');
    })

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
</script>

@stop

