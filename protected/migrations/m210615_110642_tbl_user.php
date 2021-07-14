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
class m210615_110642_tbl_user extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (! isset($table->columns['activity_id'])) {
            $this->execute("ALTER TABLE `tbl_user` ADD `activity_id` int(11) DEFAULT NULL AFTER `emoji_id`;");
        }
        if (! isset($table->columns['language_id'])) {
            $this->execute("ALTER TABLE `tbl_user` ADD `language_id` int(11) DEFAULT NULL AFTER `activity_id`;");
        }
        if (! isset($table->columns['target_area_id'])) {
            $this->execute("ALTER TABLE `tbl_user` ADD `target_area_id` int(11) DEFAULT NULL AFTER `language_id`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (isset($table->columns['activity_id'])) {
            $this->dropColumn('{{%user}}', 'activity_id');
        }
        if (isset($table->columns['language_id'])) {
            $this->dropColumn('{{%user}}', 'language_id');
        }
        if (isset($table->columns['target_area_id'])) {
            $this->dropColumn('{{%user}}', 'target_area_id');
        }
    }
}