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
class m210623_160603_tbl_focus extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%focus}}');
        if (! isset($table->columns['image_file'])) {
            $this->execute("ALTER TABLE `tbl_focus` ADD `image_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL  AFTER `description`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%focus}}');
        if (isset($table->columns['image_file'])) {
            $this->dropColumn('{{%focus}}', 'image_file');
        }
    }
}