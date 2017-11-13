    <div class="row">
        <table class="table borderless " id="arama-sonuc-tablosu">
            <?php
            if(strlen(Input::get('aranan'))<3){
                echo "<div style='text-align: center'>En az üç karakter yazmalısınız</div>";
                exit();
            }

            $sayac=0;?>
            @foreach ( $sonucArr as $key => $value )
                <?php $sayac++;?>
                <tr>
                    <td style="text-align: center">
{{--                        {{ $sonucArr->perPage()*($sonucArr->currentPage()-1)+$sayac . "." }}--}}
                    </td>
                    <td>
                        <img class="ag-kullanici-resmi" src="{{$value->profil_resmi==null?'/images/noimage.png':$value->profil_resmi}}">
                    </td>
                    <td>
                         {!! $value->name !!}
                    </td>
                    <td style="width: 150px">
                        @if($value->istek_gonderildi==1)
                            <div class="btn btn-default btn-xs ag-arkadaslik-istegi-gonder" islem="iptalet" user_id = "{!! $value->id !!}">İsteği iptal et</div>
                        @else
                            <div class="btn btn-default btn-xs ag-arkadaslik-istegi-gonder" user_id = "{!! $value->id !!}">Arkadaşı Ekle</div>
                        @endif
                    </td>
                </tr>
            @endforeach
            @if(count($sonucArr)==0)
                {!!  '<div style="text-align:center;width:100%">Kritere uygun rakip bulunamadı</div>'!!}
            @endif
        </table>
    </div>

<div class="clearfix "></div>
<div class="ag-sayfalama-cubugu panel ag-panel">
    {!! $sonucArr->appends(['aranan' => Input::get('aranan')])->render() !!}
</div>

<script>

    $('.pagination').find('a').click(function (event) {
        event.preventDefault();
        data    =$(this).attr('href');
        $.ajax({
            url: data,
            dataType: 'html',
            beforeSend: function() {
                $('#ag-ara').attr("disabled", true);
                islemBar.show();
            },
            complete: function() {
                $('#ag-ara').attr("disabled", false);
                islemBar.hide();
            },
            success: function(json) {
                $('#ag-arama-sonuclari').html(json)
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
        return false;
    })


    $('.ag-arkadaslik-istegi-gonder').click(function () {
        //event.preventDefault();
        budugme = $(this);
        if( budugme.attr('islem')=='iptalet'){
            url    ='arkadas/istekIptalEt/'+$(this).attr('user_id');
        }else{
            url    ='arkadas/arkadasiekle/'+$(this).attr('user_id');
        }
        $.ajax({
            url: url,
            dataType: 'json',
            beforeSend: function() {
                $(budugme).attr("disabled", true);
            },
            complete: function() {
                $(budugme).attr("disabled", false);
            },
            success: function(json) {
                console.log(json);
                if(json['sonuc']=='istek_gonderildi'){
                    budugme.html('İsteği iptal et')
                    budugme.attr('islem','iptalet')
                }
                if(json['sonuc']=='istek_iptal_edildi'){
                    budugme.html('Arkadaşı Ekle')
                    budugme.attr('islem','')
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    })


</script>

    <style>
        #arama-sonuc-tablosu tr td{
            vertical-align: middle;
            font-size: 12px;
        }

        #arama-sonuc-tablosu .ag-kullanici-resmi{
            border: 0px solid #ccc!important;
            width: 16px;
            padding: 0px;
        }

        #arama-sonuc-tablosu .ag-kullanici-resmi:hover{
            width: 100px;
            position: absolute;
            margin-top: -50px;
            margin-left: -25px;
            box-shadow: 2px 2px 8px #6b6b6b;
        }
    </style>

