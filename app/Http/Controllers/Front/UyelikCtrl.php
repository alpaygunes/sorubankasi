<?php

namespace App\Http\Controllers\Front;

use App\User;
use Auth;
use App\Http\Controllers\Controller;
use Input;
use Illuminate\Support\Facades\Session;
use Response;
use App\Ayarlar;
use App\Soru;

class UyelikCtrl extends Controller
{
    function girisFormu(){
        return view('front.uyelik.girisformu');
    }

    function girisYap(){
        $email      = Input::get('email');
        $password   = Input::get('password');
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Authentication passed...
            return redirect()->intended('/');
        }else{
            Session::flash('alert', 'Eposta ve parolanız uyumsuz.');
            return view('front.uyelik.girisformu');
        }
    }

    function parolamiUnuttum(){
        return view('front.uyelik.email');
    }

    function uyeol(){
        return view('front.uyelik.uyelikformu');
    }

    function getArkadasListesi($user_id=null){
        $hepsi = User::getArkadaslar($user_id,9999);
        return Response::json($hepsi->items());
    }

    function getKullaniciVarliklari($user_id=null){
        $ayarlarArr                 = Ayarlar::where('id','=',$user_id)->first();
        $varliklarimArr             = array();
        $oduller                    = User::getOdul($user_id);
        $varliklarimArr['oduller']  = $oduller;
        $sorular                    = User::getSorular($user_id);
        $sorular                    = $this->sorularinDetaylariniCek($sorular);
        $varliklarimArr['sorular']  = $sorular;
        return Response::json($varliklarimArr);
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
