<?php

namespace App\Auth;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class TokenUserProvider implements UserProvider
{
  public function retrieveById($identifier)
  {
    // Retrieve a user by their unique identifier (ID)
  }

  public function retrieveByToken($identifier, $token)
  {
    $user = null;
    $token = UserToken::where('access_token', $token)->first();
    if ($token)  $user = $token->user;
    return $user;
  }

  public function register($request)
  {

    $user = User::create([
      'name'              => $request->name,
      'email'             => $request->email,
      'password'          => bcrypt($request->password),
    ]);

    return $user;
  }
  public function updateRememberToken(Authenticatable $user, $token)
  {
    // Update the "remember me" token for the user
  }

  public function retrieveByCredentials(array $credentials)
  {
    // Retrieve a user by their login credentials (e.g., email, password)
  }

  public function validateCredentials(Authenticatable $user, array $credentials)
  {
    // Validate a user's login credentials (e.g., check if password matches)
  }


  public function isDeferred()
  {
    return false;
  }
}
