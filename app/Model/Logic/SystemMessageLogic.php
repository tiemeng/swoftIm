<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/9
 * Time: 16:29
 */

namespace App\Model\Logic;


use App\Exception\DatabaseException;
use App\Model\Dao\SystemMessageDao;
use App\Model\Dao\UserDao;

class SystemMessageLogic
{
    /**
     * 增加系统消息
     * @param array $data
     * @return bool
     * @throws DatabaseException
     */
    public static function add(array $data): bool
    {
        try {
            return SystemMessageDao::add($data);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 获取消息列表
     * @param array $where
     * @return array
     * @throws DatabaseException
     */
    public static function getList(array $where): array
    {
        try {
            $list = SystemMessageDao::getList($where);
            foreach($list as &$item){
                $userInfo = UserDao::getInfoById($item['fromId']);
                $item['nickname'] = $userInfo['nickname'];
                $item['avatar'] = $userInfo['avatar'];
            }
            return $list;
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }

    /**
     * 获取未读消息数
     * @param int $uid
     * @return int
     * @throws DatabaseException
     */
    public static function getUnReadCount(int $uid):int
    {
        try {
            return SystemMessageDao::getUnReadCount($uid);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }

    /**
     * 通过ID获取信息
     * @param int $id
     * @return array
     * @throws DatabaseException
     */
    public static function getInfoById(int $id):array
    {
        try {
            return SystemMessageDao::getInfoById($id);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }


    /**
     * 更新数据
     * @param array $where
     * @param array $updateValue
     * @return mixed
     * @throws DatabaseException
     */
    public static function updateData(array $where,array $updateValue)
    {
        try {
            if(empty($where) || empty($updateValue)){
                throw new DatabaseException('参数错误');
            }
            return SystemMessageDao::updateData($where,$updateValue);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }
}