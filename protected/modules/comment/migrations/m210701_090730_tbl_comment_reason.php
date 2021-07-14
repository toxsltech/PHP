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
class m210701_090730_tbl_comment_reason extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%comment_reason}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_comment_reason`;
CREATE TABLE IF NOT EXISTS `tbl_comment_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT '1',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`comment`),
  INDEX(`state_id`),
  INDEX(`created_on`),
  KEY `fk_comment_reason_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_comment_reason_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%comment_reason}}');
        if (isset($table)) {
            $this->dropTable('{{%comment-reason}}');
        }
    }
}