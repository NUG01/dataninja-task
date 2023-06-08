<?php

namespace App\Auth;

use App\Models\User;
use App\Services\AuthServices;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class TokenGuard implements Guard
{

  protected $authService;
  protected $request;

  public function __construct(UserProvider $userProvider, $request)
  {

    $this->userProvider = $userProvider;
    $this->request = $request;
  }

  public function login(Authenticatable $user, $remember = false)
  {
    //
  }

  public function attempt(array $credentials = [], $remember = false)
  {

    $user = User::where('email', $credentials['email'])
      ->where('is_verified', 1)
      ->first();

    if (!$user) return false;

    if (Hash::check($credentials['password'], $user->password)) {
      return $user;
    }
  }

  public function check()
  {
    // Check if a user is authenticated
  }

  public function guest()
  {
    // Check if a user is a guest (not authenticated)
  }

  public function user()
  {

    $authService = new AuthServices();
    $user = $authService->getUser();
    return $user;
    // Get the currently authenticated user
  }

  public function id()
  {
    // Get the ID of the currently authenticated user
  }

  public function validate(array $credentials = [])
  {
    // Validate a user's login credentials (e.g., check if password matches)
  }

  public function setUser(Authenticatable $user)
  {
    //
    // return auth()->login($user);
  }
  public function hasUser()
  {
    // Set the currently authenticated user
  }
}
