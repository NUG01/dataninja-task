<?php

namespace App\Policies;

use App\Auth\TokenUserProvider;
use App\Models\User;
use App\Models\UserToken;
use App\Services\AuthServices;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */


    public function create(): bool
    {
        $user = User::where('email', request()->input('email'))->first();
        return $user->is_verified == 1;
    }

    public function delete(AuthServices $authService): bool
    {
        $token = UserToken::where('access_token', $authService->getRequestToken())->first();
        return auth()->guard('token')->user()->id == $token->user->id ? true : false;
    }
}
