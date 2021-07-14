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
class m210623_160619_tbl_focus extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%focus}}');
        if (! isset($table->columns['duration'])) {
            $this->execute("ALTER TABLE `tbl_focus` ADD `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL  AFTER `end_time`;");
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%focus}}');
        if (! isset($table->columns['budget'])) {
            $this->execute("ALTER TABLE `tbl_focus` ADD `budget` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL  AFTER `duration`;");
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%focus}}');
        if (! isset($table->columns['location'])) {
            $this->execute("ALTER TABLE `tbl_focus` ADD `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL  AFTER `budget`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%focus}}');
        if (isset($table->columns['duration'])) {
            $this->dropColumn('{{%focus}}', 'duration');
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%focus}}');
        if (isset($table->columns['budget'])) {
            $this->dropColumn('{{%focus}}', 'budget');
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%focus}}');
        if (isset($table->columns['location'])) {
            $this->dropColumn('{{%focus}}', 'location');
        }
    }
}