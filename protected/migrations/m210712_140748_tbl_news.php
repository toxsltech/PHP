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
class m210712_140748_tbl_news extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (! isset($table->columns['latitude'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `latitude` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `end_time`;");
        }
        if (! isset($table->columns['longitude'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `longitude` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `latitude`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['latitude'])) {
            $this->dropColumn('{{%news}}', 'latitude');
        }
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['longitude'])) {
            $this->dropColumn('{{%news}}', 'latitude');
        }
    }
}