<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    static function getUserYetki(){
        $user   = Auth::user();
        if($user){
            $yetki  = DB::table('role_to_user')->where('user_id', $user->id)->first();
        }else{
             return null;
        }
        return $yetki->role;
    }

    static function isUserRole($role){
        $user   = Auth::user();
        if($user){
            $yetki  = DB::table('role_to_user')->where('user_id', $user->id)->first();
        }else{
            return false;
        }
        return $yetki->role==$role?true:false;
    }

    static function getOdul($user_id=null){
        $user_id=$user_id==null?Auth::user()->id:$user_id;
        return OdulToUser::where(['user_id'=>$user_id ])->get();
    }

    static function getSorular($user_id=null){
        $user_id=$user_id==null?Auth::user()->id:$user_id;
        return SoruToUser::where(['user_id'=>$user_id ])->get();
    }

    static function getArkadaslar($user_id=null,$sayfa_basina_kayit=20){
        $onlarinlistesindeyim   = DB::table('arkadas')
            ->leftJoin('users', 'users.id', '=', 'arkadas.user_id')
            ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id')
            ->select('users.id','users.name','profiles.profil_resmi','arkadas.arkadaslik_istegi')
            ->where('arkadas.arkadaslik_istegi','=',null)
            ->where('arkadas.arkadas_id','=',Auth::user()->id)
            ->paginate($sayfa_basina_kayit);

        $onlarbenimlistemde     = DB::table('arkadas')
            ->leftJoin('users', 'users.id', '=', 'arkadas.arkadas_id')
            ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id')
            ->select('users.id','users.name','profiles.profil_resmi','arkadas.arkadaslik_istegi')
            ->where('arkadas.arkadaslik_istegi','=',null)
            ->where('arkadas.user_id','=',Auth::user()->id)
            ->paginate($sayfa_basina_kayit);

        $hepsi  = $onlarinlistesindeyim;
        foreach ($onlarbenimlistemde as $key=>$value){
            $hepsi[]=$value;
        }


        $engellediklerim        = DB::table('arkadas_engelle')->where('user_id','=',Auth::user()->id)->get();
        foreach ($engellediklerim as $key=>$value){
            foreach($hepsi as $kkey=>$vvalue){
                if($vvalue->id == $value->arkadas_id){
                    $vvalue->engelle=1;
                }
            }
        }

        $listeden_sildiklerim        = DB::table('arkadas_sil')->where('user_id','=',Auth::user()->id)->get();
        foreach ($listeden_sildiklerim as $key=>$value){
            foreach($hepsi as $kkey=>$vvalue){
                if($vvalue->id == $value->arkadas_id){
                    unset($hepsi[$kkey]);
                }
            }
        }
        return $hepsi;
    }

    static function getBilgiler($user_id){
        $bilgiler  = DB::table('users')->where('id', $user_id)->select('id','name','email')->first();
        return $bilgiler;
    }
}
