<?php
/**
 * Created by PhpStorm.
 * User: Dex
 * Date: 006 11.6
 * Time: 下午 22:48
 */

namespace Decent\Wechat\Providers\Department;

use Decent\Wechat\Providers\Base;

class Department extends Base
{
    const API_CREATE_DEPARTMENT = "https://qyapi.weixin.qq.com/cgi-bin/department/create";
    const API_UPDATE_DEPARTMENT = "https://qyapi.weixin.qq.com/cgi-bin/department/update";
    const API_DELETE_DEPARTMENT = "https://qyapi.weixin.qq.com/cgi-bin/department/delete";
    const API_GET_DEPARTMENT_LIST = "https://qyapi.weixin.qq.com/cgi-bin/department/list";

    /**
     * 创建部门
     */
    public function create($name, $parentid, $order, $id) {
        return $this->http->parseJSON('json', [self::API_CREATE_DEPARTMENT,
            'name' => $name,
            'parentid' => $parentid,
            'order' => $order,
            'id' => $id,
        ]);
    }

    /**
     * 更新部门
     */
    public function update($name, $parentid, $order, $id) {
        return $this->http->parseJSON('json', [self::API_UPDATE_DEPARTMENT,
            'name' => $name,
            'parentid' => $parentid,
            'order' => $order,
            'id' => $id,
        ]);
    }

    /**
     * 删除部门
     */
    public function delete($departmentid) {
        return $this->http->parseJSON('get', [self::API_DELETE_DEPARTMENT,
            'id' => $departmentid,
        ]);
    }


    /**
     * 获取部门列表
    */
    public function get() {
        return $this->http->parseJSON('get', [self::API_GET_DEPARTMENT_LIST]);
    }

}