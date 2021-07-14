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
class m210622_160629_tbl_target_trade extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%target_trade}}');
        if (! isset($table->columns['target_area_id'])) {
            $this->execute("ALTER TABLE `tbl_target_trade` ADD `target_area_id` int(11) DEFAULT NULL  AFTER `description`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%target_trade}}');
        if (isset($table->columns['target_area_id'])) {
            $this->dropColumn('{{%target_trade}}', 'target_area_id');
        }
    }
}