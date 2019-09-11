<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 消息表
 * Class Msg
 *
 * @since 2.0
 *
 * @Entity(table="msg")
 */
class Msg extends Model
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
     * 用户ID
     *
     * @Column()
     *
     * @var int
     */
    private $uid;

    /**
     * 好友ID
     *
     * @Column()
     *
     * @var int
     */
    private $fuid;

    /**
     * 群ID
     *
     * @Column()
     *
     * @var int
     */
    private $gid;

    /**
     * 消息类型 1：个人 2：群消息
     *
     * @Column()
     *
     * @var int
     */
    private $type;

    /**
     * 内容
     *
     * @Column()
     *
     * @var string|null
     */
    private $content;

    /**
     * 添加时间
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
     * @param int $fuid
     *
     * @return void
     */
    public function setFuid(int $fuid): void
    {
        $this->fuid = $fuid;
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
     * @param int $type
     *
     * @return void
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string|null $content
     *
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
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
    public function getFuid(): ?int
    {
        return $this->fuid;
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
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

}
