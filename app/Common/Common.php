<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/4
 * Time: 18:30
 */

namespace App\Common;


use Swoft\Context\Context;
use Swoft\Http\Message\Response;
use Swoft\Redis\Redis;

class Common
{

    /**
     * websoket uid->fd redis key
     * @var string
     */
    private static $_fdKey = 'user:ws';

    private static $_url = 'http://schat.com:9992';

    /**
     * 返回json数据
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \Swoft\Exception\SwoftException
     */
    public static function reJson(int $code = 200, string $msg = 'success', array $data = [])
    {
        $response = Context::get()->getResponse();
        $response->withContentType('application/json', 'utf-8');
        return $response->withData(compact('code', 'msg', 'data'));
    }

    /**
     * @param string $type
     * @param string $msg
     * @param array $data
     * @return false|string
     */
    public static function reWsJson(string $type,string $msg,array $data=[])
    {
//        $response = Context::get()->getResponse();
//        $response->withContentType('application/json', 'utf-8');
        return json_encode(compact('type', 'msg', 'data'));
    }

    /**
     * 获取用的FD
     * @param int $uid
     * @return int
     * @throws \Exception
     */
    public static function getFd(int $uid):int
    {
        try {
            return Redis::hGet(self::$_fdKey,strval($uid));
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * 获取地址
     * @param string $path
     * @return string
     */
    public static function getUrl(string $path):string{
        return self::$_url.$path;
    }
}