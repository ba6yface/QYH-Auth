<?php

namespace Decent\Wechat\Contacts;

interface SessionInterface
{
    /**
     * 更新用户session
     * @param $id 用户id
    */
    public function updateUserSession($id);

    /**
     * 从session中获取用户id
    */
    public function getUserIdFromSession();
}