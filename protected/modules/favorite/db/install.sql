-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- -------------------------------------------
-- TABLE `tbl_favorite_item`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_favorite_item`;
CREATE TABLE IF NOT EXISTS `tbl_favorite_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `model_type` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`project_id`),
  INDEX(`model_type`),
  INDEX(`model_id`),
  INDEX(`state_id`),
  INDEX(`created_by_id`),
  KEY `fk_favorite_item_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_favorite_item_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- -------------------------------------------
-- TABLE `tbl_favorite_quick_link`
-- ------------------------------------------- 
DROP TABLE IF EXISTS `tbl_favorite_quick_link`;
CREATE TABLE IF NOT EXISTS `tbl_favorite_quick_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `explore_option` text COLLATE utf8_unicode_ci,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `state_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`title`),
  INDEX(`state_id`),
  INDEX(`created_by_id`),
  KEY `fk_favorite_quick_link_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_favorite_quick_link_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
