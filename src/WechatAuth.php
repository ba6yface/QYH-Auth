<?php

namespace Decent\Wechat;

use Decent\Wechat\Contacts\UserInterface;
use Decent\Wechat\Contacts\WechatInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WechatAuth
{
    /**
     * 用户提供器
    */
    protected $user;

    /**
     * 用户会话管理
     */
    protected $session;
    
    /**
     * 微信认证实现
    */
    protected $wechat;

    /**
     * 企业号成员唯一id
    */
    protected $userid;

    public function __construct(UserInterface $user, SessionInterface $session, WechatInterface $wechat)
    {
        $this->user = $user;
        $this->session = $session;
        $this->wechat = $wechat;

        $this->userid = $this->getUserid();
    }

    /**
     * 获取用户
     * @param $userid 企业号用户唯一ID
    */
    public function user($userid = null)
    {
        $id = empty($userid) ? $this->userid : $userid;

        return $this->user->getUserByUserid($id);
    }

    /**
     * 获取用户id
     */
    public function getUserid()
    {
        if (! is_null($this->userid)) {
            return $this->userid;
        }

        return $this->userid = $this->session->getUserIdFromSession();
    }

    /**
     * 尝试使用给出的参数认证一个用户
     *
     * @param $code 用户授权后重定向回来带的code
     * @return bool
     */
    public function attempt($code)
    {
        $userid = $this->wechat->getUserinfoByCode($code);

        if (empty($userid)) {
            return false;
        }

        $this->updateUserid($userid);

        return true;
    }

    /**
     * 更新用户id
    */
    protected function updateUserid($userid)
    {
        $this->userid = $userid;
        $this->updateSession($userid);
    }

    /**
     * 用户登出
    */
    public function logout()
    {
        $this->session->remove();
        $this->userid = null;
    }

    /**
     * 判断当前用户是否是游客
    */
    public function guest()
    {
        if (!is_null($this->userid)) {
            $this->auth->setUser($this->user());
            return false;
        }
        return true;
    }

    /**
     * 重定向至微信扫码登录
    */
    public function redirectToLogin()
    {
        $params = [
            'corp_id' => config('wechat.corp_id'),
            'redirect_uri' => config('wechat.auth_action'),
            'usertype' => 'member',
        ];

        return redirect()->guest($this->wechat->getLoginUrl().'?'.http_build_query($params));
    }
}