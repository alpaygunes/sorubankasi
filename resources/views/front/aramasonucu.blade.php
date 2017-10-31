    <div class="row">
        <table class="table borderless">
            <?php
            if(strlen(Input::get('aranan'))<3){
                echo "En az üç karater yazmalısınız";
                exit();
            }

            $sayac=0;?>
            @foreach ( $sonucArr as $key => $value )
                <?php $sayac++;?>
                <tr>
                    <td>
                        {{ $sonucArr->perPage()*($sonucArr->currentPage()-1)+$sayac . "." }}
                    </td>
                    <td>
                         {!! $value->name !!}
                    </td>
                    <td>
                        @if($value->istek_gonderildi==1)
                            <div class="btn btn-default btn-xs ag-arkadaslik-istegi-gonder" islem="iptalet" user_id = "{!! $value->id !!}">İsteği iptal et</div>
                        @else
                            <div class="btn btn-default btn-xs ag-arkadaslik-istegi-gonder" user_id = "{!! $value->id !!}">Arkadaşı Ekle</div>
                        @endif
                    </td>
                </tr>
            @endforeach
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