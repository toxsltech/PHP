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
class m210709_100743_tbl_news extends Migration
{

    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_news` CHANGE `start_time` `start_time` time DEFAULT NULL;");
        $this->execute("ALTER TABLE `tbl_news` CHANGE `end_time` `end_time` time DEFAULT NULL;");
    }

    public function safeDown()
    {
        echo "m210709_100743_tbl_news migrating down by doing nothing....\n";
    }
}