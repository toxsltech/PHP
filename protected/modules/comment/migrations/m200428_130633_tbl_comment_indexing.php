<?php
use yii\db\Migration;

class m200428_130633_tbl_comment_indexing extends Migration
{

    public function safeUp()
    {
        $this->execute("
ALTER TABLE `tbl_comment` ADD INDEX(`model_type`);
ALTER TABLE `tbl_comment` ADD INDEX(`model_id`);
");
    }

    public function safeDown()
    {
        echo "m200428_130633_tbl_comment_indexing migrating down by doing nothing....\n";
    }
}