<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/6
 * Time: 09:56
 */

namespace App\Model\Dao;


use App\Model\Entity\Group;

class GroupDao
{
    /**
     * 根据ID获取分组信息
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public static function getInfoById(int $id): array
    {
        try {
            return Group::where(['id' => $id])->first()->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 加入群组
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public static function insertData(array $data): string
    {
        try {
            return Group::insertGetId($data);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 获取群成员
     * @param int $gid
     * @return array
     * @throws \Exception
     */
    public static function getUserList(int $gid): array
    {
        try {
            return Group::leftJoin('users', 'group.uid', '=', 'users.id')->where(['gid' => $gid])->get([
                'users.id',
                'users.username',
                'users.avatar',
                'users.sign'
            ])->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 获取群成员总数
     * @param int $gid
     * @return int
     * @throws \Exception
     */
    public static function getCount(int $gid)
    {
        try {
            return Group::leftJoin('users', 'group.id', '=', 'uid')->where(['gid' => $gid])->count();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 获取群列表
     * @param array $where
     * @param array $fields
     * @return array
     * @throws \Exception
     */
    public static function getGroupList(array $where, array $fields): array
    {
        try {
            return Group::where($where)->get($fields)->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * 是否为群成员
     * @param int $gid
     * @param int $uid
     * @return bool
     * @throws \Exception
     */
    public static function isMember(int $gid, int $uid): bool
    {
        try {
            $info = Group::where(['uid' => $uid, 'gid' => $gid])->first(['id']);
            if (is_null($info)) {
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * 获取用户群组
     * @param int $uid
     * @return array
     * @throws \Exception
     */
    public static function getUserGroup(int $uid): array
    {
        try {
            $info = Group::leftJoin('user_group', 'group.gid', '=', 'user_group.id')->where([
                'group.uid' => $uid,
                'type' => 2
            ])->get(['user_group.id', 'user_group.name as groupname', 'user_group.avatar']);
            if (is_null($info)) {
                return [];
            }
            return $info->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }

}