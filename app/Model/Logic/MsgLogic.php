<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/10
 * Time: 18:04
 */

namespace App\Model\Logic;


use App\Exception\DatabaseException;
use App\Model\Dao\MsgDao;

class MsgLogic
{
    /**
     * 插入聊天信息
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public static function insertData(array $data):bool
    {
        if(empty($data)){
            throw new DatabaseException('参数错误');
        }
        try {
            return MsgDao::insertData($data);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }
}