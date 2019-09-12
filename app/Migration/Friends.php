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
 * Class Friends
 * @\Swoft\Devtool\Annotation\Mapping\Migration(time=201909041636,db="db.pool")
 * @package App\Migration
 */
class Friends extends Migration
{

    /**
     * @return void
     */
    public function up(): void
    {
        $sql = <<<sql
CREATE TABLE `chat_friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `fuid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '好友ID',
  `gid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分组ID',
  `alais` varchar(20) NOT NULL DEFAULT '' COMMENT '别名',
  `created_at` timestamp NOT NULL DEFAULT '2019-12-12 12:12:12' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='好友关系表';
sql;
        $this->execute($sql);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $dropSql = <<<sql
drop table if exists `chat_friends`;
sql;
        $this->execute($dropSql);
    }
}