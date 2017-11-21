<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SoruToUser extends Model
{
    static function getRasgeleSoru($seviye){
        $user_id = Auth::user()->id;
        return SoruToUser::where(['soru_to_users.user_id'=>$user_id])
                        ->leftJoin('sorus', 'sorus.id', '=', 'soru_to_users.soru_id')
                        ->where(['sorus.zorluk'=>$seviye])
                        ->inRandomOrder()->first();
    }
    static function kullanicininSorusunuSil($soru_id){
        $user_id = Auth::user()->id;
        $soru       = SoruToUser::where(['user_id'=>$user_id,'soru_id'=>$soru_id])->first();
        $soru->delete();
    }



}
