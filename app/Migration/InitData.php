<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/6
 * Time: 11:11
 */

namespace App\Migration;

use Swoft\Devtool\Migration\Migration;

/**
 * Class InitData
 * @\Swoft\Devtool\Annotation\Mapping\Migration(time=201909061112,db="db.pool")
 * @package App\Migration
 */
class InitData extends Migration
{

    /**
     * @return void
     */
    public function up(): void
    {
        $sql = <<<sql
INSERT INTO chat_user_group VALUES(NULL,'默认分组',1,1,now());
INSERT INTO chat_users VALUES (null,"admin",'$2y$10$3dBJR56RI38pZVZzRwuWDuFl7/lfKJ2BLpp0Wg6O5.djMwiTnqGn.',"管理员",'','',1,'2019-09-05 14:44:03');
sql;
        $this->execute($sql);
    }

    /**
     * @return void
     */
    public function down(): void
    {
    }
}