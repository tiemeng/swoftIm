<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 用户群
 * Class Group
 *
 * @since 2.0
 *
 * @Entity(table="group")
 */
class Group extends Model
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
     * 分组ID
     *
     * @Column()
     *
     * @var int
     */
    private $gid;

    /**
     * 用户ID
     *
     * @Column()
     *
     * @var int
     */
    private $uid;

    /**
     * 是否为管理员 1：不是 2：是
     *
     * @Column(name="is_admin", prop="isAdmin")
     *
     * @var int
     */
    private $isAdmin;

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
     * @param int $gid
     *
     * @return void
     */
    public function setGid(int $gid): void
    {
        $this->gid = $gid;
    }

    /**
     * @param int $uid
     *
     * @return void
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @param int $isAdmin
     *
     * @return void
     */
    public function setIsAdmin(int $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
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
     * @return int
     */
    public function getGid(): ?int
    {
        return $this->gid;
    }

    /**
     * @return int
     */
    public function getUid(): ?int
    {
        return $this->uid;
    }

    /**
     * @return int
     */
    public function getIsAdmin(): ?int
    {
        return $this->isAdmin;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

}
