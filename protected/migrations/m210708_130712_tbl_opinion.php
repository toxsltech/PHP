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
class m210708_130712_tbl_opinion extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%opinion}}');
        if (! isset($table->columns['rating'])) {
            $this->execute("ALTER TABLE `tbl_opinion` ADD `rating` float(11,2) DEFAULT NULL after `image_file`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%opinion}}');
        if (isset($table->columns['rating'])) {
            $this->dropColumn('{{%opinion}}', 'rating');
        }
    }
}