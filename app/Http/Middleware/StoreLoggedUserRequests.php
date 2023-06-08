<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserRequestLog;
use App\Models\UserToken;
use App\Services\AuthServices;
use Closure;
use Illuminate\Http\Request;

class StoreLoggedUserRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->guard('token')->user();
        if ($user) {
            UserRequestLog::create([
                'user_id' => $user->id,
                'token_id' => UserToken::where('user_id', $user->id)->first()['id'],
                'request_method' => request()->method(),
                'request_params' => $this->convertParams(request()->query()),
            ]);
            $requestUser = User::find($user->id);
            $requestUser->requests_count = $requestUser->requests_count + 1;
            $requestUser->save();
        }
        return $next($request);
    }

    public static function convertParams($params)
    {
        $paramsArray = [];
        foreach ($params as $key => $value) {
            array_push($paramsArray, [$key => $value]);
        }
        return $paramsArray;
    }
}
