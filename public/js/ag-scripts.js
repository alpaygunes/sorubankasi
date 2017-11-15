/**
 * Created by alpay on 14.11.2017.
 */


//--------------------------- GERİ SAYIM FONSİYONU -----------------------//
function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    return window.setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            //timer = duration;
            sayacSureBitti();
        }
    }, 1000);
}

function sayacSureBitti() {
    window.clearInterval(sayac_id);
    $.ajax({
        url: '/duello/cevapKontrol/zaman_bitti',
        dataType: 'json',
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(veri) {
            $('#agDuelloDetayModal').modal('hide');
            $('#agMesajBoxModal').modal('show')
            $('#agMesajBoxModal .modal-body').html("<img src='/bgimages/zamanbitti.gif'>")
            $('#agMesajBoxModal .modal-title').html('KAYBETTİN !')
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
