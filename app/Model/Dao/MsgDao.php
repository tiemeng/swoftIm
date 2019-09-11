<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/10
 * Time: 18:01
 */

namespace App\Model\Dao;


use App\Model\Entity\Msg;

class MsgDao
{
    /**
     * 插入消息
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public static function insertData(array $data):bool
    {
        try {
            return Msg::insert($data);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }
}