<?php

namespace App\Services;

use App\Auth\TokenUserProvider;
use App\Models\UserToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AuthServices
{

  public static function createAccessToken($userId)
  {
    $token = UserToken::create([
      'user_id' => $userId,
      'access_token' => Str::random(32),
      'expires_at' => now()->subDays(30),
    ]);
    return $token;
  }


  public static function destroyAccessToken()
  {
    UserToken::where('access_token', self::getRequestToken())->delete();
    return;
  }


  public static function getRequestToken()
  {
    $bearerToken = Str::after(request()->header('Authorization'), 'Bearer ');
    $token = request()->has('access_token') ? request('access_token') : $bearerToken;

    return $token;
  }

  public static function getUser()
  {

    $token = self::getRequestToken();
    $user = (new TokenUserProvider())->retrieveByToken([], $token);
    return $user;
  }
}
