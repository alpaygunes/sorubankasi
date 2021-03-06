<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use App\User;

class IsMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_role          = User::getUserYetki();
        if($user_role=='uye' || $user_role=='admin'){
            return $next($request);
        }
        return redirect('/');
    }
}
