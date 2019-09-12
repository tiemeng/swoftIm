<?php
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/4
 * Time: 15:24
 */

namespace App\Migration;


use Swoft\Devtool\Migration\Migration;

/**
 * Class Msg
 * @\Swoft\Devtool\Annotation\Mapping\Migration(time=201909041636,db="db.pool")
 * @package App\Migration
 */
class Msg extends Migration
{

    /**
     * @return void
     */
    public function up(): void
    {
        $sql = <<<sql
CREATE TABLE `chat_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `fuid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '好友ID',
  `gid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '群ID',
  `type` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '消息类型 1：个人 2：群消息',
  `content` text COMMENT '内容',
  `created_at` timestamp NOT NULL DEFAULT '2019-12-12 12:12:12' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='消息表';
sql;
        $this->execute($sql);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $dropSql = <<<sql
drop table if exists `chat_msg`;
sql;
        $this->execute($dropSql);
    }
}