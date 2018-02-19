<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Input;
use Auth;
use Response;
use App\User;
use Illuminate\Support\Facades\DB;

class MobilUyelikCtrl extends Controller
{
    public $dataArr =  Array();
    function dogrula(){
        $email      = Input::get('email');
        $password   = Input::get('password');
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return Response::json(Auth::user());
        }else{
            return Response::json('0');
        }

    }

    function hesapOlustur(){

        $kontrol_sonucu = array();
        $data['name']       = Input::get('name');
        $data['email']      = Input::get('email');
        $data['password']   = Input::get('password');
        $data['user-role']  = Input::get('user-role');

        if (strlen($data['name'])<1){
            $kontrol_sonucu['name'] = "Adınızı yazmalısınız ";
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            $kontrol_sonucu['email'] = "Eposta adresinizi kontrol ediniz.";
        }
        if (strlen($data['password'])<6){
            $kontrol_sonucu['password'] = "Lütfen parolanızı kontrol ediniz en az 6 karater olmalı";
        }

        // kullanıcı adı varmı
        if (User::where('email', '=', $data['email'])->exists()) {
            $kontrol_sonucu['email_mevcut'] = "Yazdığınız eposta adresi kullanılıyor.";
        }

        if(count($kontrol_sonucu)){
            return Response::json($kontrol_sonucu);
        }

        $user   =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        DB::table('role_to_user')->insert(
            array(
                'role'      => 'uye',
                'user_id'   => $user->id
            )
        );

        return Response::json($user);
    }

    function oturumAcikmi(){
        if (Auth::check()) {
            return Response::json(Auth::user());
        }else{
            return Response::json("null");
        }
    }

    function logout(){
        Auth::logout();
        return Response::json("null");
    }
}
