<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/6
 * Time: 09:56
 */

namespace App\Model\Logic;


use App\Exception\DatabaseException;
use App\Model\Dao\GroupDao;
use App\Model\Dao\UserGroupDao;

class GroupLogic
{
    /**
     * 获取群成员
     * @param int $gid
     * @return array
     * @throws DatabaseException
     */
    public static function getUserList(int $gid):array
    {
        try{
            return GroupDao::getUserList($gid);
        }catch (\Throwable $e){
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 获取群成员数
     * @param int $gid
     * @return int
     * @throws DatabaseException
     */
    public static function getCount(int $gid):int
    {
        try{
            return GroupDao::getCount($gid);
        }catch (\Throwable $e){
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 获取群列表
     * @param array $where
     * @param array $fields
     * @return array
     * @throws DatabaseException
     */
    public static function getGroupList(array $where,array $fields = ['*']):array
    {
        try {
            return GroupDao::getGroupList($where,$fields);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 加入群组
     * @param array $data
     * @return array
     * @throws DatabaseException
     */
    public static function joinGroup(array $data):array
    {
        try {
            $gid = intval($data['gid']);
            $groupInfo = UserGroupDao::getInfoById($gid);
            if(empty($groupInfo)){
                throw new DatabaseException('该群组不存在');
            }
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = intval(GroupDao::insertData($data));
            if($id > 0){
                return [
                    'type' => 'group',
                    'avatar' => $groupInfo['avatar'],
                    'groupname' => $groupInfo['name'],
                    'id' => $gid
                ];
            }
            return [];
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }

    /**
     * 是否为群成员
     * @param int $gid
     * @param int $uid
     * @return bool
     * @throws DatabaseException
     */
    public static function isMember(int $gid,int $uid):bool
    {
        try {
            return GroupDao::isMember($gid,$uid);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }

    /**
     * 获取用户群组
     * @param int $uid
     * @return array
     * @throws DatabaseException
     */
    public static function getUserGroup(int $uid):array
    {
        try {
            return GroupDao::getUserGroup($uid);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }
}