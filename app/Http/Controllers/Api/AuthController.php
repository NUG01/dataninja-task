<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthServices;
use App\Auth\TokenGuard;
use App\Auth\TokenUserProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function store(LoginRequest $request, AuthServices $authService)
    {
        $credentials = (['email' => $request->email, 'password' => $request->password]);
        if (auth()->guard('token')->attempt($credentials)) {
            $authService->createAccessToken();
        } else {
            return response()->json('Verified user not found!', 401);
        }

        return response()->json(['token' => $authService->getAccessToken()->access_token]);
    }

    public function destroy(Request $request, AuthServices $authService)
    {
        $authService->destroyAccessToken($request);
        return response()->noContent();
    }


    public function register(RegisterUserRequest $request, TokenUserProvider $userProvider)
    {
        $registeredUser = $userProvider->register($request);
        return response()->json($registeredUser);
    }

    public function verify(Request $request, TokenUserProvider $userProvider,  AuthServices $authService)
    {

        $token = $authService->getRequestToken();
        $user = $userProvider->retrieveByToken([], $token);
        User::where('email', $user->email)->update(['is_verified' => $request->value]);
        return response()->noContent();
    }

    public function me(TokenUserProvider $userProvider, AuthServices $authService)
    {
        $token = $authService->getRequestToken();

        $user = $userProvider->retrieveByToken([], $token);
        if (!$user) return response()->noContent(401);
        return response()->json($user);
    }
}
