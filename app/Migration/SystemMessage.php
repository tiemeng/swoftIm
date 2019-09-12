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
 * Class SystemMessage
 * @\Swoft\Devtool\Annotation\Mapping\Migration(time=201909041636,db="db.pool")
 * @package App\Migration
 */
class SystemMessage extends Migration
{

    /**
     * @return void
     */
    public function up(): void
    {
        $sql = <<<sql
CREATE TABLE `chat_system_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '接收用户id',
  `from_id` int(11) NOT NULL COMMENT '来源相关用户id',
  `gid` int(11) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '添加好友附言',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0好友请求 1请求结果通知',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未处理 1同意 2拒绝',
  `read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未读 1已读，用来显示消息盒子数量',
  `created_at` timestamp NOT NULL DEFAULT '2019-09-04 12:12:12',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统消息表';
sql;
        $this->execute($sql);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $dropSql = <<<sql
drop table if exists `chat_system_message`;
sql;
        $this->execute($dropSql);
    }
}