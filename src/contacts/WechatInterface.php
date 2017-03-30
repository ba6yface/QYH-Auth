<?php

namespace Decent\Wechat\Contacts;

interface WechatInterface
{
    public function getLoginUrl();

    public function getUserinfoByCode($code);
}