<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OdulToUser extends Model
{
    protected $table = "odul_to_user";
    static function kullaniciOdulunuGuncelle($user_id,$odul_turu,$miktar){
        $kayit  = OdulToUser::where(['user_id'=> $user_id,'odul_turu'=> $odul_turu])->first();
        if($kayit){
            if($kayit->miktar+$miktar<0){
                $kayit->miktar = 0;
            }else{
                $kayit->miktar = $kayit->miktar+$miktar;
            }
        }else{
            $kayit              = new OdulToUser;
            $kayit->miktar      = $miktar;
            $kayit->user_id     = $user_id;
            $kayit->odul_turu   = $odul_turu;
        }
        $kayit->save();
    }
}
