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



    static   function getKonular($parent_id,$altkonularlaBeraber=false,$cikti_turu='array',$on_sayfada_listele=1){
        Self::altKonulariniGetir($parent_id,$altkonularlaBeraber,$on_sayfada_listele);
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

    static function altKonulariniGetir($parent_id,$altkonularlaBeraber,$on_sayfada_listele){
        $alt_konular = DB::table('konus')->where('parent_id', '=', $parent_id)->where('on_sayfada_listele','=',$on_sayfada_listele)->get();
        foreach ($alt_konular as $konu){
            if($konu->parent_id == $parent_id){
                $konu->seviye           = Self::$hiyerarsik_seviye;
                Self::$konular[Self::$sayac] = $konu;
                Self::$sayac++;
                Self::$hiyerarsik_seviye++;
                if($altkonularlaBeraber){
                    Self::altKonulariniGetir($konu->id,$altkonularlaBeraber,$on_sayfada_listele);
                }
                Self::$hiyerarsik_seviye--;
            }
        }
    }
}
