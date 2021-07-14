<?php
use yii\db\Migration;

/**
 * Class m210105_151438_alter_table_order
 */
class m210105_151438_alter_table_order extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_order_product_id', '{{%order}}');
        $this->dropForeignKey('fk_order_address_id', '{{%order}}');
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210105_151438_alter_table_order cannot be reverted.\n";

        return false;
    }

    /*
     * // Use up()/down() to run migration code without a transaction.
     * public function up()
     * {
     *
     * }
     *
     * public function down()
     * {
     * echo "m210105_151438_alter_table_order cannot be reverted.\n";
     *
     * return false;
     * }
     */
}
