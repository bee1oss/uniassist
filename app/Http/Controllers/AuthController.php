<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Service\TokenService;
use Illuminate\Support\Facades\Hash;
use Str;

class AuthController extends Controller
{
    protected $tokenService;
    public function __construct(TokenService $tokenService) {
        $this->tokenService = $tokenService;
    }

    public function register(RegisterRequest $req)
    {
        $validated = $req->validated();

        $user = User::create($validated);

        return response()->json(
            [
                'message'=>'user registerred succesfully',
                'user'=>[
                    'name'=>$user->name,
                    'email'=>$user->email
                ]
            ,201]
        );
    }

    public function login(LoginRequest $req)
    {
        $validated = $req->validated();

        $user = User::where('email',$validated['email'])->first();
        
        if(!$user || !Hash::check($validated['password'], $user->password)){
            return response()->json([
                'message'=>'invalid email or password'
            ],401);
        }

        $newaccessToke = $this->tokenService->generateAccessToken($user);
        $newrefreshToken = $this->tokenService->issueRefreshToken($user,$req);
        //csrf double submit
        $newcsrfToken = Str::random(64);
        return $this->tokenService->responWithToken($newaccessToke,$newrefreshToken,$newcsrfToken);
        
    }
        
}
