<?php

namespace App\Http\Middleware;

use App\Models\RefreshToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Double submit CSRF check
        $csrfTokenHeader = $request->header('X-CSRF-TOKEN');
        $csrfTokenCookie = $request->cookie('csrf_token');

        if(!$csrfTokenCookie || !$csrfTokenHeader || !hash_equals($csrfTokenCookie,$csrfTokenHeader)){
            return response()->json([
                'message'=>'Auth invalid'
            ],403);
        }

        //Cookie refresh token check
        $refreshTokenPlain = $request->cookie('refresh_token');
        if(!$refreshTokenPlain){
            return response()->json([
                'message'=>'Auth error'
            ],401);
        }

        $hash = hash('sha256',$refreshTokenPlain);
        $stored = RefreshToken::where('token_hash',$hash)->first();

        if(!$stored || $stored->revoked_at || $stored->expires_at->isPast()){
            return response()->json([
                'message'=>'Auth invalid'
            ],401);
        }

        //controller will get there
        $request->attributes->set('refres_token_model',$stored);

        return $next($request);
    }
}
