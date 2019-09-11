<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/6
 * Time: 10:06
 */

namespace App\Model\Logic;


use App\Exception\DatabaseException;
use App\Model\Dao\GroupDao;
use App\Model\Dao\UserGroupDao;

class UserGroupLogic
{
    /**
     * 获取用户不同类型的群组信息
     * @param int $uid
     * @param int $type
     * @return array
     * @throws DatabaseException
     */
    public static function getUserGroupByType(int $uid, int $type = 1): array
    {
        try {
            if ($uid <= 0) {
                throw new DatabaseException("参数错误");
            }
            $info = UserGroupDao::getUserGroupByType($uid, $type);
            foreach ($info as &$item) {
                $item['groupname'] = $item['name'];
            }
            return $info;
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws DatabaseException
     */
    public static function getInfoById(int $id): array
    {
        try {
            if ($id <= 0) {
                throw new DatabaseException("参数错误");
            }
            return UserGroupDao::getInfoById($id);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 获取群组列表
     * @param array $where
     * @param array $fields
     * @return array
     * @throws DatabaseException
     */
    public static function getGroupList(array $where, array $fields = ["*"]): array
    {
        try {
            return UserGroupDao::getGroupList($where, $fields);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }

    /**
     * 创建群组
     * @param array $data
     * @return string
     * @throws DatabaseException
     */
    public static function create(array $data): string
    {
        try {
            if (empty($data)) {
                throw new DatabaseException('参数错误');
            }
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = UserGroupDao::insertData($data);
            if ($id > 0) {
                !GroupDao::insertData([
                    'uid' => $data['uid'],
                    'gid' => $id,
                    'is_admin' => 2,
                    'created_at' => $data['created_at']
                ]) && GroupDao::insertData([
                    'uid' => $data['uid'],
                    'gid' => $id,
                    'is_admin' => 2,
                    'created_at' => $data['created_at']
                ]);
            }
            return $id;
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }
}