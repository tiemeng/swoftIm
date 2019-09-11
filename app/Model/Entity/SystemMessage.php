<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 系统消息表
 * Class SystemMessage
 *
 * @since 2.0
 *
 * @Entity(table="system_message")
 */
class SystemMessage extends Model
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
     * 接收用户id
     *
     * @Column()
     *
     * @var int
     */
    private $uid;

    /**
     * 来源相关用户id
     *
     * @Column(name="from_id", prop="fromId")
     *
     * @var int
     */
    private $fromId;

    /**
     * 
     *
     * @Column()
     *
     * @var int
     */
    private $gid;

    /**
     * 添加好友附言
     *
     * @Column()
     *
     * @var string
     */
    private $remark;

    /**
     * 0好友请求 1请求结果通知
     *
     * @Column()
     *
     * @var int
     */
    private $type;

    /**
     * 0未处理 1同意 2拒绝
     *
     * @Column()
     *
     * @var int
     */
    private $status;

    /**
     * 0未读 1已读，用来显示消息盒子数量
     *
     * @Column()
     *
     * @var int
     */
    private $read;

    /**
     * 
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
     * @param int $uid
     *
     * @return void
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @param int $fromId
     *
     * @return void
     */
    public function setFromId(int $fromId): void
    {
        $this->fromId = $fromId;
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
     * @param string $remark
     *
     * @return void
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * @param int $type
     *
     * @return void
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @param int $status
     *
     * @return void
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @param int $read
     *
     * @return void
     */
    public function setRead(int $read): void
    {
        $this->read = $read;
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
    public function getUid(): ?int
    {
        return $this->uid;
    }

    /**
     * @return int
     */
    public function getFromId(): ?int
    {
        return $this->fromId;
    }

    /**
     * @return int
     */
    public function getGid(): ?int
    {
        return $this->gid;
    }

    /**
     * @return string
     */
    public function getRemark(): ?string
    {
        return $this->remark;
    }

    /**
     * @return int
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getRead(): ?int
    {
        return $this->read;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

}
