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
 *   php console.php module/migrate 
 */
class m210105_200153_update_table_tbl_subscription_billing extends Migration{
    public function safeUp()
	{
                                    $this->execute("ALTER TABLE `tbl_subscription_billing` CHANGE `created_on` `created_on` DATETIME NULL DEFAULT NULL;");
                                }
                
	public function safeDown()
	{

                $this->execute("ALTER TABLE `tbl_subscription_billing` CHANGE `created_on` `created_on` DATETIME NULL DEFAULT NULL;");

	}
}