<?php

namespace App\Http\Controllers\Front;

use App\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Input;
use Illuminate\Support\Facades\Auth;

class ProfilCtrl extends Controller
{
    function profil(){
        $baslik         ="Profil bilgilerim";
        $profilArr      =Profile::where(['user_id'=>Auth::user()->id])->first();
        return view('front.profil.profil',['baslik' => $baslik,'profilArr' => $profilArr]);
    }

    function kaydet(Request $request){
        $baslik ="Profil bilgilerim";

        $profil =Profile::where(['user_id'=>Auth::user()->id])->first();
        if($profil){
            $profil->profil_resmi   = 'images/noimage.png';
            $profil->sinif          = Input::get('sinif');
        }else{
            $profil                 = new Profile;
            $profil->profil_resmi   = 'images/noimage.png';
            $profil->sinif          = Input::get('sinif');
            $profil->user_id        = Auth::user()->id;
        }


        if($request->file('profilresmi')){
            $imageName = Auth::user()->id . '.' .$request->file('profilresmi')->getClientOriginalExtension();
            if(in_array($request->file('profilresmi')->getClientOriginalExtension(),array('jpg','png','gif'))){
                $request->file('profilresmi')->move(base_path() . '/public/images/profilresimleri/', $imageName);
                $profil->profil_resmi   = '/images/profilresimleri/'. $imageName;
            }else{
                Session::flash('alert', 'Profil resminizin formatı JPG,PNG veya GIF olabilir. Kayıt başarısız.');
                return redirect()->intended('/profil');
            }
        }
        Session::flash('mesaj', 'Kaydedildi');
        $profil->save();
        return redirect()->intended('/profil');
    }
}
