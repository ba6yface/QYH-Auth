<?php

namespace Decent\Wechat\Providers;

use Illuminate\Support\ServiceProvider;

class WechatServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAuthProvider();
//        $this->registerDepartmentProvider();
//        $this->registerMemberProvider();
    }

    public function registerAuthProvider()
    {
        $this->app->singleton('Wechat.Auth', function ($app) {
            return $this->app->make(config('wechat.providers.auth'));
        });
    }

    public function registerDepartmentProvider()
    {
        $this->app->singleton('Wechat.Department', function ($app) {
            return $this->app->make($app['config']['wechat']['providers']['department']);
        });
    }

    public function registerMemberProvider()
    {
        $this->app->singleton('Wechat.Member', function ($app) {
            return $this->app->make($app['config']['wechat']['providers']['member']);
        });
    }

    /**
     * 获取由提供者提供的服务.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Wechat.Auth',
//            'Wechat.Department',
//           'Wechat.Member',
        ];
    }
}
