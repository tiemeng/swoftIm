<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/5
 * Time: 18:18
 */

namespace App\Http\Controller;


use App\Common\Common;
use App\Model\Logic\FriendLogic;
use App\Model\Logic\GroupLogic;
use App\Model\Logic\SystemMessageLogic;
use App\Model\Logic\UserGroupLogic;
use App\Model\Logic\UserLogic;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class UserController
 * @Controller()
 * @Middleware(name="App\Http\Middleware\LoginMiddleware")
 * @package App\Http\Controller
 */
class UserController
{

    /**
     * 退出登录
     * @RequestMapping("/logout")
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \App\Exception\DatabaseException
     * @throws \Swoft\Exception\SwoftException
     */
    public function logout(Request $request)
    {

        $id = $request->userInfo['id'];
        if (UserLogic::logout($id)) {
            return Common::reJson();
        }
        return Common::reJson(202, '退出失败');


    }

    /**
     * 初始化数据
     * @RequestMapping("/getList")
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \App\Exception\DatabaseException
     * @throws \Swoft\Exception\SwoftException
     */
    public function getList(Request $request)
    {

        $mine = $request->userInfo;
        $group = GroupLogic::getUserGroup($mine['id']);
        $friend = FriendLogic::getGroupUser($mine['id']);
        $data = compact('mine', 'friend', 'group');
        var_dump($data);
        return Common::reJson(0, 'success', $data);

    }

    /**
     * 获取群成员
     * @RequestMapping("/getMembers")
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \App\Exception\DatabaseException
     * @throws \Swoft\Exception\SwoftException
     */
    public function getGroupUserList(Request $request)
    {

        $mine = $request->userInfo;
        $gid = intval($request->get('id'));
        if ($gid <= 0) {
            return Common::reJson(-1, '参数错误');
        }
        $members = GroupLogic::getUserList($gid);
        $data = ['owner' => $mine, 'list' => $members, 'members' => GroupLogic::getCount($gid)];
        var_dump($data);
        return Common::reJson(0, 'success', $data);

    }

    /**
     * 修改更新签名
     * @RequestMapping("/updateSign",method={"POST"})
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \App\Exception\DatabaseException
     * @throws \Swoft\Exception\SwoftException
     */
    public function updateSign(Request $request)
    {

        $token = trim($request->get('token'));
        $sign = $request->input('sign') ?? '';
        if (empty($sign)) {
            return Common::reJson(201, '参数错误');
        }
        if (UserLogic::updateSign($token, $sign)) {
            return Common::reJson(0, '更新成功');
        }
        return Common::reJson(201, '修改失败');

    }


    /**
     * 创建群组
     * @RequestMapping("/createGroup",method={"POST","GET"})
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \App\Exception\DatabaseException
     * @throws \Swoft\Exception\SwoftException
     * @throws \Throwable
     */
    public function createGroup(Request $request)
    {

        if ($request->isGet()) {
            $type = intval($request->get('type')) ?? 1;
            return view('find/create_group', ['type' => $type]);
        }
        $uid = $request->userInfo['id'];
        $name = trim($request->input('name')) ?? '';
        $type = intval($request->get('type')) ?? 1;
        $avatar = !empty($request->input('avatar')) ? $request->input('avatar') : 'http://tp2.sinaimg.cn/2211874245/180/40050524279/0';
        $insertData = [
            'name' => $name,
            'avatar' => $avatar,
            'type' => $type,
            'uid' => $uid
        ];

        if ($id = UserGroupLogic::create($insertData)) {
            if ($type == 1) {
                $resData = [
                    'type' => 'friend',
                    'avatar' => '',
                    'groupid' => $id,
                    'id' => '',
                    'username' => '',
                    'sign' => ''
                ];
            } else {
                $resData = [
                    'type' => 'group',
                    'avatar' => $avatar,
                    'groupname' => $name,
                    'id' => $id,
                ];
            }
            return Common::reJson(200, '创建成功', $resData);
        }
        return Common::reJson(201, '创建失败');


    }

