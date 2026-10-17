<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use App\Model\{Astrologer};

class CheckAstrologerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      // Check if this is an API request (has api_token parameter) or web request (has CSRF token)
      $apiToken = $request->get('api_token') ?: $request->header('X-API-TOKEN');
      $csrfToken = $request->get('_token'); // CSRF token for web requests

      $astrologer = auth()->guard('astrologer')->user();

      // If API token is provided, authenticate via API token
      if($apiToken && !$astrologer){
        $astrologer = Astrologer::where('api_token', $apiToken)->first();
        if($astrologer){
          session(['app_login' => true]);
          Auth::guard('astrologer')->login($astrologer);
        }else{
          return response()->json(['message'=>'not found'], 404);
        }
      }
      // If API token provided and doesn't match current user's token
      elseif($apiToken && $astrologer && $apiToken != $astrologer->api_token) {
        Auth::guard('astrologer')->logout();
        $astrologer = Astrologer::where('api_token', $apiToken)->first();
        if($astrologer){
          session(['app_login' => true]);
          Auth::guard('astrologer')->login($astrologer);
        }else{
          return response()->json(['message'=>'not found'], 404);
        }
      }
      // If no astrologer authenticated and no API token, redirect to login (web request)
      elseif(!$astrologer && !$apiToken){
        // For AJAX requests, return 401, otherwise redirect
        if($request->ajax() || $request->wantsJson()){
          return response()->json(['message'=>'Unauthorized'], 401);
        }
        return redirect()->route('astrologer.login');
      }

      // Unverified astrologers (application not approved) may only see the pending page or logout
      if ($astrologer && !$astrologer->is_verified) {
          if (!$request->routeIs('astrologer.pending') && !$request->routeIs('astrologer.logout')) {
              return redirect()->route('astrologer.pending');
          }
      }

      return $next($request);
    }
}




