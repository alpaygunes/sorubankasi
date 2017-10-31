@extends('front.layouts.front')

@section('content')

<div class="col-sm-9 col-md-10 col-lg-10 col-xl-10" >

    @if(isset($baslik))
        <div class="panel ag-front-baslik-kutusu">
            {{$baslik}}
            <div class="ag-ust-menu-ogesi">
                <a href="#" id="ag-search">
                    <span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span>
                </a>
                <a href="#" id="ag-istekleri-listele">
                    <span class="glyphicon glyphicon glyphicon-list" aria-hidden="true"></span>
                </a>

                <a href="#" id="ag-engellediklerim">
                    Engellenenler
                </a>

                <a href="#" id="ag-silinenler">
                    silinenler
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



    <div class="panel ag-front-panel col-md-12">
        <div id="ag-liste">

                <table class="table borderless">
                    @foreach ( $arkadaslarArr as $key => $value )
                    <tr>
                        <td><img class="ag-kullanici-resmi" src='{{$value->profil_resmi==null?'/images/noimage.png':$value->profil_resmi}}'></td>
                        <td>{!! $value->name !!}</td>
                        <td>
                           <div class="ag-listeden-sil" user_id="{!! $value->id !!}">Listeden sil</div>
                        </td>
                        <td>
                            @if(isset($value->engelle))
                                <div class="ag-engeli-kaldir" user_id="{!! $value->id !!}">Engeli Kaldır</div>
                            @else
                                <div class="ag-engelle" user_id="{!! $value->id !!}">Engelle</div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>



        </div>
        <div id="ag-ajax-liste">

        </div>
    </div>


        <!-- -------------------------------- ARAMA MODALI -------------------------------- - - -->
        <div class="modal fade" id="ag-aramaModali" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Arkadaş Ara</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <table class="table borderless">
                                <tr>
                                    <td style="vertical-align: middle">Ara :</td>
                                    <td><input type="text" id="ag-aranan" class="form-control" placeholder="Eposta adresini yada isim soyisim yazın" required></td>
                                    <td><input type="button" class="btn btn-primary" id="ag-ara" value="Ara"></td>
                                </tr>
                            </table>
                            <div id="ag-arama-sonuclari">

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- -- -------------------------------- ARAMA MODALI SON -------------------------------- -->
</div>

<style>
    .modal-body{
        min-height: 500px;
    }

    #ag-istek-sayisi{
        position: absolute;
        top: -6px;
        border-radius: 10px;
        background: red;
        width: 12px;
        height: 12px;
        line-height: 12px;
        padding-left: 3px;
        color: #fff;
        font-size: 12px;
        text-shadow: 1px 1px #000;
    }

    #ag-ajax-liste table tr td{
        vertical-align: middle;
    }

    .ag-kullanici-resmi{
        max-width:50px;
        max-height: 50px;
        padding: 5px;
        border: 1px solid #ccc;
    }

