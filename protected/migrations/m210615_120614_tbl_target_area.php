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
class m210615_120614_tbl_target_area extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%target_area}}');
        if (! isset($table)) {

            $this->execute("DROP TABLE IF EXISTS `tbl_target_area`;
  CREATE TABLE IF NOT EXISTS `tbl_target_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int(11) DEFAULT '0',
  `type_id` varchar(255) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
   PRIMARY KEY (`id`),
   INDEX(`title`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%target_area}}');
        if (isset($table)) {
            $this->dropTable('{{%target_area}}');
        }
    }
}