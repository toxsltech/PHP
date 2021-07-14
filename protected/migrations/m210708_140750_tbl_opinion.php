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
class m210708_140750_tbl_opinion extends Migration
{

    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_opinion` CHANGE `image_file` `image_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;");
    }

    public function safeDown()
    {
        echo "m210708_140750_tbl_opinion migrating down by doing nothing....\n";
    }
}