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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Policies\UserPolicy;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{
    public function store(LoginRequest $request, AuthServices $authService)
    {

        // return response()->json(request()->query());

        // if (Gate::allows('login', $authService)) {
        //     return response()->json(['error' => 'Already authenticated.'], 403);
        // }
        // $user = $authService->getUser();
        // if (!$user) return response()->noContent(401);
        // return response()->json($user);
        // return response()->json($authService->getUser());


        // if (Gate::denies('login', $authService->getUser())) {
        //     return response()->json(['error' => 'Already authenticated.'], 403);
        // }

        // if ($authService->getUser())  $user = $authService->getUser();
        // return  response()->json($authService->getUser() ? false : true);

        // if (Gate::allows('create-token')) {
        //     return response()->json(['error' => 'Already authenticated.'], 403);
        // }



        // return response()->json('ok');
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

        // $token = $authService->getRequestToken();
        // $user = $userProvider->retrieveByToken([], $token);
        $user = $authService->getUser();
        User::where('email', $user->email)->update(['is_verified' => $request->value]);
        return response()->noContent();
    }

    public function me(AuthServices $authService)
    {
        $user = $authService->getUser();
        if (!$user) return response()->noContent(401);
        return response()->json($user);
    }
}
