<?php
namespace Decent\Wechat\Providers;

use Decent\Wechat\Contacts\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionAdapter implements SessionInterface
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getUserIdFromSession()
    {
        return $this->session->get($this->getName());
    }

    public function updateUserSession($id)
    {
        $this->session->set($this->getName(), $id);
        $this->session->migrate(true);
    }

    public function removeUserSession()
    {
        $this->session->remove($this->getName());
    }

    /**
     * 为当前会话生成唯一键
     *
     * @return string
     */
    public function getName()
    {
        return 'login_session_'.sha1(static::class);
    }
}