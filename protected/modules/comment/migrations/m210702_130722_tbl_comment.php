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
class m210702_130722_tbl_comment extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%comment}}');
        if (isset($table->columns['comment'])) {
            $this->execute("ALTER TABLE `tbl_comment` CHANGE `comment` `comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%comment}}');
        if (! isset($table->columns['comment'])) {
            $this->execute("ALTER TABLE `tbl_comment` CHANGE `comment` `comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL;");
        }
    }
}