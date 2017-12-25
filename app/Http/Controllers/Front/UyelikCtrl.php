<?php

namespace App\Http\Controllers\Front;

use App\User;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use Response;
use App\Ayarlar;
use App\Soru;
use Session;

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

    function getGuvenlikResmi(Request $request){
        $random_string_length = 6;
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $guvenlik_kodu = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $random_string_length; $i++) {
            $guvenlik_kodu .= $characters[mt_rand(0, $max)];
        }
        $request->session()->put('guvenlik_kodu', $guvenlik_kodu);




        $im = imagecreate(100, 30);
        $bg = imagecolorallocate($im, 255, 255, 255);
        $textcolor = imagecolorallocate($im, 0, 0, 255);
        imagestring($im, 5, 0, 0, $guvenlik_kodu, $textcolor);
        header('Content-type: image/png');

        imagepng($im);
        imagedestroy($im);


    }

}
