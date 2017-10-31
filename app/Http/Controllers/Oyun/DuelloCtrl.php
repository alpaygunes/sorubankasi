<?php

namespace App\Http\Controllers\Oyun;

use App\Duello;
use App\Soru;
use App\User;
use App\OdulToUser;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use App\Ayarlar;

class DuelloCtrl extends Controller
{
    function index(){
        $baslik     = "Duello";
        return view('oyun.duello',['baslik' => $baslik]);
    }

    function bunuSec($rakip_id){
        $baslik             = "Duello";
        $rakip_bilgileri    = User::getBilgiler($rakip_id);
        session(['rakip_user_id' => $rakip_id]);

        if($this->sonSoruyuKimGondermis($rakip_id)=='kendisi'){
            Session::flash('alert', 'Son soruyu siz göndermişsiniz. Rakibinizeden soru bekliyorsunuz');
            //return redirect()->action('Oyun\DuelloCtrl@index');
        };

        return view('oyun.bunusec',['baslik' => $baslik,'rakip_bilgileri' => $rakip_bilgileri]);
    }

    function rakibeBirSoruGonder($seviye){
        $rakib_user_id  =  session('rakip_user_id');

        if($this->sonSoruyuKimGondermis($rakib_user_id)=='kendisi'){
            return Response::json(array('hata'=>'sira_rakipte'));
        };

        //seviyeden bir soru seç rasgele olsun
        $soru       = Soru::getRasgeleSoru($seviye);
        $soru_id    = $soru->id;

        if($soru->id){
            $gonder = DB::table('duellos')
                    ->insert(
                        ['gonderen_id' => Auth::user()->id,
                         'rakip_id' => $rakib_user_id,
                         'soru_id'=>$soru_id]
                        );
        }

        return Response::json(array($soru_id));
    }

    function sonSoruyuKimGondermis($rakip_id){
        // kullanıcının başlattıkları
        $duello_devameden_user   = Duello::where('gonderen_id','=',Auth::user()->id)
                                        ->where('rakip_id','=',$rakip_id)
                                        ->where('kazanan_id','=',null)
                                        ->orderBy('id', 'desc')->first();

        //rakibin başlattıkları
        $duello_devameden_rakip   = Duello::where('gonderen_id','=',$rakip_id)
                                        ->where('rakip_id','=',Auth::user()->id)
                                        ->where('kazanan_id','=',null)
                                        ->orderBy('id', 'desc')->first();
        if($duello_devameden_rakip){
            $duello_rakip_id = $duello_devameden_rakip->id;
        }else{
            $duello_rakip_id = 0;
        }

        if($duello_devameden_user){
            $duello_user_id = $duello_devameden_user->id;
        }else{
            $duello_user_id = 0;
        }


        $son_soran_id = null;
        if($duello_rakip_id > $duello_user_id){
            return 'rakibi';
        }else if($duello_rakip_id < $duello_user_id){
            return 'kendisi';
        }

    }

    function getUserDuellos($user_id=null){
        $user_id=$user_id==null?Auth::user()->id:$user_id;

        // sıranın kullanıcıda olduğu duello ları listele meliyim.

        $gonderdiklerim  = DB::table('duellos')
                                ->where('duellos.gonderen_id','=',$user_id)
                                ->leftJoin('users', 'users.id', '=', 'duellos.rakip_id')
                                ->leftJoin('profiles', 'profiles.user_id', '=', 'duellos.rakip_id')
                                ->select('users.name','users.id AS user_id','profiles.profil_resmi'
                                        ,'duellos.id AS duello_id','duellos.gonderen_id','duellos.kazanan_id')->get();

        $bana_gelenler = DB::table('duellos')
                                ->where('duellos.rakip_id','=',$user_id)
                                ->where('duellos.kazanan_id','=',null)
                                ->leftJoin('users', 'users.id', '=', 'duellos.gonderen_id')
                                ->leftJoin('profiles', 'profiles.user_id', '=', 'duellos.gonderen_id')
                                ->select('users.name','profiles.profil_resmi','duellos.id')->get();
        // kazandimi
        foreach ($gonderdiklerim as $gonderi){
            if($gonderi->kazanan_id == $user_id){
                $gonderi->kazandin  = 1;
            }else if($gonderi->kazanan_id==null){
                $gonderi->kazandin  = 0;
            }else{
                $gonderi->kazandin  = 2;
            }
        }

        return Response::json(array('rakibe_sorduklarim'=>$gonderdiklerim
                                    ,'bana_gelenler'=>$bana_gelenler));

    }

