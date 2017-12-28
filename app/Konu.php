<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Http\Response;

class Konu extends Model
{
    static $konular = array();
    static $hiyerarsik_seviye  = 0;
    static  $sayac              = 0;



    static   function getKonular($parent_id,$altkonularlaBeraber=false,$cikti_turu='array',$on_sayfada_listele=1,$sadece_zamani_gelenler=0){
        Self::altKonulariniGetir($parent_id,$altkonularlaBeraber,$on_sayfada_listele,$sadece_zamani_gelenler);
        if($cikti_turu=='JSON'){
            return Response::json(Self::$konular);
        }else if($cikti_turu=='array'){
            $konularArr     = array();
            if($parent_id==0){
                $konularArr[0]  = "Bir Ders Seçin";
            }

            foreach (Self::$konular as $key => $value) {
                $konularArr[$value->id] = $value->konu_adi;
            }
            return $konularArr;
        }
    }

    static function altKonulariniGetir($parent_id,$altkonularlaBeraber,$on_sayfada_listele,$sadece_zamani_gelenler){
        if($sadece_zamani_gelenler){
            // soru seçmek için sadece zamanı gelenler listelenmeli
            $alt_konular = DB::table('konus')
                ->where('parent_id', '=', $parent_id)
                ->where('on_sayfada_listele','=',$on_sayfada_listele)
                ->whereDate('baslangic_tarihi', '<=', date("Y-m-d"))
                ->whereDate('bitis_tarihi', '>=', date("Y-m-d"))
                ->get();
        }else{
            //konuların tamamı listelenir. sorulma tarihi dikkate alınmaz
            $alt_konular = DB::table('konus')->where('parent_id', '=', $parent_id)->where('on_sayfada_listele','=',$on_sayfada_listele)->get();
        }

        foreach ($alt_konular as $konu){
            if($konu->parent_id == $parent_id){
                $konu->seviye           = Self::$hiyerarsik_seviye;
                Self::$konular[Self::$sayac] = $konu;
                Self::$sayac++;
                Self::$hiyerarsik_seviye++;
                if($altkonularlaBeraber){
                    Self::altKonulariniGetir($konu->id,$altkonularlaBeraber,$on_sayfada_listele,$sadece_zamani_gelenler);
                }
                Self::$hiyerarsik_seviye--;
            }
        }
    }
}
