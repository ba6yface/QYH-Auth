<?php

namespace Decent\Wechat;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class WechatBase
{
    const API_GET_ACCESSTOKEN = "https://qyapi.weixin.qq.com/cgi-bin/gettoken";

    protected $corp_id;
    protected $corp_secret;

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

    /**
     * 获取access_token
     */
    protected function setAccessToken()
    {
        $params = [
            "corpid" => $this->corp_id,
            "corpsecret" => $this->corp_secret,
        ];

        $token = $this->http->parseJSON('get', [
            self::API_GET_ACCESSTOKEN, $params,
        ]);

        $this->access_token = isset($token['access_token']) ? $token['access_token'] : null;
    }

    /**
     * 在每个请求中使用URL编码带上参数access_token
    */
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
