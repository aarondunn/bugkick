CREATE TABLE `bk_task` (
`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`description` TEXT NOT NULL ,
`user_id` BIGINT( 20 ) UNSIGNED NOT NULL ,
`ticket_id` BIGINT( 20 ) UNSIGNED NOT NULL ,
`date` DATETIME NOT NULL ,
`status` TINYINT NOT NULL COMMENT  '0 - ''new'', 1 - ''completed''',
INDEX (  `ticket_id` )
) ENGINE = INNODB;

ALTER TABLE  `bk_task` ADD FOREIGN KEY ( `ticket_id` ) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;