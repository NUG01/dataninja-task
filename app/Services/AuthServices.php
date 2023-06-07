<?php

namespace App\Services;

use App\Models\UserToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AuthServices
{
  public static function createAccessToken()
  {
    $token = UserToken::create([
      'user_id' => auth()->user()->id,
      'access_token' => Str::random(32),
      'expires_at' => now()->subDays(30),
    ]);
    return $token;
  }


  public static function destroyAccessToken($request)
  {
    UserToken::where('access_token', $request->token)->delete();
    return;
  }


  public static function getAccessToken()
  {
    $token = UserToken::where('user_id',  auth()->user()->id)->first();
    return $token;
  }


  public static function getRequestToken()
  {
    $bearerToken = Str::after(request()->header('Authorization'), 'Bearer ');
    $token = request()->has('access_token') ? request('access_token') : $bearerToken;

    return $token;
  }
}
