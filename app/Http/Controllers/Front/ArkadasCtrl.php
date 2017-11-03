<?php

namespace App\Http\Controllers\Front;

use App\Arkadas;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Input;
use Response;
use DB;

class ArkadasCtrl extends Controller
{
    function getList(){
        $baslik     = "Rakiplerim";
        $hepsi      = User::getArkadaslar();
        return view('front.arkadas',['baslik' => $baslik,'arkadaslarArr' => $hepsi]);
    }

    function arkadasAra(){
        //mevcut arkadaşlar
        $arkadaslarim   = DB::table('arkadas')->where('arkadas_id','=',Auth::user()->id)
                                                ->orwhere('user_id','=',Auth::user()->id)
                                                ->where('arkadaslik_istegi','=',null)
                                                ->select('user_id','arkadas_id')->get();
        $arkadaslarIDs = array();
        foreach ($arkadaslarim as $key=>$value){
            $arkadaslarIDs[]=$value->user_id;
            $arkadaslarIDs[]=$value->arkadas_id;
        }
        //keniside listelenmemeli
        $arkadaslarIDs[] = Auth::user()->id;


        $aranan = Input::get('aranan');
        $sonuclarArr  = User::where('name','LIKE',"%$aranan%")->whereNotIn('id',$arkadaslarIDs)
                        ->orwhere('email','LIKE',"%$aranan%")->whereNotIn('id',$arkadaslarIDs)
                        ->select('name','id')
                        ->paginate(4);

        // önceden istek gönderilenleri belirt
        $istek_gonderilenler   = DB::table('arkadas')
                                ->where('arkadaslik_istegi','=',1)
                                ->where('user_id','=',Auth::user()->id)->get();
        $istekGonderilenlerIDs = array();
        foreach ($istek_gonderilenler as $key=>$value){
            $istekGonderilenlerIDs[]=$value->arkadas_id;
        }

        foreach ($sonuclarArr as $sonuc){
            if(in_array($sonuc->id ,$istekGonderilenlerIDs)){
                $sonuc->istek_gonderildi = 1;
            }
        }

        return view('front.aramasonucu',['sonucArr' => $sonuclarArr]);
    }

    function arkadasiEkle($user_id){
        $istek = Arkadas::where('user_id'   ,'=',Auth::user()->id)
                        ->where('arkadas_id','=',$user_id)->get();
        if($istek->count()){
            return Response::json(array('hata'=>'Kullanıcı arkadaş listenizde yada istek zaten gönderilmiş.'));
        }else{
            $istek              = new Arkadas();
            $istek->user_id     = Auth::user()->id;
            $istek->arkadas_id  = $user_id;
            $istek->arkadaslik_istegi = '1';
            $istek->save();
            return Response::json(array('sonuc'=>'istek_gonderildi'));
        }
    }

    function istekIptalEt($user_id){
        $istek = Arkadas::where('user_id'   ,'=',Auth::user()->id)
                        ->where('arkadas_id','=',$user_id)->first();
        if($istek){
            $istek->delete();
            return Response::json(array('sonuc'=>'istek_iptal_edildi'));
        }
    }

    function getArkadaslikIstekleri(){
        $istek = DB::table('arkadas')
            ->leftJoin('users', 'users.id', '=', 'arkadas.user_id')
            ->leftJoin('profiles', 'profiles.user_id', '=', 'arkadas.arkadas_id')
            ->select('users.id','users.name','profiles.profil_resmi')
            ->where('arkadas.arkadaslik_istegi','=',1)
            ->where('arkadas.arkadas_id','=',Auth::user()->id)
            ->get();

        return Response::json($istek);
    }

    function istegiYap($islem, $arkadas_id){
        $istek = Arkadas::where('arkadas_id','=',Auth::user()->id)
                        ->where('user_id','=',$arkadas_id)->first();
        if($istek==null){
            return Response::json(array('sonuc'=>'istek_bulunamadi'));
        }
        if($islem=='onayla'){
            $istek->arkadaslik_istegi=null;
            $istek->save();
            return Response::json(array('sonuc'=>'onaylandi'));
        }else if($islem=='kaldir'){
            $istek->delete();
            return Response::json(array('sonuc'=>'isteksilindi'));
        }
    }

    function listedenSil($arkadas_id){
        DB::table('arkadas_sil')->insert(
            ['arkadas_id' => $arkadas_id,'user_id' => Auth::user()->id]
        );


        return Response::json(array('sonuc'=>'listeden_silindi'));
    }

    function engelle($arkadas_id){
        DB::table('arkadas_engelle')->insert(
            ['arkadas_id' => $arkadas_id,'user_id' => Auth::user()->id]
        );
        return Response::json(array('sonuc'=>'engellendi'));
    }

    function engeliKaldir($arkadas_id){
        DB::table('arkadas_engelle')->where(['arkadas_id' => $arkadas_id,'user_id' => Auth::user()->id])->delete();
        return Response::json(array('sonuc'=>'engel_kaldirildi'));
    }

    function silineniGeriAl($arkadas_id){
        DB::table('arkadas_sil')->where(['arkadas_id' => $arkadas_id,'user_id' => Auth::user()->id])->delete();
        return Response::json(array('sonuc'=>'silinme-geri-alindi'));
    }

    function engellediklerim(){
        $engellediklerim = DB::table('arkadas_engelle')
                            ->leftJoin('users', 'users.id', '=', 'arkadas_engelle.arkadas_id')
                            ->leftJoin('profiles', 'profiles.user_id', '=', 'arkadas_engelle.arkadas_id')
                            ->select('users.id','users.name','profiles.profil_resmi')
                            ->where('arkadas_engelle.user_id','=',Auth::user()->id)->get();
        $engellediklerimArr = array();
        foreach ($engellediklerim as $user){
            $engellediklerimArr[] = $user;
        }
        return Response::json($engellediklerimArr);
    }

    function silinenler(){
        $sildiklerim = DB::table('arkadas_sil')
            ->leftJoin('users', 'users.id', '=', 'arkadas_sil.arkadas_id')
            ->leftJoin('profiles', 'profiles.user_id', '=', 'arkadas_sil.arkadas_id')
            ->select('users.id','users.name','profiles.profil_resmi')
            ->where('arkadas_sil.user_id','=',Auth::user()->id)->get();
        $sildiklerimArr = array();
        foreach ($sildiklerim as $user){
            $sildiklerimArr[] = $user;
        }
        return Response::json($sildiklerimArr);
    }

}
