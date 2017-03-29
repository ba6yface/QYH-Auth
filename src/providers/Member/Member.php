<?php
/**
 * Created by PhpStorm.
 * User: Dex
 * Date: 006 11.6
 * Time: 下午 22:48
 */

namespace Decent\Wechat\Providers\Department;

use Decent\Wechat\Providers\Base;

class Member extends Base
{
    const API_GET_MEMBER = "https://qyapi.weixin.qq.com/cgi-bin/user/get";
    const API_GET_MEMEBER_LIST_SIMPLE = "https://qyapi.weixin.qq.com/cgi-bin/user/simplelist";
    const API_GET_MEMEBER_LIST_DETAIL = "https://qyapi.weixin.qq.com/cgi-bin/user/list";

    /**
     * 通过成员账号获取成员详细信息
    */
    public function getMemberInfo($userid) {
        return $this->http->get(self::API_GET_MEMBER, [
            'userid' => $userid,
        ]);
    }

    /**
     * 获取部门成员信息
     *
     * @param $isSimple 获取简要信息列表还是详细信息列表
     * @param $department_id 部门id
     * @param $fetch_child 是否递归获取子部门下面的成员
     * @param $status  	0获取全部成员，1获取已关注成员列表，2获取禁用成员列表，4获取未关注成员列表。status可叠加,未填写则默认为4
     */
    public function getMemberList($isSimple, $department_id, $fetch_child, $status = 0) {
        $url = $isSimple ? self::API_GET_MEMEBER_LIST_SIMPLE : self::API_GET_MEMEBER_LIST_DETAIL;
        return $this->http->get($url, [
            'department_id' => $department_id,
            'fetch_child' => $fetch_child,
            'status' => $status,
        ]);
    }
}