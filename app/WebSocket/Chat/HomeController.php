<?php declare(strict_types=1);

namespace App\WebSocket\Chat;

use App\Common\Common;
use App\Model\Logic\FriendLogic;
use App\Model\Logic\MsgLogic;
use App\Model\Logic\SystemMessageLogic;
use App\Model\Logic\UserGroupLogic;
use App\Model\Logic\UserLogic;
use Swoft\Log\Helper\CLog;
use Swoft\Task\Task;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use function server;

/**
 * Class HomeController
 *
 * @WsController()
 */
class HomeController
{

    /**
     * @MessageMapping("addFriend")
     * @param string $data
     * @throws \Exception
     */
    public function friendApply($data): void
    {
        $data = json_decode($data, true);
        $uid = intval($data['to_user_id']);
        $gid = $data['to_friend_group_id'] ?? '';
        $remark = $data['remark'] ?? '';
        $created_at = date('Y-m-d H:i:s');
        $token = $data['token'];
        $userInfo = UserLogic::getInfo($token);
        $from_id = $userInfo['id'];
        $fd = Common::getFd($userInfo['id']);
        try {
            if ($userInfo['id'] == $uid) {
                $reData = [
                    'type' => 'layer',
                    'msg' => '不能添加自己为好友'
                ];
                server()->push($fd, json_encode($reData));
                return;
            }
            $isFriend = FriendLogic::isFriend($userInfo['id'], $uid);
            if ($isFriend) {
                $reData = [
                    'type' => 'layer',
                    'msg' => '你们已经是好友关系'
                ];
                server()->push($fd, json_encode($reData));
                return;
            }
            $fd = Common::getFd($uid);
            !SystemMessageLogic::add(compact('uid', 'gid', 'remark',
                'created_at', 'from_id')) && SystemMessageLogic::add(compact('uid', 'gid', 'remark', 'created_at',
                'from_id'));
            $reData = [
                'type' => 'msgBox',
                'count' => SystemMessageLogic::getUnReadCount($uid)
            ];
            if ($fd) {
                server()->push($fd, json_encode($reData));
            }
        } catch (\Throwable $e) {
            $reData = [
                'type' => 'layer',
                'msg' => $e->getMessage()
            ];
            $fd = Common::getFd($userInfo['id']);
            if ($fd) {
                server()->push($fd, json_encode($reData));
            }
        }

    }

    /**
     * @param string $data
     * @MessageMapping("refuseFriend")
     * @throws \Exception
     */
    public static function refuseFriend($data): void
    {
        try {
            $data = json_decode($data, true);
            $id = intval($data['id']);
            $info = SystemMessageLogic::getInfoById($id);
            $token = $data['token'];
            $userInfo = UserLogic::getInfo($token);
            if (empty($info)) {
                $reData = ['type' => 'layer', 'msg' => '不存在'];
                $fd = Common::getFd($userInfo['id']);
            } else {
                $fuid = $info['uid'];
                $fd = Common::getFd($fuid);
                $reData = [
                    'type' => 'msgBox',
                    'msg' => '你的请求已被"' . $userInfo['nickname'] . '"拒绝'
                ];
            }
            if ($fd) {
                server()->push($fd, json_encode($reData));
            }
        } catch (\Throwable $e) {
            $reData = [
                'type' => 'layer',
                'msg' => $e->getMessage()
            ];
            if ($fd) {
                server()->push($fd, json_encode($reData));
            }
        }

    }


    /**
     * @param string $data
     * @MessageMapping("addList")
     * @throws \Exception
     */
    public static function addList($data): void
    {
        try {
            $data = json_decode($data, true);
            $id = intval($data['id']);
            $gid = intval($data['gid']);
            $userInfo = UserLogic::getInfo($data['token']);
            $fd = Common::getFd($id);
            $reData = [
                'type' => 'addList',
                'data' => [
                    "type" => "friend",
                    "avatar" => $userInfo['avatar'],
                    "username" => $userInfo['nickname'],
                    "groupid" => $gid,
                    "id" => $userInfo['id'],
                    "sign" => $userInfo['sign']
                ]
            ];
            $unReadCount = SystemMessageLogic::getUnReadCount($id);
            CLog::info(json_encode($reData));
            if($fd){
                server()->push($fd,json_encode($reData));
                server()->push($fd,json_encode([
                    'type' => 'msgBox',
                    'count' => $unReadCount
                ]));
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }


    /**
     * @MessageMapping("joinGroup")
     * @param $data
     * @throws \Exception
     */
    public function joinGroup($data): void
    {
        try {
            $data = json_decode($data, true);
            $gid = intval($data['gid']);
            $userInfo = UserLogic::getInfo($data['token']);
            $groupInfo = UserGroupLogic::getInfoById($gid);
            $fd = Common::getFd($userInfo['id']);
            if (empty($groupInfo)) {
                $reData = [
                    'type' => 'layer',
                    'msg' => '参数错误'
                ];
                server()->push($fd, json_encode($reData));
                return;
            }
            if ($gid == $groupInfo['uid']) {
                $reData = [
                    'type' => 'layer',
                    'msg' => '你已经是群主了哦……^_^'
                ];
                server()->push($fd, json_encode($reData));
                return;
            }
            CLog::info('gid=====' . $gid);
            //异步任务处理
            Task::async('wpush', 'gpush', [$gid, $userInfo['id']]);

        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * @MessageMapping("message")
     * @param $data
     * @throws \Exception
     */
    public static function message($data): void
    {
        try {
            $data = json_decode($data, true);
            $userInfo = UserLogic::getInfo($data['token']);
            $type = $data['to']['type'];
            if ($type == 'friend') {
                $redata = [
                    'username' => $data['mine']['username'],
                    'avatar' => $data['mine']['avatar'],
                    'id' => $data['mine']['id'],
                    'type' => $data['to']['type'],
                    'content' => $data['mine']['content'],
                    'cid' => 0,
                    'mine' => $userInfo['id'] == $data['to']['id'] ? true : false,//要通过判断是否是我自己发的
                    'fromid' => $data['mine']['id'],
                    'timestamp' => time() * 1000
                ];
                if ($userInfo['id'] == $data['to']['id']) {
                    return;
                }
                $fd = Common::getFd($data['to']['id']);
                if($fd){
                    server()->push($fd,json_encode($redata));
                }else{

                }
                //记录聊天记录
                $insertData = [
                    'uid' => $data['mine']['id'],
                    'fuid' => $data['to']['id'],
                    'content' => $data['mine']['content'],
                    'created_at' => date("Y-m-d H:i:s")
                ];
                !MsgLogic::insertData($insertData) && MsgLogic::insertData($insertData);
            } else {
                //群消息
                $redata = [
                    'username' => $data['mine']['username'],
                    'avatar' => $data['mine']['avatar'],
                    'id' => $data['to']['id'],
                    'type' => $data['to']['type'],
                    'content' => $data['mine']['content'],
                    'cid' => 0,
                    'mine' => $userInfo['id'] == $data['to']['id'],//要通过判断是否是我自己发的
                    'fromid' => $data['mine']['id'],
                    'timestamp' => time() * 1000
                ];
                if ($userInfo['id'] == $data['to']['id']) {
                    return;
                }
                $insertData = [
                    'uid' => $data['mine']['id'],
                    'gid' => $data['to']['id'],
                    'content' => $data['mine']['content'],
                    'created_at' => date("Y-m-d H:i:s")
                ];
                Task::async('wpush', 'groupMsg', [$data['to']['id'], $userInfo['id'], $redata]);
                !MsgLogic::insertData($insertData) && MsgLogic::insertData($insertData);

            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }
}
