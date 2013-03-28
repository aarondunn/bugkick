SET AUTOCOMMIT=0;
START TRANSACTION;
  ALTER TABLE  `bk_bug_changelog` ADD  `date_t` INT UNSIGNED NOT NULL AFTER  `change`;
  UPDATE `bk_bug_changelog` SET `date_t`=UNIX_TIMESTAMP(`date`);
  ALTER TABLE  `bk_bug_changelog` DROP  `date`;
  ALTER TABLE  `bk_bug_changelog` CHANGE  `date_t`  `date` INT UNSIGNED NOT NULL ,
  ADD INDEX (  `date` );
ROLLBACK;
SET AUTOCOMMIT=1;