<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\User;
use Session;
use Symfony\Component\HttpFoundation\Response;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      if(auth()->guard('web')->check()){
        $user = auth()->guard('web')->user();
        if($user->is_verified){
          return $next($request);
        }else{
          // return $next($request);
          return redirect()->route('verify.user.mobile');
        }
      }
      return $next($request);
    }
}