    function gelenHamleyiGor($duello_id){
        $user_id=Auth::user()->id;
        $duello = Duello::where('duellos.id','=',$duello_id)
                        ->where('duellos.rakip_id','=',$user_id)
                        ->leftJoin('users', 'users.id', '=', 'duellos.gonderen_id')
                        ->leftJoin('profiles', 'profiles.user_id', '=', 'duellos.gonderen_id')
                        ->leftJoin('sorus', 'sorus.id', '=', 'duellos.soru_id')
                        ->leftJoin('konus', 'konus.id', '=', 'sorus.konu_id')
                        ->select('users.name'
                            ,'duellos.rakip_id','duellos.gonderen_id','duellos.kazanan_id'
                            ,'profiles.profil_resmi'
                            ,'sorus.zorluk','sorus.sorumetni','sorus.soru_turu','sorus.yanit'
                            ,'konus.parent_id')->first();

        // sorunun ödül değerini bulalım
        $ayarlar        = Ayarlar::where('id','>',0)->first();
        $zmAlanAdlariArr  = array('fiyat_zm_cok_kolay','fiyat_zm_kolay','fiyat_zm_normal','fiyat_zm_zor','fiyat_zm_cok_zor');
        $dsAlanAdlariArr  = array('fiyat_ds_cok_kolay','fiyat_ds_kolay','fiyat_ds_normal','fiyat_ds_zor','fiyat_ds_cok_zor');
        // eğer zeka mantık sorusu ise
        if($ayarlar->zeka_ve_mantik_sorulari_kazananid==$duello->parent_id){
            $alan_adi    = $zmAlanAdlariArr[$duello->zorluk-1];// 0 dan başlması için -1
        }else{
            $alan_adi    = $dsAlanAdlariArr[$duello->zorluk-1];// 0 dan başlması için -1
        }
        $odul            = $ayarlar->$alan_adi;
        $duello->odul    = $odul;


        if(!$duello){
            return Response::json(array('hata'=>'Duelo bulunamadı'));
        }else{
            Session::put('duello_id', $duello_id);
            Session::put('duello_sorumetni', $duello->sorumetni);
            Session::put('duello_soru_turu', $duello->soru_turu);
            Session::put('duello_soru_dogru_secenek', $duello->yanit);
            Session::put('duello_soru_sure', $ayarlar->soru_basina_sure);
            Session::put('duello_gonderen_id', $duello->gonderen_id);
            Session::put('duello_odul', $duello->odul);
            unset($duello->sorumetni);// sorumetnini göndermeyelim
            unset($duello->yanit);// sorumetnini göndermeyelim

            $kalan_zaman = $this->surecKontrol(0);
            if($kalan_zaman<=0){
                return Response::json(array('hata'=>'zaman_bitti'));
            }

            return Response::json($duello);
        }
    }

    function gonderdigimHamleyiGor($duello_id){
        $user_id    = Auth::user()->id;
        $duello     = Duello::where('duellos.id','=',$duello_id)
                    ->where('duellos.gonderen_id','=',$user_id)
                    ->leftJoin('users', 'users.id', '=', 'duellos.rakip_id')
                    ->leftJoin('profiles', 'profiles.user_id', '=', 'duellos.rakip_id')
                    ->leftJoin('sorus', 'sorus.id', '=', 'duellos.soru_id')
                    ->leftJoin('konus', 'konus.id', '=', 'sorus.konu_id')
                    ->select('users.name'
                        ,'duellos.rakip_id','duellos.gonderen_id','duellos.kazanan_id'
                        ,'profiles.profil_resmi'
                        ,'sorus.zorluk','sorus.sorumetni','sorus.soru_turu','sorus.yanit'
                        ,'konus.parent_id')->first();

        // sorunun ödül değerini bulalım
        $ayarlar          = Ayarlar::where('id','>',0)->first();
        $zmAlanAdlariArr  = array('fiyat_zm_cok_kolay','fiyat_zm_kolay','fiyat_zm_normal','fiyat_zm_zor','fiyat_zm_cok_zor');
        $dsAlanAdlariArr  = array('fiyat_ds_cok_kolay','fiyat_ds_kolay','fiyat_ds_normal','fiyat_ds_zor','fiyat_ds_cok_zor');
        // eğer zeka mantık sorusu ise
        if($ayarlar->zeka_ve_mantik_sorulari_kazananid==$duello->parent_id){
            $alan_adi    = $zmAlanAdlariArr[$duello->zorluk-1];// 0 dan başlması için -1
        }else{
            $alan_adi    = $dsAlanAdlariArr[$duello->zorluk-1];// 0 dan başlması için -1
        }
        $odul            = $ayarlar->$alan_adi;
        $duello->odul    = $odul;

        // kazandimi
        if($duello->kazanan_id == $user_id){
            $duello->kazandin  = 1;
        }else if($duello->kazanan_id==null){
            $duello->kazandin  = 0;
        }else{
            $duello->kazandin = 2;
        }


        if(!$duello){
            return Response::json(array('hata'=>'Duelo bulunamadı'));
        }else{
            Session::put('duello_id', $duello_id);
            Session::put('duello_sorumetni', $duello->sorumetni);
            unset($duello->sorumetni);// sorumetnini göndermeyelim
            unset($duello->yanit);// sorumetnini göndermeyelim

            //sonuçlanmışsa silelim
            $duello_silinecek     = Duello::where('duellos.id','=',$duello_id)
                                    ->where('duellos.gonderen_id','=',$user_id)
                                    ->where('duellos.kazanan_id','<>',null)->first();
            if($duello_silinecek){
                $duello_silinecek->delete();

            }
            return Response::json($duello);
        }
    }

