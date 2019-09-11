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
 * Class Users
 * @\Swoft\Devtool\Annotation\Mapping\Migration(time=201909041636,db="db.pool")
 * @package App\Migration
 */
class Users extends Migration
{

    /**
     * @return void
     */
    public function up(): void
    {
        $sql = <<<sql
CREATE TABLE chat_users (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR ( 32 ) NOT NULL UNIQUE KEY DEFAULT '' COMMENT '用户名',
	user_pwd CHAR ( 60 ) NOT NULL DEFAULT '' COMMENT '密码',
	nickname VARCHAR ( 20 ) NOT NULL DEFAULT '' COMMENT '昵称',
	avatar VARCHAR ( 200 ) NOT NULL DEFAULT '' COMMENT "头像",
	token VARCHAR(32) NOT NULL UNIQUE KEY DEFAULT '' COMMENT '登录TOKEN',
	is_online SMALLINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否在线 1：不在线 2：在线',
	sign varchar(50) NOT NULL DEFAULT '' COMMENT '个性签名',
	created_at TIMESTAMP NOT NULL DEFAULT '2019-12-12 12:12:12' COMMENT '创建时间' 
) COMMENT '用户表';
sql;
        $this->execute($sql);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $dropSql = <<<sql
drop table if exists `chat_users`;
sql;
        $this->execute($dropSql);
    }
}