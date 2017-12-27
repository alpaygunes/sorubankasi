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
        {!! Form::open(['url' => '/admin/konu/kaydet']) !!}

        {!! Form::label('konu_adi', '',['class' => 'ag-label']) !!}
        {!! Form::text('konu_adi',isset($konu->konu_adi)?$konu->konu_adi:null,['class' => 'form-control ag-form-control',
                                                         'required'=>'required',
                                                         'placeholder'=>'Konu adı']) !!}
        @if($errors->first('konu_adi'))
            <label class="label label-danger">{{ $errors->first('konu_adi') }}</label>
        @endif

        {!! Form::label('parent_id', 'Üst Konu',['class' => 'ag-label']) !!}
        {{ Form::select('parent_id', $konularArr,isset($konu->parent_id)?$konu->parent_id:null, ['class' =>  'form-control ag-form-control','required'=>'required']) }}


        {!! Form::label('baslangic_tarihi', 'Serbest bırakma tarihi',['class' => 'ag-label']) !!}
        {{ Form::date('baslangic_tarihi', isset($konu->baslangic_tarihi)?$konu->baslangic_tarihi:null, ['class' =>  'form-control ag-form-control tarih ','required'=>'required']) }}

        {!! Form::label('bitis_tarihi', 'Engelleme tarihi',['class' => 'ag-label']) !!}
        {{ Form::date('bitis_tarihi', isset($konu->bitis_tarihi)?$konu->bitis_tarihi:null, ['class' =>  'form-control ag-form-control tarih ','required'=>'required']) }}



        {!! Form::label('on_sayfada_listele', 'Ön sayfada listele',['class' => 'ag-label']) !!}
        <?php
        if(isset($konu)){
            $on_sayfada_listele = $konu->on_sayfada_listele;
        }else{
            $on_sayfada_listele = 1;
        }
        ?>
        {{ Form::checkbox('on_sayfada_listele', $on_sayfada_listele==1?1:0,$on_sayfada_listele==1?true:false) }}




        <br><br>
        {{ Form::submit('Kaydet',['class' => 'btn btn-primary ag-form-control']) }}

        @if(isset($konu->id))
            {!! Form::hidden('id',$konu->id) !!}
        @endif
        {!! Form::close() !!}
    <div>


<script>
    $('#on_sayfada_listele').click(function () {
        if($(this).prop( "checked" )){
            $(this).val(1);
        }else{
            $(this).val(0);
        }
    })
</script>


<style>

    .tarih{

    }

</style>
@stop