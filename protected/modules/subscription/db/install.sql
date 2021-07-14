-- -------------------------------------------

SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

SET AUTOCOMMIT=0;
START TRANSACTION;
-- -------------------------------------------
-- -------------------------------------------
-- START BACKUP
-- -------------------------------------------
-- -------------------------------------------
-- TABLE `tbl_subscription_plan`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_subscription_plan`;
CREATE TABLE IF NOT EXISTS `tbl_subscription_plan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `validity` int NOT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `state_id` int DEFAULT '0',
  `type_id` int NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_subscription_plan_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_subscription_plan_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  KEY `fk_subscription_plan_user_id` (`user_id`),
  CONSTRAINT `fk_subscription_plan_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_subscription_billing`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_subscription_billing`;
CREATE TABLE IF NOT EXISTS `tbl_subscription_billing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subscription_id` int NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `state_id` int NOT NULL,
  `type_id` int DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_subscription_buy_subscription_id` (`subscription_id`),
  KEY `fk_subscription_buy_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_subscription_buy_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  CONSTRAINT `fk_subscription_buy_subscription_id` FOREIGN KEY (`subscription_id`) REFERENCES `tbl_subscription_plan` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

COMMIT;
-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
