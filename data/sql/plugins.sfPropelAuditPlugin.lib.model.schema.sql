
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- audit
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `audit`;


CREATE TABLE `audit`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_type` CHAR(1),
	`user_id` INTEGER,
	`remote_ip_address` VARCHAR(255),
	`object` VARCHAR(255),
	`object_key` VARCHAR(255),
	`object_changes` TEXT,
	`query` TEXT,
	`type` VARCHAR(255),
	`created_at` DATETIME,
	PRIMARY KEY (`id`)
)Type=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
