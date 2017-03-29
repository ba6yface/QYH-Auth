<?php
/**
 * Created by PhpStorm.
 * User: Dex
 * Date: 006 11.6
 * Time: 下午 22:48
 */

namespace App\Model\Weixin;

class Chat extends Weixin
{
    const API_GET_CHAT = "https://qyapi.weixin.qq.com/cgi-bin/chat/get";
    const API_CREATE_CHAT = "https://qyapi.weixin.qq.com/cgi-bin/chat/create";
    const API_UPDATE_CHAT = "https://qyapi.weixin.qq.com/cgi-bin/chat/update";
    const API_QUIT_CHAT = "https://qyapi.weixin.qq.com/cgi-bin/chat/quit";
    const API_CLEAR_NOTIFY_CHAT = "https://qyapi.weixin.qq.com/cgi-bin/chat/clearnotify";
    const API_SEND_CHAT = "https://qyapi.weixin.qq.com/cgi-bin/chat/send";
    const API_SETMUTE_CHAT = "https://qyapi.weixin.qq.com/cgi-bin/chat/setmute";

    /**
     * 创建会话
     */
    public function create($name, $owner, $chatid, $userlist) {
        $res = $this->http->json(self::API_CREATE_CHAT, [
            'chatid' => $chatid,
            'name' => $name,
            'owner' => $owner,
            'userlist' => $userlist,
        ]);
        dd($res);
    }

    /**
     * 获取会话
     */
    public function get($chatid) {
        return $this->http->get(self::API_GET_CHAT, [
            'chatid' => $chatid,
        ]);
    }

    /**
     * 修改会话信息
     */
    public function update($chatid, $op_user, $name, $owner, $add_user_list, $del_user_list) {
        return $this->http->json(self::API_UPDATE_CHAT, [
            'chatid' => $chatid,
            'op_user' => $op_user,
            'name' => $name,
            'owner' => $owner,
            'add_user_list' => $add_user_list,
            'del_user_list' => $del_user_list,
        ]);
    }

    /**
     * 退出会话
     */
    public function delete($chatid, $op_user) {
        return $this->http->json(self::API_QUIT_CHAT, [
            'chatid' => $chatid,
            'op_user' => $op_user,
        ]);
    }

    /**
     * 清除会话未读状态
     */
    public function clearNotify($op_user, $chat) {
        return $this->http->json(self::API_CLEAR_NOTIFY_CHAT, [
            'op_user' => $op_user,
            'chat' => $chat,
        ]);
    }

    /**
     * 发送信息
     */
    public function send($message) {
        return $this->http->json(self::API_SEND_CHAT, [
            'message' => $message,
        ]);
    }

    /**
     * 设置成员新消息免打扰
     */
    public function setmute($user_mute_list) {
        return $this->http->json(self::API_SETMUTE_CHAT, [
            'user_mute_list' => $user_mute_list,
        ]);
    }
}