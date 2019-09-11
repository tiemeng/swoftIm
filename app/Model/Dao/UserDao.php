<?php declare(strict_types=1);


namespace App\Model\Dao;

use App\Model\Entity\Friends;
use App\Model\Entity\Users;

/**
 * Class UserDao
 * @package App\Model\Dao
 */
class UserDao
{
    /**
     * 用户登录
     * @param string $username
     * @param string $password
     * @return bool
     * @throws \Exception
     */
    public static function login(string $username, string $password): bool
    {
        try {
            $userPass = Users::where('username', '=', $username)->value('user_pwd');
            return $userPass ? password_verify($password, $userPass) : false;
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * 设置token
     * @param string $username
     * @return string
     * @throws \Exception
     */
    public static function setToken(string $username): string
    {
        try {
            $token = md5(uniqid());
            return Users::where(['username' => $username])->update(['token' => $token]) ? $token : '';
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 通过token获取用户信息
     * @param string $token
     * @return array
     * @throws \Exception
     */
    public static function getInfoByToken(string $token): array
    {
        try {
            return Users::where(['token' => $token])->first()->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 通过用户Id获取用户信息
     * @param int $id
     * @param array $fields
     * @return array
     * @throws \Exception
     */
    public static function getInfoById(int $id, array $fields = ['*'])
    {
        try {
            return Users::where(['id' => $id])->first($fields)->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 获取在线人数
     * @param int $uid
     * @return int
     * @throws \Exception
     */
    public static function getOnlineCount(int $uid): int
    {
        try {
            return Friends::where(['uid' => $uid, 'is_online' => 2])->leftJoin('users', 'users.id', '=',
                'friends.fuid')->count();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 切换在线状态
     * @param string $username
     * @param int $status
     * @return int
     * @throws \Exception
     */
    public static function tabOnlineStatus(string $username, int $status): int
    {
        try {
            return Users::where(['username' => $username])->update(['is_online' => $status]);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 修改个性签名
     * @param string $token
     * @param string $sign
     * @return int
     * @throws \Exception
     */
    public static function updateSign(string $token, string $sign): int
    {
        try {
            return Users::where(['token' => $token])->update(['sign' => $sign]);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * @param array $where
     * @param array $fields
     * @return array
     * @throws \Exception
     */
    public static function getUserList(array $where, array $fields = ["*"]): array
    {
        try{
            return Users::where($where)->get($fields)->toArray();
        }catch (\Throwable $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 退出登录
     * @param int $uid
     * @return int
     * @throws \Exception
     */
    public static function logout(int $uid)
    {
        try {
            return Users::where(['id'=>$uid])->update(['token'=>'']);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

    }
}