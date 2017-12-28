@extends('admin.home')
@section('content')
    @if(isset($baslik))
        <div class="panel ag-baslik-kutusu">
            {{$baslik}}
            <div class="ust-menu-ogesi" id="menu-ekle">
                <a href="/admin/konu/form">
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
    <div class="panel panel-default ag-panel">
        <table class="table table-striped" id="konu_table">
            <thead>
            <tr>
                <th>#</th>
                <th>Konu Adı</th>
                <th>Id</th>
                <th>Serbest Bırakma / Engelleme Tarihi</th>
                <th></th>
            </tr>
            </thead>
            <?php $sayac=0;?>
@foreach ( $konularArr as $key => $value )
<?php
     $sayac++;
     $girinti='';
     for( $i=0;$i<$value->seviye;$i++){
         $girinti.='&nbsp;&nbsp;&nbsp;&nbsp;';
     }
?>
<tr parent_id="{{$value->parent_id}}" id="{{$value->id}}">
    <td style="width: 25px">{{ $konularArr->perPage()*($konularArr->currentPage()-1)+$sayac }}</td>
    <td>{{ $girinti .' '. $value->konu_adi }}</td>
    <td>{{ $value->id }}</td>
    <td>{{ $value->baslangic_tarihi .' / '.  $value->bitis_tarihi }}</td>
    <td style="width: 100px">
        <div class="ag-sil pull-left ag-soru-sil-duzenle" konu-id="{{ $value->id }}">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        </div>
        <div class="ag-duzenle pull-left ag-soru-sil-duzenle" konu-id="{{ $value->id }}">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        </div>
    </td>
</tr>
@endforeach

</table>
{!! $konularArr->render() !!}
</div>

<script>
    $(document).ready(function () {
        $('[parent_id=0]').addClass('parent')
    })

    $('.ag-duzenle').click(function () {
        id = $(this).attr('konu-id');
        window.location.href = "/admin/konu/duzenle/"+id
    })
    $('.ag-sil').click(function () {
        if(confirm('Öğeyi silmek istediğinizden emin misiniz ?')){
            id = $(this).attr('konu-id');
            window.location.href = "/admin/konu/sil/"+id
        }
    })

    $('#konu_table tr').click(function () {
        id = $(this).attr('id');
        $('[parent_id='+id+']').toggle('fast');

    })
</script>


    <style>
        .parent{
            font-size: 16px!important;
            background-color: #e6f1f3 !important;
            text-shadow: 1px 1px #ffffff;
            font-weight: bold;
            cursor: pointer;
        }
        .parent:hover{
            background-color: #d9e3e5 !important;
        }
    </style>
@stop

