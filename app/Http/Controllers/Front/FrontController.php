<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            return view('front.home');
        }else{
            return view('hosgeldin');
        }
    }
}
