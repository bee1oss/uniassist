<?php

namespace App\Service;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class TokenService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generateAccessToken(User $user):string
    {
        return $user->createToken('access_token')->plainTextToken;
    }

    public function issueRefreshToken(User $user,Request $req):string
    {
        $plainToken = Str::random(64);

        RefreshToken::create([
            'user_id'=>$user->id,
            'token_hash'=>hash('sha256',$plainToken),
            'expires_at'=>now()->addDays(14),
            'created_ip'=>$req->ip(),
            'user_agent'=>substr($req->userAgent(),0,255),
        ]);
        return $plainToken;
    }

    public function rotateToken(RefreshToken $oldToken,Request $req):string
    {
        $oldToken->update(['revoked_at' => now()]);

        return $this->issueRefreshToken($oldToken->user,$req);
    }
    
    public function responWithToken($acces,$refresh,$csrf_token)
    {
        return response()->json([
            'access_token'=>$acces,
            'token_type'=>'Bearer'
        ])->cookie(
            'refresh_token',
            $refresh,
            60*24*14,//14 days
            '/',//for all sites
            null,//which subdomain is valid
            false,//secure http
            true//js cannot read cookie
            )->cookie(
            'csrf_token',
            $csrf_token,
            60*24*14,//14 days
            '/',//for all sites
            null,//which subdomain is valid (null==current)
            false,//in prod true
            false,//js can read because double submit , cookie and header
            false,//raw always false
            'Strict'//when user come from another link dont send them this cookie
        );
    }
}
