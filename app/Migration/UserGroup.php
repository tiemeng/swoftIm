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
 * Class UserGroup
 * @\Swoft\Devtool\Annotation\Mapping\Migration(time=201909041636,db="db.pool")
 * @package App\Migration
 */
class UserGroup extends Migration
{

    /**
     * @return void
     */
    public function up(): void
    {
        $sql = <<<sql
CREATE TABLE chat_user_group (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR ( 10 ) NOT NULL DEFAULT '' COMMENT '组名',
	uid INT UNSIGNED NOT NULL DEFAULT 0 COMMENT "用户ID",
	avatar VARCHAR ( 200 ) NOT NULL DEFAULT '' COMMENT "头像",
	type SMALLINT UNSIGNED not null DEFAULT 1 COMMENT "群组类型 1：个人 2：群组",
	created_at TIMESTAMP NOT NULL DEFAULT '2019-12-12 12:12:12' COMMENT '创建时间'
) COMMENT '用户分组';
sql;
        $this->execute($sql);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $dropSql = <<<sql
drop table if exists `chat_user_group`;
sql;
        $this->execute($dropSql);
    }
}