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
class m210622_160627_tbl_activity extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%activity}}');
        if (! isset($table->columns['domain_id'])) {
            $this->execute("ALTER TABLE `tbl_activity` ADD `domain_id` int(11) DEFAULT NULL AFTER `description`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%activity}}');
        if (isset($table->columns['domain_id'])) {
            $this->dropColumn('{{%activity}}', 'domain_id');
        }
    }
}