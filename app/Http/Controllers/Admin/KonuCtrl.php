<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Validator;
use Input;
use Redirect;
use Session;
use DB;
use Response;
use App\Konu;

class KonuCtrl extends Controller
{
    ##################           KONU EKLEME VE DÜZENLEME  ################
    function form(Request $request){
        if(!Input::get('id')){
            $baslik = "Konu Ekle";
        }else{
            $baslik = "Konu Düzenle";
        }

        $konular = Konu::all();
        $konularArr = array();
        $konularArr[0] = "Üst Konu";
        foreach ($konular as $key=>$value) {
            $konularArr[$value->id] = $value->konu_adi;
        }

        return View::make('admin.konu_form',['baslik' => $baslik,'konularArr'=>$konularArr]);
    }

    function duzenle($id){
        $baslik             = "Konu Düzenle";
        $konu               = DB::table('konus')->where('id', $id)->first();
        $konus              = Konu::all();
        $konularArr         = array();
        $konularArr[0]      = "Üst Konu Olsun";
        foreach ($konus as $key=>$value) {
            $konularArr[$value->id]     = $value->konu_adi;
        }
        return view('admin.konu_form', ['baslik' => $baslik, 'konularArr'=>$konularArr, 'konu'=>$konu]);
    }

    #################            KAYDETME         ###############################
    function kaydet(Request $request){
        $rules = array(
            'konu_adi'          => 'required',
            'parent_id'         => 'required'
        );
        $validator         = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {

            if(!Input::get('id')){
                return Redirect::to('/admin/konu/form')->withInput()->withErrors($validator);
            }else{
                return Redirect::to('/admin/konu/duzenle/'.Input::get('id'))->withInput()->withErrors($validator);
            }
        } else {
            if(!Input::get('id')){
                $konu                           = new Konu();
                $konu->konu_adi                 = Input::get('konu_adi');
                $konu->parent_id                = Input::get('parent_id');
                $konu->on_sayfada_listele       = Input::get('on_sayfada_listele');
                $konu->baslangic_tarihi         = Input::get('baslangic_tarihi');
                $konu->bitis_tarihi             = Input::get('bitis_tarihi');
                $konu->save();
                Session::flash('mesaj', 'Kayıt işlemi tamamlandı');
                return Redirect::to('/admin/konu/liste');
            }else{
                $konu                           = Konu::find(Input::get('id'));
                $konu->konu_adi                 = Input::get('konu_adi');
                $konu->parent_id                = Input::get('parent_id');
                $konu->on_sayfada_listele       = Input::get('on_sayfada_listele');
                $konu->baslangic_tarihi         = Input::get('baslangic_tarihi');
                $konu->bitis_tarihi             = Input::get('bitis_tarihi');
                $konu->save();
                Session::flash('mesaj', 'Güncelleme işlemi tamalandı.');
                return Redirect::to('/admin/konu/liste');
            }

        }
    }

    #################################         KONULARI LİSTELE         3#########################
    var $hiyerarsik_seviye  = 0;
    var $sayac              = 0;
    var $konular = array();
    var $konularCopy;

    function liste(){
        $baslik         = "Konular";
        $konular_ham    = DB::table('konus')->paginate(150);
        $konularCopy    = $konular_ham;
        $this->altKonulariniGetir(0);
        // laravel objesini değiştiremediğimden kendi kopyamı oluşturuyorum
        $currentPage        = $konular_ham->currentPage()-1;
        $perPage            = $konular_ham->perPage();
        $start              = $currentPage*$perPage;
        $stop               = $start+$perPage;
        $this->konular      = array_slice($this->konular,$start,$perPage);
        foreach ($konular_ham as $key=>$value){
            $konular_ham[$key]  = $this->konular[$key];
        }
        return view('admin.konu_liste',['baslik' => $baslik,'konularArr'=>$konular_ham]);
    }

    function altKonulariniGetir($parent_id){
        $alt_konular = DB::table('konus')
            ->where('parent_id', '=', $parent_id)->get();
            //->whereDate('baslangic_tarihi', '<=', date("Y-m-d"))
            //->whereDate('bitis_tarihi', '>=', date("Y-m-d"))

        /*ZAMANI GELMEYENLERE zamani_gelemdi İBRASEİNİ BIRAK.
        ÖN SAYFA BUNU DEĞERLENDİR.
        compare data*/
        $today  = date("Y-m-d");
        foreach ($alt_konular as $konu){
            if($konu->parent_id == $parent_id){
                $konu->zamani_gelmis = 0;
                $konu->aciklama = $konu->baslangic_tarihi . ' tarihinden sonra';
                if($today>=$konu->baslangic_tarihi
                    && $today<=$konu->bitis_tarihi){
                    $konu->zamani_gelmis = 1;
                }
                $konu->seviye           = $this->hiyerarsik_seviye;
                $this->konular[$this->sayac] = $konu;
                $this->sayac++;
                $this->hiyerarsik_seviye++;
                $this->altKonulariniGetir($konu->id);
                $this->hiyerarsik_seviye--;
            }
        }
    }

    function sil($id){
        $konu_sorulari = DB::table('sorus')->where('konu_id', '=', $id)->get();
        $cocuklari = DB::table('konus')->where('parent_id', '=', $id)->get();
        if(count($cocuklari)>0){
            Session::flash('alert', 'Kayıt silinemedi !  Silmek istediğiniz konunun alt konuları var. ');
        }else if(count($konu_sorulari)){
            Session::flash('alert', 'Kayıt silinemedi !  Konuya ait sorular var.');
        }else{
            Session::flash('mesaj', 'Silme işlemi tamamlandı');
            Konu::destroy($id);
        }
        return Redirect::back();
    }

    // belli bir konunun alt konularını JSON olarak veriri
    function getKonu($parent_id){
        $this->altKonulariniGetir($parent_id);
        return Response::json($this->konular);
    }

}
