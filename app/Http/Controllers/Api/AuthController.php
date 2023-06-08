<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthServices;
use App\Auth\TokenGuard;
use App\Auth\TokenUserProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Policies\UserPolicy;

class AuthController extends Controller
{
    public function store(LoginRequest $request, AuthServices $authService)
    {

        if (Gate::allows('verified-user')) {
            return response()->json(['error' => 'Not verified!'], 403);
        }

        $credentials = (['email' => $request->email, 'password' => $request->password]);
        if (auth()->guard('token')->attempt($credentials)) {
            $userId = User::where('email', $request->email)->first()['id'];
            $authService->createAccessToken($userId);
        } else {
            return response()->json('Verified user not found!', 401);
        }

        return response()->json(['token' => UserToken::where('user_id', $userId)->first()['access_token']]);
    }

    public function destroy(Request $request, AuthServices $authService)
    {
        if (Gate::allows('owner-user')) {
            return response()->json(['error' => 'Not an owner.'], 403);
        }

        $authService->destroyAccessToken();
        return response()->noContent();
    }


    public function register(RegisterUserRequest $request, TokenUserProvider $userProvider)
    {
        $registeredUser = $userProvider->register($request);
        return response()->json($registeredUser);
    }

    public function verify(Request $request, AuthServices $authService)
    {
        $user = UserToken::where('access_token', $authService->getRequestToken())->first()->user;
        $user->update(['is_verified' => $request->value]);

        return response()->noContent();
    }

    public function me()
    {
        $user = auth()->guard('token')->user();
        if (!$user) return response()->noContent(401);
        return response()->json($user);
    }
}
