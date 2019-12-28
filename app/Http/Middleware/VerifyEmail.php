<?php

namespace App\Http\Middleware;

use App\Enums\ResponseCodeEnum;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                ! $request->user()->hasVerifiedEmail())) {
            return response()->json([
                'success' => false,
                'message' => __('auth.email.not.verify')
            ], ResponseCodeEnum::Success);
        }

        return $next($request);
    }
}
