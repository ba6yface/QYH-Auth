<?php

namespace Decent\Wechat\Providers\Auth;

use Decent\Wechat\Providers\Base;
use Illuminate\Contracts\Auth\UserProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Str;
use App\User;

class WechatAuthAdapter extends Base
{
    const API_GET_LOGIN_INFO = "https://qyapi.weixin.qq.com/cgi-bin/service/get_login_info";

    protected $userid;
    protected $session;
    protected $cookie;
    protected $request;
//    protected $provider;
    protected $loggedOut;

    public function __construct(SessionInterface $session,
                                  Request $request)
    {
        parent::__construct();

        $this->session = $session;
        $this->request = $request;
//        $this->provider = $provider;
        $this->userid = $this->getUserid();
    }

    /**
     * 用户同意授权登录后，使用code获取用户信息
     *
     * @param $code 用户授权后重定向回来带的code
     * @return array
     */
    public function getLoginInfo($code)
    {
        return $this->http->parseJSON('json', [
            self::API_GET_LOGIN_INFO, [
                'auth_code' => $code,
            ]
        ]);
    }

    public function user()
    {
        return User::where('qy_id', $this->getUserid())->first();
    }

    /**
     * 获取用户id
     */
    public function getUserid()
    {
        if (! is_null($this->userid)) {
            return $this->userid;
        }

        return $this->userid = $this->session->get($this->getName());
    }

    protected function updateSession($id)
    {
        $this->session->set($this->getName(), $id);

        $this->session->migrate(true);
    }

    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        return 'login_session_'.sha1(static::class);
    }


    /**
     * 尝试使用给出的参数认证一个用户
     *
     * @param $code 用户授权后重定向回来带的code
     * @return bool
     */
    public function attempt($code)
    {
        $res = $this->getLoginInfo($code);
        if (isset($res['user_info'])) {
            $this->userid = $res['user_info']['userid'];
            $this->updateSession($this->userid);
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $this->clearUserDataFromStorage();

        $this->userid = null;

        $this->loggedOut = true;
    }

    /**
     * Remove the user data from the session and cookies.
     *
     * @return void
     */
    protected function clearUserDataFromStorage()
    {
        $this->session->remove($this->getName());

//        if (! is_null($this->getRecaller())) {
//            $recaller = $this->getRecallerName();
//
//            $this->getCookieJar()->queue($this->getCookieJar()->forget($recaller));
//        }
    }

    public function guest()
    {
        return is_null($this->userid);
    }
}