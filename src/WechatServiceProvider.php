<?php

namespace Decent\Wechat;

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
        $this->publishes([
            __DIR__.'/config/wechat.php' => config_path('wechat.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerUserProvider();
        $this->registerSessionProvider();
        $this->registerWechatProvider();

        $this->registerWechatAuth();
    }

    protected function registerUserProvider()
    {
        $this->app->singleton('wechat.auth.user', function ($app) {
            return $app->make(config('wechat.providers.user'));
        });
    }

    /**
     * Register the bindings for the Storage provider.
     */
    protected function registerSessionProvider()
    {
        $this->app->singleton('wechat.auth.session', function ($app) {
            return $app->make(config('wechat.providers.session'));
        });
    }

    /**
     * Register the bindings for the Payload Factory.
     */
    protected function registerWechatProvider()
    {
        $this->app->singleton('wechat.auth.wechat', function ($app) {
            return $app->make(config('wechat.providers.wechat'));
        });
    }

    public function registerAuthProvider()
    {
        $this->app->singleton('wechat.auth', function ($app) {
            return new WechatAuth(
                $app['wechat.auth.user'],
                $app['wechat.auth.session'],
                $app['wechat.auth.wechat']
            );
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
            'wechat.auth',
        ];
    }
}
