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
class m210107_120155_alter_table_order_item extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%order_item}}');
       
        if (isset($table->columns['product_id'])) {
            $this->dropForeignKey('fk_order_item_product_id', '{{%order_item}}');
            $this->renameColumn('{{%order_item}}', 'product_id', 'item_id');
        }else{
            $table = Yii::$app->db->schema->getTableSchema('{{%order_item}}');
            $this->addColumn('{{%order_item}}', 'item_id', $this->integer()->notNull());
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%order_item}}');
        if (!isset($table->columns['item_id'])) {
            $this->dropColumn('{{%order_item}}', 'item_id');
        }
    }
}