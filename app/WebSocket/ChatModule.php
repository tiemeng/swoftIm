<?php declare(strict_types=1);

namespace App\WebSocket;

use App\Common\Common;
use App\Model\Logic\UserLogic;
use App\WebSocket\Chat\HomeController;
use Swoft\Http\Message\Request;
use Swoft\Redis\Redis;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\WebSocket\Server\MessageParser\JsonParser;
use function server;

/**
 * Class ChatModule
 *
 * @WsModule(
 *     "/chat",
 *     messageParser=JsonParser::class,
 *     controllers={HomeController::class}
 * )
 */
class ChatModule
{
    /**
     * @OnOpen()
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Request $request, int $fd): void
    {
        $token = $request->getQueryParams()['token'] ?? '';
        if(empty($token)){
            server()->push($request->getFd(),Common::reWsJson('error','token error'));
            return ;
        }
        try{
            $userInfo = UserLogic::getInfo($token);
            if(empty($userInfo)){
                server()->push($request->getFd(),Common::reWsJson('error','用户不存在'));
                return ;
            }
            !Redis::hSet('user:ws',strval($userInfo['id']),strval($fd)) && Redis::hSet('user:ws',strval($userInfo['id']),strval($fd));
        }catch (\Throwable $e){
            server()->push($request->getFd(),$e->getMessage());
        }



    }
}
