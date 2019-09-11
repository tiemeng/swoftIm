<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/9
 * Time: 16:27
 */

namespace App\Model\Dao;


use App\Model\Entity\SystemMessage;

class SystemMessageDao
{
    /**
     * 添加数据
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public static function add(array $data):bool
    {
        try {
            return SystemMessage::insert($data);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * 获取列表
     * @param array $where
     * @return array
     * @throws \Exception
     */
    public static function getList(array $where):array
    {
        try {
            return SystemMessage::where($where)->orderBy('read')->orderBy('id','desc')->get()->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }


    /**
     * 获取未读消息数
     * @param int $uid
     * @return int
     * @throws \Exception
     */
    public static function getUnReadCount(int $uid):int
    {
        try {
            return SystemMessage::where(['uid'=>$uid,'read'=>0])->count();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * 通过ID获取信息
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public static function getInfoById(int $id):array
    {
        try {
            return SystemMessage::where(['id'=>$id])->first()->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
        
    }

    /**
     * 更新数据
     * @param array $where
     * @param array $updateValue
     * @return int
     * @throws \Exception
     */
    public static function updateData(array $where,array $updateValue)
    {
        try {
            return SystemMessage::where($where)->update($updateValue);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }
}