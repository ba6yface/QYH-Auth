<?php

namespace Decent\Wechat\Contacts;

interface UserInterface
{
    /**
     * 使用企业号成员唯一id获取用户
     * @param $userid 企业号成员唯一ID
    */
    public function getUserByUserid($userid);
}