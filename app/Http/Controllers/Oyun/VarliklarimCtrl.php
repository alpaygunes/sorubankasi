<?php

namespace App\Http\Controllers\Oyun;

use App\Ayarlar;
use App\Http\Controllers\Controller;
use App\Konu;
use App\Soru;
use App\SoruToUser;
use Input;
use Response;
use App\User;
use Auth;
use App\OdulToUser;

class VarliklarimCtrl extends Controller
{
    function varliklarim($format=null){
        $ayarlarArr  = Ayarlar::where('id','=',1)->first();

        $baslik                     ='Valıklarım';
        $varliklarimArr             = array();
        $oduller                    = User::getOdul();
        $varliklarimArr['oduller']  = $oduller;
        $sorular                    = User::getSorular();
        $sorular                    = $this->sorularinDetaylariniCek($sorular);
        $varliklarimArr['sorular']  = $sorular;

        if($format){
            return Response::json($varliklarimArr);
        }
        return view('oyun.varliklarim',['baslik' => $baslik,'varliklarimArr' => $varliklarimArr,'ayarlarArr'=>$ayarlarArr]);
    }

    function satinalmayiOnayla(){
        $ayarlar        = Ayarlar::where('id','=',1)->first();
        $tur            = Input::get('tur');
        $seviye         = Input::get('seviye');
        $istene_miktar  = Input::get('miktar');
        $fiyat = $ayarlar->$tur;

        $toplam_maliyet = $istene_miktar * $fiyat;
        // şartlar uygunsa alışı yap varlıkları güncelle
        // Yeterli altın varmı ?

        $oduller                = User::getOdul();
        $sahip_olunan_altin     = $oduller[0]->miktar;

        if($sahip_olunan_altin<$toplam_maliyet){
            return Response::json(['hata'=>'Bakiyeniz yeterli değil']);
        }

        /*2 - Yeterli soru varmı ?*/
        if($tur=='fiyat_zm_cok_kolay'
                || $tur=='fiyat_zm_kolay'
                || $tur=='fiyat_zm_normal'
                || $tur=='fiyat_zm_zor'
                || $tur=='fiyat_zm_cok_zor'){
           // okul dersleri haricindeki sorulardan seçilece
            //  zeka ve mantık sorularının id si bilinmediğinden admin ayar sayfasında belirtilmeli
            $konu_idleri = Konu::getKonular($ayarlar->zeka_ve_mantik_sorulari_id,true,'array',0);
            foreach ($konu_idleri as $key=>$value){
                $konu_idArr[]=$key;
            }
            $sorular    = Soru::where('market_sorusu','=',1)
                                    ->where('zorluk','=',$seviye)
                                    ->whereIn('konu_id',$konu_idArr)
                                    ->limit($istene_miktar)
                                    ->inRandomOrder()
                                    ->get();
        }else{
            $konu_idleri = Konu::getKonular($ayarlar->zeka_ve_mantik_sorulari_id,true,'array',0);
            foreach ($konu_idleri as $key=>$value){
                $konu_idArr[]=$key;
            }
            $sorular    = Soru::where('market_sorusu','=',1)
                        ->where('zorluk','=',$seviye)
                        ->whereNotIn('konu_id',$konu_idArr)
                        ->limit($istene_miktar)
                        ->inRandomOrder()->get();
        }

        if($sorular->count()<$istene_miktar){
            return Response::json(['hata'=>'İstediğiniz kadar soru bulunamadı']);
        }

        // kontroller tamamsa satın al
        foreach ($sorular as $soru){
            $sorutouser           = new SoruToUser();
            $sorutouser->user_id  = Auth::user()->id;
            $sorutouser->soru_id  = $soru->id;
            $sorutouser->save();
        }
        OdulToUser::kullaniciOdulunuGuncelle(Auth::user()->id,'altin',-$toplam_maliyet);

        return Response::json(['tur'=>$tur]);
    }

    protected function sorularinDetaylariniCek($sorular){
        $detayliSorularArr = array();
        foreach ($sorular as $soru){
            $sorunun_detaylari = Soru::where('id','=',$soru->soru_id)->select('zorluk')->first();
            $detayliSorularArr[] = $sorunun_detaylari;
        }
        return $detayliSorularArr;
    }
}
