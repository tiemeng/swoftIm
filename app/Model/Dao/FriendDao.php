<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/6
 * Time: 09:31
 */

namespace App\Model\Dao;


use App\Model\Entity\Friends;

class FriendDao
{
    /**
     * 获取列表
     * @param array $where
     * @return array
     * @throws \Exception
     */
    public static function getList(array $where=[]):array{
        try{
            return Friends::where($where)->get()->toArray();
        }catch (\Throwable $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 添加好友
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public static function addFriend(array $data):string
    {
        try{
            return Friends::insertGetId($data);
        }catch (\Throwable $e){
            throw new \Exception($e->getMessage());
        }
    }

    public static function getUserFriendsByGroup(int $gid,int $uid):array{
        if($gid <=0 || $uid <= 0){
            throw new \Exception('参数错误');
        }
        try{
            $friends = Friends::where(['gid'=>$gid,'uid'=>$uid])->leftJoin('users','users.id','=','friends.fuid')->select("users.id",'nickname as username','sign','avatar','alais')->get()->toArray();
            foreach ($friends as &$friend){
                $friend['username'] = empty($friend['alais']) ? $friend['username'] : $friend['alais'];
            }
            return $friends;

        }catch (\Throwable $e){
            throw new \Exception($e->getMessage());
        }
    }

    public static function getGroupUser(int $uid){
        try{
            $groups = UserGroupDao::getUserGroupByType($uid,1);
            $friends = [];
            $i=0;
            foreach ($groups as $item){
                $friends[$i]['groupname'] = $item['name'];
                $friends[$i]['id'] = $item['id'];
                $friends[$i]['online'] = UserDao::getOnlineCount($uid);
                $friends[$i]['list'] = self::getUserFriendsByGroup($item['id'],$uid);
                $i++;
            }
            return $friends;
        }catch (\Throwable $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 检测是否为好友关系
     * @param int $uid
     * @param int $fuid
     * @return bool
     * @throws \Exception
     */
    public static function isFriend(int $uid,int $fuid):bool
    {
        try {
            $info = Friends::where(['uid'=>$uid,'fuid'=>$fuid])->count();
            return $info > 0;
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }
}