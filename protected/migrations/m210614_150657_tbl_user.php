<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
use yii\db\Migration;

/**
 * php console.php module/migrate
 */
class m210614_150657_tbl_user extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (! isset($table->columns['emoji_id'])) {
            $this->execute("ALTER TABLE `tbl_user` ADD `emoji_id` int(11) DEFAULT NULL AFTER `latitude`;");
        }
        if (! isset($table->columns['domain_id'])) {
            $this->execute("ALTER TABLE `tbl_user` ADD `domain_id` int(11) DEFAULT NULL AFTER `emoji_id`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (isset($table->columns['emoji_id'])) {
            $this->dropColumn('{{%user}}', 'emoji_id');
        }
        if (isset($table->columns['domain_id'])) {
            $this->dropColumn('{{%user}}', 'domain_id');
        }
    }
}