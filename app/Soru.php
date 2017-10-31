<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soru extends Model
{
    static function getRasgeleSoru($seviye){
        return Soru::where(['zorluk'=>$seviye])->inRandomOrder()->first();
    }
}
