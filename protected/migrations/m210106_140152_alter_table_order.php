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
class m210106_140152_alter_table_order extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%order}}');
        if (! isset($table->columns['product_id'])) {
            $this->alterColumn('{{%order}}', 'product_id', $this->integer(11)
                ->defaultValue(0));
        }
        if (! isset($table->columns['address_id'])) {
            $this->alterColumn('{{%order}}', 'address_id', $this->integer(11)
                ->defaultValue(0));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%order}}');
        if (isset($table->columns['product_id'])) {
            $this->dropColumn('{{%order}}', 'product_id');
        }
        if (isset($table->columns['address_id'])) {
            $this->dropColumn('{{%order}}', 'address_id');
        }
    }
}