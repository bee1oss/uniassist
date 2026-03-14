<?php

namespace App\Http\Controllers;

use App\Service\TokenService;
use Illuminate\Http\Request;
use Str;

class RefreshTokenController extends Controller
{
    protected $tokenService;
    public function __construct(TokenService $tokenService) {
        $this ->tokenService = $tokenService;
    }
    public function refreshToken(Request $req)
    {
        $stored = $req->attributes->get('refres_token_model');

        $newRefreshToken = $this->tokenService->rotateToken($stored,$req);
        $newAccessToken = $this->tokenService->generateAccessToken($stored->user);
        $newcsrfToken = Str::random(64);

        return $this->tokenService->responWithToken($newAccessToken,$newRefreshToken,$newcsrfToken);

    }
}
