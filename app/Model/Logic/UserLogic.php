<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/4
 * Time: 18:12
 */

namespace App\Model\Logic;

use App\Exception\DatabaseException;
use App\Model\Dao\UserDao;
use App\Model\Dao\UserGroupDao;
use App\Model\Entity\Users;
use Swoft\Redis\Redis;

/**
 * Class UserLogic
 * @package App\Model\Logic
 */
class UserLogic
{
    /**
     * 注册
     * @param array $data
     * @return string
     * @throws DatabaseException
     */
    public static function register(array $data): string
    {
        try {
            $insertData = self::_checkData($data);
            $id = Users::insertGetId($insertData);
            !UserGroupDao::insertData([
                'name' => '默认分组',
                'uid' => intval($id),
                'created_at' => date('Y-m-d H:i:s')
            ]) && UserGroupDao::insertData([
                'name' => '默认分组',
                'uid' => intval($id),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $id;
        } catch (\Throwable $e) {
            throw new  DatabaseException($e->getMessage());
        }
    }

    /**
     * 用户登录
     * @param string $username
     * @param string $password
     * @return bool|string
     * @throws DatabaseException
     */
    public static function login(string $username, string $password)
    {
        if (empty($username) || empty($password)) {
            return false;
        }
        try {
            if (UserDao::login($username, $password)) {
                UserDao::tabOnlineStatus($username, 2);
                return UserDao::setToken($username);
            }
            return false;
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 通过token获取用户信息
     * @param string $token
     * @return array
     * @throws DatabaseException
     */
    public static function getInfo(string $token): array
    {
        if (empty($token)) {
            throw new DatabaseException('参数错误');
        }
        try {
            return UserDao::getInfoByToken($token);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * 通过用户ID获取用户信息
     * @param int $id
     * @param array $fields
     * @return array
     * @throws DatabaseException
     */
    public static function getInfoById(int $id, array $fields = ['*'])
    {
        if ($id <= 0) {
            throw new DatabaseException('参数错误');
        }
        try {
            $users = UserDao::getInfoById($id, $fields);
            return $users;
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public static function updateSign(string $token, string $sign)
    {
        try {
            if (empty($token) || empty($sign)) {
                throw new DatabaseException('参数错误');
            }
            return UserDao::updateSign($token, $sign);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }

    public static function getUserList($where, $fields = ['*'])
    {
        try {
            return UserDao::getUserList($where, $fields);
        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }

    /**
     * 退出登录
     * @param int $uid
     * @return int
     * @throws DatabaseException
     */
    public static function logout(int $uid)
    {
        try {
            $affectRow = UserDao::logout($uid);
            if ($affectRow > 0) {
                !Redis::hDel('user:ws', $uid) && Redis::hDel('user:ws', $uid);
                return $affectRow;
            }
            return 0;

        } catch (\Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }

    }

    /**
     * @param array $data
     * @return array
     * @throws DatabaseException
     */
    protected static function _checkData(array $data): array
    {
        if (empty($data)) {
            throw new DatabaseException('数据解析错误');
        }
        $userName = $data['username'] ?? "";
        $nickname = $data['nickname'] ?? '';
        $avatar = $data['avatar'] ?? "http://tp3.sinaimg.cn/1223762662/180/5741707953/0";
        $userPwd = $data['user_pwd'] ?? '';
        $sign = $data['sign'] ?? '';
        if (empty($userName) || strlen($userName) > 20 || strlen($userName) < 2) {
            throw new DatabaseException('用户名必须3-20个字符');
        }
        if (empty($userPwd) && strlen($userName) < 6) {
            throw new DatabaseException('密码不能少于6位');
        }
        return [
            'username' => trim($userName),
            'user_pwd' => password_hash(trim($userPwd), PASSWORD_BCRYPT),
            'nickname' => trim($nickname),
            'avatar' => trim($avatar),
            'sign' => trim($sign),
            'created_at' => date("Y-m-d H:i:s")
        ];
    }
}