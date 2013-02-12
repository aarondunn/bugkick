CREATE TABLE `bk_file` (
`id` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL ,
`public_name` VARCHAR( 255 ) NOT NULL COMMENT 'Box.net name of the file for downloading',
`user_id` BIGINT( 20 ) UNSIGNED NOT NULL COMMENT  'ID of user who uploaded ',
`ticket_id` BIGINT( 20 ) UNSIGNED NOT NULL ,
`box_file_id` VARCHAR( 255 ) NOT NULL COMMENT  'Box.net file ID',
`size` BIGINT( 20 ) UNSIGNED NOT NULL COMMENT  'Size in bytes',
`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
INDEX (  `user_id` ,  `ticket_id` ,  `box_file_id` )
) ENGINE = INNODB;

ALTER TABLE  `bk_file` ADD FOREIGN KEY (  `ticket_id` ) REFERENCES `bk_bug` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
