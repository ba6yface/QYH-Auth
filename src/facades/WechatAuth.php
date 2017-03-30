<?php
namespace Decent\Wechat\Facades;

use Illuminate\Support\Facades\Facade;

class WechatAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wechat.auth';
    }
}