    function soruyuGoster($komut){
        $duello_id          = Session::get('duello_id');
        $duello_sorumetni   = Session::get('duello_sorumetni');
        $soru_turu          = Session::get('duello_soru_turu');
        $kalan_zaman        = 1;

        // önizlemede süre başlamasın
        if($komut=='sureyi_baslat'){
            $kalan_zaman = $this->surecKontrol();
        }

        if($kalan_zaman<=0){
            return Response::json(array('hata'=>'zaman_bitti'));
        }else{
            return Response::json(array('duello_sorumetni'=>$duello_sorumetni
                                        ,'soru_turu'=>$soru_turu));
        }
    }

    function surecKontrol($sureyi_baslat=1){
        $kalan_zaman        = 0;
        $duello_id = Session::get('duello_id');
        // soruyu gördüğse sureyi başlatalım. duello tablosuna işleyelim
        $duello             = Duello::find($duello_id);

        //sadece kanal zamani öğrenelim fakat süreyi başlatmayım. duelloyu gör için bu uygun. soruyu görmüyor nede olsa
        if($sureyi_baslat==0){
            if($duello->son_cevaplama_zamani==null){
                return 1;// maksat süre bitti demesin
            }
            $kalan_zaman =  $duello->son_cevaplama_zamani -  time();
            return $kalan_zaman; // kalan zamnı döndür
        }
        if(!$duello->son_cevaplama_zamani){
            $duello->son_cevaplama_zamani     = time() + Session::get('duello_soru_sure');
            $duello->save();
            $kalan_zaman = Session::get('duello_soru_sure');
        }else{
            $kalan_zaman =  $duello->son_cevaplama_zamani -  time();
        }
        return $kalan_zaman;
    }

    function cevapKontrol($cevap){
        $duello_id         = Session::get('duello_id');
        $duello_odul       = Session::get('duello_odul');
        $kalan_zaman       = $this->surecKontrol();
        if($kalan_zaman<=0){
            return Response::json(array('hata'=>'zaman_bitti'));
        }else{
            $dogru_cevap            = Session::get('duello_soru_dogru_secenek');
            if($dogru_cevap==$cevap){
                // kendi ödülü artacak
                OdulToUser::kullaniciOdulunuGuncelle(Auth::user()->id,'altin',$duello_odul);
                // rakibinki azalacak
                OdulToUser::kullaniciOdulunuGuncelle(Session::get('duello_gonderen_id'),'altin',-$duello_odul);
                // duello durumu değişecek
                $kazanan_id = Auth::user()->id;
                $dogru='dogru';
            }else{
                // kendi ödülü azalacak
                OdulToUser::kullaniciOdulunuGuncelle(Auth::user()->id,'altin',-$duello_odul);
                // rakibin ödülü artacak
                OdulToUser::kullaniciOdulunuGuncelle(Session::get('duello_gonderen_id'),'altin',$duello_odul);
                // duello durumu değişecek
                $kazanan_id = Session::get('duello_gonderen_id');
                $dogru='yanlis';
            }
        }

        $duello             =  Duello::find($duello_id);
        $duello->kazanan_id = $kazanan_id;
        $duello->save();

        Session::forget('duello_id');
        Session::forget('duello_sorumetni');
        Session::forget('duello_soru_turu');
        Session::forget('duello_soru_dogru_secenek');
        Session::forget('duello_soru_sure');
        Session::forget('duello_gonderen_id');
        Session::forget('duello_odul');

        return Response::json(array('sonuc'=>$dogru));
    }

}