</style>

    <script>

        $('#ag-search').click(function () {
            $('#ag-aramaModali').modal('toggle');
        })

        //   ---------------------------  ARA ----------------------------------------
        $('#ag-ara').click(function () {
            if($('#ag-aranan').val().length<3){
                alert("Arama için en az üç harf gerekli.")
                //return false;
            }
            data    ='?aranan='+$('#ag-aranan').val();
            $.ajax({
                url: '/arkadas/ara'+data,
                dataType: 'html',
                beforeSend: function() {
                    $('#ag-ara').attr("disabled", true);
                },
                complete: function() {
                    $('#ag-ara').attr("disabled", false);
                },
                success: function(json) {
                    $('#ag-arama-sonuclari').html(json)
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

        //   ---------------------------  ARKADAŞ İSTEKLERİ VARMMI VARSA LİSTELE ----------------------------------------
        var arkadaslikIstekleri = new Array();
        $(document).ready(function () {
            $.ajax({
                url: '/arkadas/getArkadaslikIstekleri',
                dataType: 'json',
                beforeSend: function() {
                    //$('#ag-ara').attr("disabled", true);
                },
                complete: function() {
                    //$('#ag-ara').attr("disabled", false);
                },
                success: function(json) {
                    console.log(json)
                    arkadaslikIstekleri =json;
                    istek_sayisi = arkadaslikIstekleri.length
                    if(istek_sayisi>0){
                        $('#ag-istekleri-listele').prepend('<span id=\'ag-istek-sayisi\'>'+istek_sayisi+'</span>')
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

        $('#ag-istekleri-listele').click(function () {
            tablogovdesi ='';
            $.each( arkadaslikIstekleri, function( key, value ) {
                if(value['profil_resmi']==null){
                    value['profil_resmi']='/images/noimage.png'
                }
                tablogovdesi+='<tr>';
                tablogovdesi+='<td user_id=\''+value['id']+'\'><img class="ag-kullanici-resmi" src='+value['profil_resmi']+'></td>';
                tablogovdesi+='<td user_id=\''+value['id']+'\'>'+value['name']+'</td>';
                tablogovdesi+='<td><div class="btn btn-default btn-sm" islem=\'onayla\' arkadas_id=\''+value['id']+'\' >Onayla</div></td>';
                tablogovdesi+='<td><div class="btn btn-default btn-sm" islem=\'kaldir\' arkadas_id=\''+value['id']+'\'>Kaldır</div></td>';
                tablogovdesi+='</tr>';
            });
            $('#ag-liste').hide();
            $('#ag-ajax-liste').show();
            $('#ag-ajax-liste').empty()
            $('#ag-ajax-liste').append('<table class="table borderless" >'+tablogovdesi+'</table>');
        })

        $('#ag-ajax-liste').on('click','.btn',function () {
            budugme         = $(this);
            islem           = $(this).attr('islem');
            arkadas_id      = $(this).attr('arkadas_id');
            satir           = $(this).parent().parent();
            $.ajax({
                url: '/arkadas/istegiYap/'+islem+'/'+arkadas_id,
                dataType: 'json',
                beforeSend: function() {
                    budugme.attr("disabled", true);
                },
                complete: function() {
                    budugme.attr("disabled", false);
                },
                success: function(json) {
                    console.log(json)
                    satir.hide();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

        $('.ag-listeden-sil').click(function () {
            budugme         = $(this);
            arkadas_id      = $(this).attr('user_id');
            satir           = $(this).parent().parent();
            $.ajax({
                url: '/arkadas/listedenSil/'+arkadas_id,
                dataType: 'json',
                beforeSend: function() {
                    budugme.attr("disabled", true);
                },
                complete: function() {
                    budugme.attr("disabled", false);
                },
                success: function(json) {
                    console.log(json)
                    satir.hide();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

        $('#ag-liste').on('click','.ag-engelle',function () {
            budugme         = $(this);
            arkadas_id      = $(this).attr('user_id');
            satir           = $(this).parent().parent();
            $.ajax({
                url: '/arkadas/engelle/'+arkadas_id,
                dataType: 'json',
                beforeSend: function() {
                    budugme.attr("disabled", true);
                },
                complete: function() {
                    budugme.attr("disabled", false);
                },
                success: function(json) {
                    console.log(json)
                    budugme.html('Engeli Kaldır')
                    budugme.removeClass('ag-engelle').addClass('ag-engeli-kaldir')
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

        $('#ag-liste').on('click','.ag-engeli-kaldir',function () {
            budugme         = $(this);
            arkadas_id      = $(this).attr('user_id');
            satir           = $(this).parent().parent();
            $.ajax({
                url: '/arkadas/engeliKaldir/'+arkadas_id,
                dataType: 'json',
                beforeSend: function() {
                    budugme.attr("disabled", true);
                },
                complete: function() {
                    budugme.attr("disabled", false);
                },
                success: function(json) {
                    console.log(json)
                    budugme.html('Engelle')
                    budugme.removeClass('ag-engeli-kaldir').addClass('ag-engelle')
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

        $('#ag-ajax-liste').on('click','.ag-engeli-kaldir',function () {
            budugme         = $(this);
            arkadas_id      = $(this).attr('user_id');
            satir           = $(this).parent().parent();
            $.ajax({
                url: '/arkadas/engeliKaldir/'+arkadas_id,
                dataType: 'json',
                beforeSend: function() {
                    budugme.attr("disabled", true);
                },
                complete: function() {
                    budugme.attr("disabled", false);
                },
                success: function(json) {
                    satir.hide();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

        $('#ag-engellediklerim').click(function(){
            budugme         = $(this);
            $.ajax({
                url: '/arkadas/engellediklerim/',
                dataType: 'json',
                beforeSend: function() {
                    budugme.attr("disabled", true);
                },
                complete: function() {
                    budugme.attr("disabled", false);
                },
                success: function(engelliler) {

                    tablogovdesi ='';
                    $.each( engelliler, function( key, value ) {
                        if(value['profil_resmi']==null){
                            value['profil_resmi']='/images/noimage.png'
                        }
                        tablogovdesi+='<tr>';
                        tablogovdesi+='<td user_id=\''+value['id']+'\'><img class="ag-kullanici-resmi" src='+value['profil_resmi']+'></td>';
                        tablogovdesi+='<td user_id=\''+value['id']+'\'>'+value['name']+'</td>';
                        tablogovdesi+='<td><div class="btn btn-default btn-sm ag-engeli-kaldir"  user_id=\''+value['id']+'\'>Engeli Kaldır</div></td>';
                        tablogovdesi+='</tr>';
                    });
                    $('#ag-liste').hide();
                    $('#ag-ajax-liste').show();
                    $('#ag-ajax-liste').empty()
                    $('#ag-ajax-liste').append('<table class="table borderless" >'+tablogovdesi+'</table>');


                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

        $('#ag-silinenler').click(function(){
            budugme         = $(this);
            $.ajax({
                url: '/arkadas/silinenler/',
                dataType: 'json',
                beforeSend: function() {
                    budugme.attr("disabled", true);
                },
                complete: function() {
                    budugme.attr("disabled", false);
                },
                success: function(silinenler) {

                    tablogovdesi ='';
                    $.each( silinenler, function( key, value ) {
                        if(value['profil_resmi']==null){
                            value['profil_resmi']='/images/noimage.png'
                        }
                        tablogovdesi+='<tr>';
                        tablogovdesi+='<td user_id=\''+value['id']+'\'><img class="ag-kullanici-resmi" src='+value['profil_resmi']+'></td>';
                        tablogovdesi+='<td user_id=\''+value['id']+'\'>'+value['name']+'</td>';
                        tablogovdesi+='<td><div class="btn btn-default btn-sm ag-silmeyi-gerial"  user_id=\''+value['id']+'\'>Tekrar Listeye Ekle</div></td>';
                        tablogovdesi+='</tr>';
                    });
                    $('#ag-liste').hide();
                    $('#ag-ajax-liste').show();
                    $('#ag-ajax-liste').empty()
                    $('#ag-ajax-liste').append('<table class="table borderless" >'+tablogovdesi+'</table>');


                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

        $('#ag-ajax-liste').on('click','.ag-silmeyi-gerial',function () {
            budugme         = $(this);
            arkadas_id      = $(this).attr('user_id');
            satir           = $(this).parent().parent();
            $.ajax({
                url: '/arkadas/silineniGeriAl/'+arkadas_id,
                dataType: 'json',
                beforeSend: function() {
                    budugme.attr("disabled", true);
                },
                complete: function() {
                    budugme.attr("disabled", false);
                },
                success: function(json) {
                    satir.hide();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })

    </script>
@endsection
