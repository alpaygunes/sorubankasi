<?php

namespace App\Http\Controllers\Oyun;

use App\Http\Controllers\Controller;
use App\Konu;
use App\OdulToUser;
use Illuminate\Support\Facades\Auth;
use Input;
use Response;
use Illuminate\Http\Request;
use DB;
use Session;

class SoruCozCtrl extends Controller
{
    function index()
    {
        $baslik = "Soru Çöz";
        $dersler = Konu::getKonular(0,$altkonularlaBeraber=false,$cikti_turu='array');
        return view('oyun.sorucoz',['baslik' => $baslik,'derslerArr' => $dersler]);
    }

    function getKonular(){
        Konu::getKonular(0,$altkonularlaBeraber=false,$cikti_turu='JSON');
    }

    function filtreleriKaydet(Request $request){
        if ($request->isMethod('post')) {
            if(Input::get('konu_id')){
                $request->session()->put('konu_id', Input::get('konu_id'));
            }else{
                $request->session()->forget('konu_id');
            }
            if(Input::get('zorluk')){
                $request->session()->put('zorluk', Input::get('zorluk'));
            }else{
                $request->session()->forget('zorluk');
            }
            if(Input::get('sinif')){
                $request->session()->put('sinif', Input::get('sinif'));
            }else{
                $request->session()->forget('sinif');
            }
        }
        // tutaln değerleri unut
        $request->session()->forget('gonderilen_soru');
        $request->session()->forget('gonderilmisSoruIdArr');
        $request->session()->forget('dogru_cevap_sayisi');
        $soru = $this->getSoru($request);
        if($soru==null){
            $sonuc['soruyok'] = 1;
        }else{
            $sonuc['soru'] = $this->getSoru($request);
        }
        return Response::json($sonuc);
    }

    var $gonderilmisSoruIdArr = array();
    var $sonuc                = array();
    function getSoru(Request $request){
        $whereArray                 = array();
        if($request->session()->get('konu_id')){
            $whereArray['konu_id']   = $request->session()->get('konu_id');
        }
        if($request->session()->get('zorluk')){
            $whereArray['zorluk']   = $request->session()->get('zorluk');
        }
        if($request->session()->get('sinif')){
            $whereArray['sinif']    = $request->session()->get('sinif');
        }
        $soru                       = DB::table('sorus')->where($whereArray)->inRandomOrder()->first();
        if(!count($soru)){
            return null;
        }
        $gonderilmisSoruIdArr       = $request->session()->get('gonderilmisSoruIdArr');
        $gonderilmisSoruIdArr[]     = $soru->id;
        $request->session()->put('gonderilen_soru', $soru);
        $request->session()->put('gonderilmisSoruIdArr', $gonderilmisSoruIdArr);
        return $soru;
    }



    function cevapKontrol($yanit,Request $request){
        $gonderilmis_soru = $request->session()->get('gonderilen_soru');
        $request->session()->forget('gonderilen_soru');
        $user_id                        = Auth::user()->id;
        $odul_turu      = 'altin';
        $miktar         = 100*$gonderilmis_soru->zorluk;
        if(strtolower($yanit)==strtolower($gonderilmis_soru->yanit)){
            $dogru_cevap_sayisi = $request->session()->get('dogru_cevap_sayisi');
            $dogru_cevap_sayisi++;
            $request->session()->put('dogru_cevap_sayisi', $dogru_cevap_sayisi);
            $sonuc['dogru_cevap']           = 1;
            $sonuc['dogru_cevap_sayisi']    = $request->session()->get('dogru_cevap_sayisi');
            $sonuc['odul_turu']             = $odul_turu;
            $sonuc['doul_miktar']           = $miktar;
            OdulToUser::kullaniciOdulunuGuncelle($user_id,$odul_turu,$miktar);
            return Response::json($sonuc);
        }else{
            $sonuc['yanlis_cevap']          = 1;
            $sonuc['dogru_cevap_sayisi']    = $request->session()->get('dogru_cevap_sayisi');
            $miktar = -$miktar;
            OdulToUser::kullaniciOdulunuGuncelle($user_id,$odul_turu,$miktar);
            return Response::json($sonuc);
        }

    }

    function soruVer(Request $request){
        $sonuc['soru'] = $this->getSoru($request);
        return Response::json($sonuc);
    }
}
