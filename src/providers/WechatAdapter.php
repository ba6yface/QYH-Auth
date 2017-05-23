<?php
namespace Decent\Wechat\Providers;

use Decent\Wechat\WechatBase;
use Decent\Wechat\Contacts\WechatInterface;

class WechatAdapter extends WechatBase implements WechatInterface
{
    const API_GET_LOGIN_INFO = "https://qyapi.weixin.qq.com/cgi-bin/service/get_login_info";
    const URL_USER_LOGIN = "https://qy.weixin.qq.com/cgi-bin/loginpage";

    public function getUserinfoByCode($code)
    {
        $res = $this->http->parseJSON('json', [
            self::API_GET_LOGIN_INFO, [
                'auth_code' => $code,
            ]
        ]);

        return empty($res['user_info']['userid']) ? config('wechat.backend_debug') ? $code : null : $res['user_info']['userid'];
    }

    public function getLoginUrl()
    {
        return self::URL_USER_LOGIN;
    }
}