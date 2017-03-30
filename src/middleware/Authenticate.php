<?php

namespace Decent\Wechat\Middleware;

use Closure;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if (app('wechat.auth')->guest()) {
            return app('wechat.auth')->redirectToLogin();
        }

        return $next($request);
    }
}