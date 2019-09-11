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
 * Class Group
 * @\Swoft\Devtool\Annotation\Mapping\Migration(time=201909041636,db="db.pool")
 * @package App\Migration
 */
class Group extends Migration
{

    /**
     * @return void
     */
    public function up(): void
    {
        $sql = <<<sql
CREATE TABLE chat_group ( 
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	gid INT UNSIGNED NOT NULL DEFAULT 0 COMMENT "分组ID",
	uid INT UNSIGNED NOT NULL DEFAULT 0 COMMENT "用户ID",
	is_admin SMALLINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否为管理员 1：不是 2：是',
	created_at TIMESTAMP NOT NULL DEFAULT '2019-12-12 12:12:12' COMMENT '创建时间'
)comment "用户群";
sql;
        $this->execute($sql);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $dropSql = <<<sql
drop table if exists `chat_group`;
sql;
        $this->execute($dropSql);
    }
}