<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/9
 * Time: 14:37
 */

namespace App\Http\Controller;

use App\Model\Logic\UserGroupLogic;
use App\Model\Logic\UserLogic;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class FindController
 * @Middleware(name="App\Http\Middleware\LoginMiddleware")
 * @Controller()
 * @package App\Http\Controller
 */
class FindController
{
    /**
     * @RequestMapping("/find")
     * @param Request $request
     * @return \Swoft\Http\Message\Response
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $type = $request->input('type') ?? 'user';
        $wd = $request->input('wd') ?? '';
        $userList = $groupList = [];
        if ($type == 'user') {
            $where = [['nickname', 'like', '%' . $wd . '%']];
            $userList = UserLogic::getUserList($where, ['nickname', 'id', 'avatar']);
        } else {
            $where = [['name', 'like', '%' . $wd . '%'], ['type', '=', 2]];
            $groupList = UserGroupLogic::getGroupList($where, ['name', 'id', 'avatar']);
        }

        return view('find/find', compact('type', 'wd', 'userList', 'groupList'));
    }
}