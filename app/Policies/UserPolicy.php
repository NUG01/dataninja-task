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


    public function create(User $user, AuthServices $authService): bool
    {

        return  false;
        return  $authService->getUser() ? false : true;
    }
}
