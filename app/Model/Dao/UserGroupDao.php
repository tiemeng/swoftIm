<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/6
 * Time: 10:05
 */

namespace App\Model\Dao;


use App\Model\Entity\UserGroup;

class UserGroupDao
{
    /**
     * 获取用户不同类型的群组信息
     * @param int $uid
     * @param int $type
     * @return array
     * @throws \Exception
     */
    public static function getUserGroupByType(int $uid, int $type = 1): array
    {
        try {
            return UserGroup::where(['uid' => $uid, 'type' => $type])->get()->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public static function getInfoById(int $id):array
    {
        try {
            return UserGroup::where(['id' => $id])->first()->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * 创建分组/群组
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public static function insertData(array $data):string
    {
        try{
            return UserGroup::insertGetId($data);
        }catch (\Throwable $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 获取用户的群组
     * @param array $where
     * @param array $fileds
     * @return array
     * @throws \Exception
     */
    public static function getGroupList(array $where,array $fileds=['*']):array
    {
        try {
            return UserGroup::where($where)->get($fileds)->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }
}