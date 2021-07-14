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
class m210623_160653_tbl_news extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (! isset($table->columns['duration'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL  AFTER `image_file`;");
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (! isset($table->columns['budget'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `budget` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL  AFTER `duration`;");
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (! isset($table->columns['location'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL  AFTER `budget`;");
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (! isset($table->columns['start_date'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `start_date` date DEFAULT NULL  AFTER `location`;");
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (! isset($table->columns['end_date'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `end_date` date DEFAULT NULL  AFTER `start_date`;");
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (! isset($table->columns['start_time'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `start_time` int(11) DEFAULT NULL  AFTER `end_date`;");
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (! isset($table->columns['end_time'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `end_time` int(11) DEFAULT NULL  AFTER `start_time`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['duration'])) {
            $this->dropColumn('{{%news}}', 'duration');
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['budget'])) {
            $this->dropColumn('{{%news}}', 'budget');
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['location'])) {
            $this->dropColumn('{{%news}}', 'location');
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['start_date'])) {
            $this->dropColumn('{{%news}}', 'start_date');
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['end_date'])) {
            $this->dropColumn('{{%news}}', 'end_date');
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['start_time'])) {
            $this->dropColumn('{{%news}}', 'start_time');
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['end_time'])) {
            $this->dropColumn('{{%news}}', 'end_time');
        }
    }
}