    /**
     * 加入群组
     * @RequestMapping("/joinGroup",method={"POST"})
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \App\Exception\DatabaseException
     * @throws \Swoft\Exception\SwoftException
     */
    public function joinGroup(Request $request)
    {

        $gid = intval($request->input('gid'));
        $uid = $request->userInfo['id'];
        if (GroupLogic::isMember($gid, $uid)) {
            return Common::reJson(201, '别闹，你已经是群成员了');
        }
        $data = [
            'gid' => $gid,
            'uid' => $uid
        ];
        $reData = GroupLogic::joinGroup($data);
        if (!empty($reData)) {
            return Common::reJson(200, '申请成功', $reData);
        }
        return Common::reJson(201, '加入失败');


    }

    /**
     * 消息盒子
     * @RequestMapping("/message")
     * @param Request $request
     * @return \Swoft\Http\Message\Response
     * @throws \App\Exception\DatabaseException
     * @throws \Throwable
     */
    public function message(Request $request)
    {

        $uid = $request->userInfo['id'];
        $where = ['uid' => $uid];
        $list = SystemMessageLogic::getList($where);
        return view('home/message_box', ['list' => $list]);


    }

    /**
     * 添加好友
     * @RequestMapping("/addFriend")
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \App\Exception\DatabaseException
     * @throws \Swoft\Exception\SwoftException
     */
    public function addFriend(Request $request)
    {

        $uid = $request->userInfo['id'];
        $id = intval($request->input('id'));
        $gid = intval($request->input('gid'));
        $msgInfo = SystemMessageLogic::getInfoById($id);
        if (empty($msgInfo)) {
            return Common::reJson(201, '该申请记录不存在');
        }
        if (FriendLogic::isFriend($uid, $msgInfo['fromId'])) {
            return Common::reJson(201, '该申请已处理');
        }
        $data = ['uid' => $uid, 'fuid' => $msgInfo['fromId'], 'gid' => $gid];
        if (FriendLogic::addFriend($data)) {
            $fuserInfo = UserLogic::getInfoById($msgInfo['fromId']);
            !FriendLogic::addFriend([
                'uid' => $msgInfo['fromId'],
                'fuid' => $uid,
                'gid' => $msgInfo['gid']
            ]) && FriendLogic::addFriend([
                'uid' => $msgInfo['fromId'],
                'fuid' => $uid,
                'gid' => $msgInfo['gid']
            ]);
            $reData = [
                'type' => 'friend',
                'avatar' => $fuserInfo['avatar'],
                'username' => $fuserInfo['username'],
                'groupid' => $gid,
                'sign' => $fuserInfo['sign']
            ];
            SystemMessageLogic::updateData(['id' => $id], ['status' => 1, 'read' => 1]);
            SystemMessageLogic::add([
                'uid' => $msgInfo['fromId'],
                'from_id' => $msgInfo['uid'],
                'type' => 1,
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s")
            ]);
            return Common::reJson(200, '添加成功', $reData);
        }
        return Common::reJson(201, '添加失败');


    }

    /**
     * 拒绝申请
     * @RequestMapping("/refuse")
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \App\Exception\DatabaseException
     * @throws \Swoft\Exception\SwoftException
     */
    public function refuseFriend(Request $request)
    {

        $id = $request->input('id');
        $where = ['id' => $id];
        $updateValue = ['status' => 2, 'read' => 1];
        $info = SystemMessageLogic::getInfoById(intval($id));
        if (empty($info)) {
            return Common::reJson(201, '该记录不存在');
        }
        if (SystemMessageLogic::updateData($where, $updateValue)) {
            $data = [
                'from_id' => $request->userInfo['id'],
                'type' => 1,
                'uid' => $info['fromId'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            SystemMessageLogic::add($data);
            return Common::reJson(200, '操作成功');
        }
        return Common::reJson(201, '操作失败');


    }

    /**
     * @RequestMapping("/setRead")
     * @param Request $request
     * @throws \Exception
     */
    public function setRead(Request $request)
    {
        $uid = $request->userInfo['id'];
        SystemMessageLogic::updateData(['uid' => $uid], ['read' => 1]);
    }


}