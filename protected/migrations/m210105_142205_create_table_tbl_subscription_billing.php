<?php
use yii\db\Migration;

/**
 * Class m210105_142205_create_table_tbl_subscription_billing
 */
class m210105_142205_create_table_tbl_subscription_billing extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%subscription_billing}}');
        if (! isset($table)) {
            $this->createTable('{{%subscription_billing}}', [
                'id' => $this->primaryKey(),
                'subscription_id' => $this->integer()
                    ->notNull(),
                'start_date' => $this->dateTime()
                    ->defaultValue(null),
                'end_date' => $this->dateTime()
                    ->defaultValue(null),
                'state_id' => $this->integer()
                    ->notNull(),
                'type_id' => $this->integer()
                    ->notNull(),
                'created_on' => $this->dateTime()
                ->defaultValue(null),
                'created_by_id' => $this->integer()
                    ->notNull()
            ]);
            $this->addForeignKey('fk_subscription_billing_created_by_id', '{{%subscription_billing}}', 'created_by_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
            $this->addForeignKey('fk_subscription_billing_subscription_id', '{{%subscription_billing}}', 'subscription_id', '{{%subscription_plan}}', 'id', 'RESTRICT', 'RESTRICT');
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%subscription_billing}}');
        if (isset($table)) {
            $this->dropTable('{{%subscription_billing}}');
        }
    }
}
