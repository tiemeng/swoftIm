<?php declare(strict_types=1);

namespace App\Http\Controller;

use App\Common\Common;
use App\Model\Logic\SystemMessageLogic;
use App\Model\Logic\UserLogic;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Throwable;

/**
 * Class HomeController
 * @Controller()
 */
class HomeController
{

    /**
     * 文件上传
     * @RequestMapping("/upload")
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\WebSocket\Server\Message\Response
     * @throws \Swoft\Exception\SwoftException
     */
    public function upload(Request $request)
    {
        $file = $request->getUploadedFiles()['file'] ?? null;
        if (is_null($file)) {
            return Common::reJson(201, '上传文件不能为空');
        }
        $dir = alias("@base");
        $imageFloder = DIRECTORY_SEPARATOR."public" . DIRECTORY_SEPARATOR . "image" . DIRECTORY_SEPARATOR . $file->getClientFileName();
        $file->moveTo($dir . $imageFloder);
        if ($file->isMoved()) {
            return Common::reJson(200, '上传成功', ['url' => Common::getUrl($imageFloder)]);
        }
        return Common::reJson(201, $file->getError());

    }

    /**
     * @RequestMapping("/",method={"GET"})
     * @Middleware(name="App\Http\Middleware\LoginMiddleware")
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function index(Request $request)
    {
        $mine = UserLogic::getInfo($request->get('token'));
        $count = SystemMessageLogic::getUnReadCount($mine['id']);
        return view('home/index', ['count' => $count]);
    }

    /**
     * @RequestMapping("/register")
     * @param Request $request
     * @return Response
     * @throws Throwable]
     */
    public function register(Request $request)
    {
        if ($request->isGet()) {
            return view('home/register');
        }
        $insertData = $request->input();
        $id = UserLogic::register($insertData);
        if (intval($id) > 0) {
            return Common::reJson();
        }
        return Common::reJson(201, $id);
    }

    /**
     * 用户登录
     * @RequestMapping("/login")
     * @param Request $request
     * @return Response|\Swoft\WebSocket\Server\Message\Response
     * @throws Throwable
     * @throws \Swoft\Exception\SwoftException
     */
    public function login(Request $request)
    {
        if ($request->isGet()) {
            return view('home/login');
        }
        $username = $request->input('username') ?? '';
        $password = $request->input('pwd') ?? '';
        if (empty($username) || empty($password)) {
            return Common::reJson(201, '用户名或密码不能为空');
        }

        $token = UserLogic::login($username, $password);
        if (!$token) {
            return Common::reJson(201, '用户名或者密码错误');
        }
        return Common::reJson(200, '登录成功', ['token' => $token]);
    }
}
