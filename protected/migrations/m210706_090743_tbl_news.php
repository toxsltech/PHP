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
class m210706_090743_tbl_news extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (! isset($table->columns['domain_id'])) {
            $this->execute("ALTER TABLE `tbl_news` ADD `domain_id` int(11) DEFAULT NULL  AFTER `image_file`;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%news}}');
        if (isset($table->columns['domain_id'])) {
            $this->dropColumn('{{%news}}', 'domain_id');
        }
    }
}