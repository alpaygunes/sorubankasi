<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Validator;
use Input;
use Redirect;
use Session;
use DB;
use App\Soru;
use Auth;
use Response;

class SoruCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('IsAdmin');
    }

    ##################           KONU EKLEME VE DÜZENLEME  ################
    function form()
    {
        $baslik         = "Soru Ekle";
        //$konu = DB::table('konus')->where('id', '=', 1)->get();// burada sorunun ait olduğu konu idsi alınacak. paretn id si lazım olacak blade içinde
        $dersler        = DB::table('konus')->where('parent_id', '=', 0)->get();

        $derslerArr     = array();
        $derslerArr[0]  = "Bir Ders Seçin";
        foreach ($dersler as $key => $value) {
            $derslerArr[$value->id] = $value->konu_adi;
        }
        return view('admin.soru_form', ['baslik' => $baslik, 'derslerArr' => $derslerArr]);

    }

    #################            KAYDET         ###############################
    function kaydet(Request $request)
    {
        $rules = array(
            'sorumetni' => 'required',
            'konu_id'   => 'required',
            'sinif'     => 'required',
            'yanit'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator);
        } else {
            if (!Input::get('id')) {
                $soru = new Soru();
                $soru->sorumetni    = Input::get('sorumetni');
                $soru->konu_id      = Input::get('konu_id');
                $soru->ekleyen      = Auth::user()->email;
                $soru->soru_turu    = Input::get('soru_turu');
                $soru->yanit        = Input::get('yanit');
                $soru->zorluk       = Input::get('zorluk');
                $soru->sinif        = Input::get('sinif');
                $soru->market_sorusu = Input::get('market_sorusu');
                $soru->save();
            } else {
                $soru               = Soru::find(Input::get('id'));
                $soru->sorumetni    = Input::get('sorumetni');
                $soru->konu_id      = Input::get('konu_id');
                $soru->yanit        = Input::get('yanit');
                $soru->zorluk       = Input::get('zorluk');
                $soru->sinif        = Input::get('sinif');
                $soru->market_sorusu = Input::get('market_sorusu');
                $soru->save();
            }
            return Response::json(array('kaydedildi'));
        }
    }

    #################            LİSTELE         ###############################
    function liste(Request $request)
    {
        $baslik = "Sorular";
        $dersler        = DB::table('konus')->where('parent_id', '=', 0)->get();

        $derslerArr     = array();
        $derslerArr[0]  = "Bir Ders Seçin";
        foreach ($dersler as $key => $value) {
            $derslerArr[$value->id] = $value->konu_adi;
        }

        $whereArray     = array();
        if ($request->isMethod('post')) {
            if(Input::get('konu_id')){
                $request->session()->put('konu_id', Input::get('konu_id'));
            }else{
                $request->session()->forget('konu_id');
            }

            if(Input::get('soru_turu')){
                $request->session()->put('soru_turu', Input::get('soru_turu'));
            }else{
                $request->session()->forget('soru_turu');
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


        if($request->session()->get('konu_id')){
            $whereArray['konu_id']   = $request->session()->get('konu_id');
        }
        if($request->session()->get('soru_turu')){
            $whereArray['soru_turu']   = $request->session()->get('soru_turu');
        }
        if($request->session()->get('zorluk')){
            $whereArray['zorluk']   = $request->session()->get('zorluk');
        }
        if($request->session()->get('sinif')){
            $whereArray['sinif']   = $request->session()->get('sinif');
        }


        $sorular = DB::table('sorus')->where($whereArray)->paginate(15);
        return view('admin.soru_liste', ['baslik'      => $baslik,
                                              'sorularArr'  => $sorular,
                                              'derslerArr'  => $derslerArr,
                                              'whereArray'  => $whereArray]);
    }

    #################            SİL         ###############################
    function sil($id)
    {
        Session::flash('mesaj', 'Silme işlemi tamamlandı');
        Soru::destroy($id);
        return Redirect::back();
    }

    #################            DÜZENLE       ###############################
    function duzenle($id){
        $baslik         = "Soru Düzenle";

        $soru           = DB::table('sorus')->where('id','=', $id)->first();

        //sorunun konu sunun parentini yani dersini çekelim
        $soru_dersi     = DB::table('konus')->where('id', '=', $soru->konu_id)->first();

        $dersler        = DB::table('konus')->where('parent_id', '=', 0)->get();
        $derslerArr     = array();
        foreach ($dersler as $key => $value) {
            $derslerArr[$value->id] = $value->konu_adi;
        }

        $konular        = DB::table('konus')->where('parent_id', '=', $soru_dersi->parent_id)->get();
        $konularArr     = array();
        foreach ($konular as $key => $value) {
            $konularArr[$value->id] = $value->konu_adi;
        }

        return view('admin.soru_form',
            ['baslik'       => $baslik,
             'derslerArr'   => $derslerArr,
             'konularArr'   => $konularArr,
             'soru_dersi'   => $soru_dersi->parent_id,
             'soruArr'         => $soru]);
    }
}
