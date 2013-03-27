SET AUTOCOMMIT=0;
START TRANSACTION;
  ALTER TABLE  `bk_comment` ADD  `date` INT UNSIGNED NOT NULL AFTER  `comment_id` ,
  ADD INDEX (  `date` );
  UPDATE `bk_comment` SET `date`=UNIX_TIMESTAMP(`created_at`);
  ALTER TABLE  `bk_comment` DROP  `created_at`;
  ALTER TABLE  `bk_comment` CHANGE  `date`  `created_at` INT UNSIGNED NOT NULL;
ROLLBACK;
SET AUTOCOMMIT=1;