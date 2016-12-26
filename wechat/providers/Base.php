<?php

namespace Decent\Wechat\Providers;

use Decent\Util\Http;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class Base
{
    protected $corp_id;
    protected $corp_secret;

    const API_GET_USERINFO_BY_CODE = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo";
    const API_GET_ACCESSTOKEN = "https://qyapi.weixin.qq.com/cgi-bin/gettoken";

    protected $access_token;
    protected $http;

    public function __construct()
    {
        $this->corp_id = config('wechat.corp_id');
        $this->corp_secret = config('wechat.corp_secret');
        $this->http = new Http();
        $this->setAccessToken();
        $this->http->addMiddleware($this->accessTokenMiddleware());
    }
    
    public function getUserinfoByCode($code) {
        return $this->http->parseJSON('get', [
            self::API_GET_USERINFO_BY_CODE, [
                'code' => $code,
            ],

        ]);
    }

    /**
     * 获取access_token
     */
    protected function setAccessToken()
    {
        $token = $this->http->parseJSON('get', [
            self::API_GET_ACCESSTOKEN, [
                "corpid" => $this->corp_id,
                "corpsecret" => $this->corp_secret,
            ],
        ]);

        if (isset($token['access_token'])) {
            $this->access_token = $token['access_token'];
        } else {
            $this->access_token = null;
        }
    }

    protected function accessTokenMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if (!$this->access_token) {
                    return $handler($request, $options);
                }

                $request = $request->withUri(Uri::withQueryValue($request->getUri(), 'access_token', $this->access_token));

                return $handler($request, $options);
            };
        };
    }



}
