<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/6
 * Time: 09:31
 */

namespace App\Model\Logic;


use App\Exception\DatabaseException;
use App\Model\Dao\FriendDao;

class FriendLogic
{
    /**
     * 获取某用户的好友列表
     * @param int $uid
     * @return array
     * @throws DatabaseException
     */
    public static function getFriendsList(int $uid){
        try{
            if($uid <= 0){
                throw new DatabaseException("参数错误");
            }
            $friends = [];
            $list = FriendDao::getList(['uid'=>$uid]);
            foreach($list as $item){
                $friends[] = UserLogic::getInfoById($item['fuid'],['id','username','avatar','sign']);
            }
            return $friends;
        }catch (\Throwable $e){
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 添加好友
     * @param array $friend
     * @return string
     * @throws DatabaseException
     */
    public static function addFriend(array $friend):string
    {
        if(empty($friend)){
            throw new DatabaseException('参数错误');
        }
        try{
            $friend = self::_checkData($friend);
            return FriendDao::addFriend($friend);
        }catch (\Throwable $e){
            throw new DatabaseException($e->getMessage());
        }
    }


    /**
     * 数据校验
     * @param array $data
     * @return array
     * @throws DatabaseException
     */
    protected static function _checkData(array $data):array {
        $uid = isset($data['uid'])? intval($data['uid']) :'';
        $fuid = isset($data['fuid'])? intval($data['fuid']) :'';
        $gid = isset($data['gid'])? intval($data['gid']) :1;
        if ($uid <= 0 || $fuid <= 0 || $gid <= 0){
            throw new DatabaseException('参数错误');
        }
        try{
            if($gid > 1){
                $ginfo = UserGroupLogic::getInfoById($gid);
                if(empty($ginfo))
                    throw new DatabaseException("该分组不存在");
            }
            $fuser = UserLogic::getInfoById($fuid);
            if(empty($fuser)){
                throw new DatabaseException("该好友不存在");
            }

            return [
                'uid' => $uid,
                'fuid' => $fuid,
                'gid' => $gid,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }catch (\Throwable $e){
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 获取群组用户
     * @param int $uid
     * @return array
     * @throws DatabaseException
     */
    public static function getGroupUser(int $uid):array{
        if ($uid <= 0){
            throw new DatabaseException('参数错误');
        }
        try{
            return FriendDao::getGroupUser($uid);
        }catch (\Throwable $e){
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 检测是否为好友关系
     * @param int $uid
     * @param int $fuid
     * @return bool
     * @throws DatabaseException
     */
    public static function isFriend(int $uid,int $fuid):bool
    {

        try {
            $finfo = UserLogic::getInfoById($fuid);
            if(empty($finfo)){
                throw new DatabaseException('添加用户不存在');
            }
            return FriendDao::isFriend($uid,$fuid);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }
}