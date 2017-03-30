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
        $this->registerAuthProvider();
    }

    public function registerAuthProvider()
    {
        $this->app->singleton('Wechat.Auth', function ($app) {
            return $this->app->make(config('wechat.providers.auth'));
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
        ];
    }
}
