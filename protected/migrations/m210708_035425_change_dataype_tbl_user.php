<?php

use yii\db\Migration;

/**
 * Class m210708_035425_change_dataype_tbl_user
 */
class m210708_035425_change_dataype_tbl_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_user` CHANGE `language_id` `language_id` VARCHAR(64) NULL DEFAULT NULL; ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tbl_user', 'language_id');
        return false;
    }

}
