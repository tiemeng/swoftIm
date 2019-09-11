<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 用户表
 * Class Users
 *
 * @since 2.0
 *
 * @Entity(table="users")
 */
class Users extends Model
{
    /**
     * 
     * @Id()
     * @Column()
     *
     * @var int
     */
    private $id;

    /**
     * 用户名
     *
     * @Column()
     *
     * @var string
     */
    private $username;

    /**
     * 密码
     *
     * @Column(name="user_pwd", prop="userPwd")
     *
     * @var string
     */
    private $userPwd;

    /**
     * 昵称
     *
     * @Column()
     *
     * @var string
     */
    private $nickname;

    /**
     * 头像
     *
     * @Column()
     *
     * @var string
     */
    private $avatar;

    /**
     * 登录TOKEN
     *
     * @Column()
     *
     * @var string
     */
    private $token;

    /**
     * 是否在线 1：不在线 2：在线
     *
     * @Column(name="is_online", prop="isOnline")
     *
     * @var int
     */
    private $isOnline;

    /**
     * 个性签名
     *
     * @Column(name="sign", prop="sign")
     *
     * @var string
     */
    private $sign;

    /**
     * 创建时间
     *
     * @Column(name="created_at", prop="createdAt")
     *
     * @var string
     */
    private $createdAt;


    /**
     * @param int $id
     *
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $username
     *
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $userPwd
     *
     * @return void
     */
    public function setUserPwd(string $userPwd): void
    {
        $this->userPwd = $userPwd;
    }

    /**
     * @param string $nickname
     *
     * @return void
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @param string $avatar
     *
     * @return void
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @param string $token
     *
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @param int $isOnline
     *
     * @return void
     */
    public function setIsOnline(int $isOnline): void
    {
        $this->isOnline = $isOnline;
    }

    /**
     * @param string $sign
     *
     * @return void
     */
    public function setSign(string $sign): void
    {
        $this->sign = $sign;
    }

    /**
     * @param string $createdAt
     *
     * @return void
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getUserPwd(): ?string
    {
        return $this->userPwd;
    }

    /**
     * @return string
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getIsOnline(): ?int
    {
        return $this->isOnline;
    }

    /**
     * @return string
     */
    public function getSign():string{
        return $this->sign;
    }
    /**
     * @return string
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

}
