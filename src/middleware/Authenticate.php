<?php

namespace Decent\Wechat\Middleware;

use Closure;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if (app('Wechat.Auth')->guest()) {
            return app('Wechat.Auth')->redirectToLogin();
        }

        return $next($request);
    }
}