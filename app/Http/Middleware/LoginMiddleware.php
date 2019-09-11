<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/5
 * Time: 15:29
 */

namespace App\Http\Middleware;


use App\Common\Common;
use App\Model\Logic\UserLogic;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use Swoft\Http\Server\Contract\MiddlewareInterface;

/**
 * @Bean()
 */
class LoginMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getQueryParams();
        $token = $params['token'] ?? '';
        try{
            $userInfo = UserLogic::getInfo($token);
            if(empty($userInfo)){
                if($request->getMethod() == "ajax"){
                    return Common::reJson(103,'请先登录');
                }
                return Context::get()->getResponse()->redirect('/login');
            }
        }catch (\Throwable $e){
            return Context::get()->getResponse()->redirect('/login');
        }
        return $handler->handle($request);
    }
}