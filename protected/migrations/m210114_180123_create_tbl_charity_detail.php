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
class m210114_180123_create_tbl_charity_detail extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%charity_detail}}', [
            'id' => $this->primaryKey(),
            'amount' => $this->string()(64)->notNull(),
            'charity_id' => $this->integer()
                ->notNull(),
            'user_id' => $this->integer()
                ->notNull(),
            'state_id' => $this->integer()
                ->defaultValue(0),
            'type_id' => $this->integer()
                ->defaultValue(0),
            'created_on' => $this->dateTime()
                ->defaultValue(null),
            'created_by_id' => $this->integer()
                ->notNull()
        ]);
        $this->addForeignKey('fk_charity_detail_created_by_id', '{{%subscription_billing}}', 'created_by_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk_charity_detail_charity_id', '{{%charity_detail}}', 'charity_id', '{{%charity}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%charity_detail}}');
        if (isset($table)) {
            $this->dropTable('{{%charity_detail}}');
        }
    }
}