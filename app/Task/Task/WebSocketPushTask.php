<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/10
 * Time: 15:27
 */

namespace App\Task\Task;

use App\Common\Common;
use App\Model\Logic\GroupLogic;
use App\Model\Logic\UserLogic;
use Swoft\Log\Helper\CLog;
use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;
use function server;

/**
 * Class WebSocketPushTask
 * @Task(name="wpush")
 * @package App\Task\Task
 */
class WebSocketPushTask
{
    /**
     * @TaskMapping("gpush")
     * @param int $gid
     * @param int $uid
     * @return mixed
     */
    public function GroupSend(int $gid,int $uid)
    {
        try{
            $groupUser = GroupLogic::getUserList($gid);
            foreach ($groupUser as $item) {
                if($item['id'] == $uid){
                    continue;
                }
                $userInfo = UserLogic::getInfoById($item['id']);
                $fd = Common::getFd($item['id']);
                $reData = [
                    "type" => 'joinGroup',
                    "data"  => [
                        "system"    => true,
                        "id"        => $gid,
                        "type"      => "group",
                        "content"   => $userInfo['nickname']."加入了群聊，赶紧索要果照吧～"
                    ]
                ];
                server()->push(intval($fd),json_encode($reData));

            }
        }catch (\Throwable $exception){
            CLog::info($exception->getMessage());
        }

    }

    /**
     * @TaskMapping("groupMsg")
     * @param $gid
     * @param $uid
     * @param $data
     * @throws \Exception
     */
    public static function groupMsg($gid,$uid,$data)
    {
        try {
            $groupUser = GroupLogic::getUserList($gid);
            foreach ($groupUser as $item) {
                if($item['id'] == $uid){
                    continue;
                }
                $fd = Common::getFd($item['id']);
                server()->push(intval($fd),json_encode($data));

            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }
}