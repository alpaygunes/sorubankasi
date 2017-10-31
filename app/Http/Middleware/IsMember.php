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
        $user       = User::getUserYetki();
        return $next($request);
    }
}
