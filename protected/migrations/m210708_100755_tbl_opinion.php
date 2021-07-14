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
class m210708_100755_tbl_opinion extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%opinion}}');
        if (! isset($table->columns['to_id'])) {
            $this->execute("ALTER TABLE `tbl_opinion` ADD `to_id` int(11) DEFAULT NULL  AFTER `image_file`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%opinion}}');
        if (isset($table->columns['to_id'])) {
            $this->dropColumn('{{%opinion}}', 'to_id');
        }
    }
}