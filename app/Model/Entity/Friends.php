<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 好友关系表
 * Class Friends
 *
 * @since 2.0
 *
 * @Entity(table="friends")
 */
class Friends extends Model
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
     * 分组ID
     *
     * @Column()
     *
     * @var int
     */
    private $gid;

    /**
     * 别名
     *
     * @Column()
     *
     * @var string
     */
    private $alais;

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
     * @param string $alais
     *
     * @return void
     */
    public function setAlais(string $alais): void
    {
        $this->alais = $alais;
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
     * @return string
     */
    public function getAlais(): ?string
    {
        return $this->alais;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

}
