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
class m210705_140735_tbl_portfolio_detail extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%portfolio_detail}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_portfolio_detail`;
                CREATE TABLE IF NOT EXISTS `tbl_portfolio_detail` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `image_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                  `state_id` int(11) DEFAULT '0',
                  `type_id` varchar(255) DEFAULT NULL,
                  `created_on` datetime DEFAULT NULL,
                  `created_by_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  INDEX(`state_id`),
                  INDEX(`created_on`),
                  KEY `fk_portfolio_detail_created_by` (`created_by_id`),
                  CONSTRAINT `fk_portfolio_detail_created_by` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%portfolio_detail}}');
        if (isset($table)) {
            $this->dropTable('{{%portfolio_detail}}');
        }
    }
}