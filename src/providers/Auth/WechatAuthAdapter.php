<?php

namespace Decent\Wechat\Providers\Auth;

use Decent\Wechat\Providers\Base;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\UserProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Str;
use App\User;

class WechatAuthAdapter extends Base
{
    const API_GET_LOGIN_INFO = "https://qyapi.weixin.qq.com/cgi-bin/service/get_login_info";

    protected $auth;
    protected $userid;  //微信id
    protected $session;
    protected $cookie;
    protected $request;
    protected $loggedOut;

    public function __construct(SessionInterface $session,
                                  Request $request,
                                  AuthManager $auth)
    {
        parent::__construct();

        $this->auth = $auth;
        $this->session = $session;
        $this->request = $request;
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
        $res = $this->http->parseJSON('json', [
            self::API_GET_LOGIN_INFO, [
                'auth_code' => $code,
            ]
        ]);

        if (config('wechat.backend_debug') && empty($res['user_info']['userid'])) {
            $res['user_info']['userid'] = $code;
            return $res;
        }

        return $res;
    }

    public function user()
    {
        $qy_id  = $this->userid;
        return User::where('qy_id', $qy_id)->first();
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
            $this->auth->setUser($this->user());
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
        if (!is_null($this->userid)) {
            $this->auth->setUser($this->user());
            return false;
        }
        return true;
    }
}