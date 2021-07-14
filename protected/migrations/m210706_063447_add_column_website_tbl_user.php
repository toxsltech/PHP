<?php

use yii\db\Migration;

/**
 * Class m210706_063447_add_column_website_tbl_user
 */
class m210706_063447_add_column_website_tbl_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_user` ADD `website` VARCHAR(32) NULL DEFAULT NULL AFTER `language_id`;");
        $this->execute("ALTER TABLE `tbl_user` ADD `portfolio_id` INT NULL DEFAULT NULL AFTER `category_id`;");
        $this->execute("ALTER TABLE `tbl_user` ADD `opinion_id` INT NULL DEFAULT NULL AFTER `domain_id`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tbl_user', 'website');
        return false;
    }

}
