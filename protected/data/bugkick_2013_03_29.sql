-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.25-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2013-03-29 00:57:15
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table bugkick.bk_article
CREATE TABLE IF NOT EXISTS `bk_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_article: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_article` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_bug
CREATE TABLE IF NOT EXISTS `bk_bug` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `github_issue_id` bigint(20) unsigned DEFAULT NULL COMMENT 'FK(bk_github_issue)',
  `number` int(10) unsigned NOT NULL COMMENT 'The number of bug.',
  `prev_number` int(10) unsigned NOT NULL COMMENT 'Previous bug''s number',
  `next_number` int(10) unsigned NOT NULL COMMENT 'Next bug''s number',
  `prev_id` int(10) unsigned NOT NULL COMMENT 'Previous bug''s identifier',
  `next_id` int(10) unsigned NOT NULL COMMENT 'Next bug''s identifier',
  `project_id` bigint(20) unsigned NOT NULL COMMENT 'The project to which this bug (ticket) belongs.',
  `created_at` datetime DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status_id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  `duedate` date DEFAULT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `isarchive` tinyint(1) unsigned DEFAULT NULL,
  `archiving_date` datetime NOT NULL,
  `company_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL COMMENT 'id user creates bug',
  `user_id` int(11) DEFAULT NULL,
  `notified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `duplicate_number` int(10) unsigned NOT NULL,
  `priority_order` bigint(20) NOT NULL,
  `is_created_with_api` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `api_user_email` varchar(255) NOT NULL COMMENT 'Email of the user who submitted a bug using API ',
  `type` enum('Bug','Feature request','Suggestion') DEFAULT NULL COMMENT 'The type of ticket created via BugKick API',
  `user_set` text NOT NULL COMMENT 'This field stores all assigned users. Data is stored as serialized array of user IDs. It duplicates the data from bk_bug_by_user and necessary for fast work of the ticket list',
  `label_set` text NOT NULL COMMENT 'This field stores all ticket''s labels. Data is stored as serialized array of label IDs. It duplicates the data from bk_bug_by_label and necessary for fast work of the ticket list',
  PRIMARY KEY (`id`),
  KEY `bug_FI_1` (`status_id`),
  KEY `project_id` (`project_id`),
  KEY `number` (`number`),
  KEY `prev_id` (`prev_id`),
  KEY `next_id` (`next_id`),
  KEY `prev_number` (`prev_number`),
  KEY `next_number` (`next_number`),
  KEY `notified` (`notified`),
  KEY `priority_order` (`priority_order`),
  KEY `is_created_with_api` (`is_created_with_api`),
  KEY `type` (`type`),
  KEY `isarchive` (`isarchive`),
  KEY `github_issue_id` (`github_issue_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `bk_bug_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_10` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_11` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_12` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_13` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_14` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_15` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_16` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_4` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_5` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_6` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_7` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_8` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_ibfk_9` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_bug: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_bug` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_bug` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_bug_by_label
CREATE TABLE IF NOT EXISTS `bk_bug_by_label` (
  `bug_id` bigint(20) unsigned NOT NULL,
  `label_id` bigint(20) unsigned NOT NULL,
  KEY `bug_id` (`bug_id`),
  KEY `label_id` (`label_id`),
  CONSTRAINT `bk_bug_by_label_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_10` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_11` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_12` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_13` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_14` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_15` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_16` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_2` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_3` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_4` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_5` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_6` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_7` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_8` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_label_ibfk_9` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_bug_by_label: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_bug_by_label` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_bug_by_label` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_bug_by_user
CREATE TABLE IF NOT EXISTS `bk_bug_by_user` (
  `bug_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  KEY `bug_id` (`bug_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bk_bug_by_user_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_10` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_11` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_12` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_13` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_14` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_15` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_16` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_3` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_5` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_6` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_7` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_8` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_by_user_ibfk_9` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_bug_by_user: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_bug_by_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_bug_by_user` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_bug_changelog
CREATE TABLE IF NOT EXISTS `bk_bug_changelog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bug_id` bigint(20) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `change` text NOT NULL,
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bug_id` (`bug_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`),
  KEY `user_id_3` (`user_id`),
  KEY `user_id_4` (`user_id`),
  KEY `user_id_5` (`user_id`),
  KEY `user_id_6` (`user_id`),
  KEY `user_id_7` (`user_id`),
  KEY `user_id_8` (`user_id`),
  KEY `date` (`date`),
  CONSTRAINT `bk_bug_changelog_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_changelog_ibfk_2` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_changelog_ibfk_3` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_changelog_ibfk_4` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_changelog_ibfk_5` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_changelog_ibfk_6` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_changelog_ibfk_7` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_bug_changelog_ibfk_8` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_bug_changelog: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_bug_changelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_bug_changelog` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_comment
CREATE TABLE IF NOT EXISTS `bk_comment` (
  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `bug_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_FI_1` (`user_id`),
  KEY `comment_FI_2` (`bug_id`),
  KEY `date` (`created_at`),
  CONSTRAINT `bk_comment_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_comment_ibfk_2` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_comment_ibfk_3` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_comment_ibfk_4` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_comment_ibfk_5` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_comment_ibfk_6` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_comment_ibfk_7` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_comment_ibfk_8` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_comment: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_comment` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_company
CREATE TABLE IF NOT EXISTS `bk_company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `company_name` varchar(250) DEFAULT NULL,
  `company_url` varchar(250) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `account_type` tinyint(2) DEFAULT '0',
  `account_plan` varchar(100) NOT NULL,
  `company_top_logo` varchar(255) NOT NULL,
  `company_color` varchar(7) NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `show_ads` tinyint(2) NOT NULL DEFAULT '1',
  `owner_id` bigint(20) unsigned NOT NULL,
  `coupon_id` int(10) unsigned NOT NULL,
  `coupon_expires_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`company_id`),
  KEY `company_name` (`company_name`),
  KEY `api_key` (`api_key`),
  KEY `account_plan` (`account_plan`),
  KEY `owner_id` (`owner_id`),
  KEY `coupon_expires_at` (`coupon_expires_at`),
  KEY `coupon_id` (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_company: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_company` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_coupon
CREATE TABLE IF NOT EXISTS `bk_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL COMMENT 'Coupon code',
  `enabled` tinyint(3) unsigned NOT NULL,
  `period` int(11) NOT NULL COMMENT 'Defines how much time for free (in seconds)',
  PRIMARY KEY (`id`),
  KEY `code` (`code`,`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_coupon: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_coupon` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_domains_blacklist
CREATE TABLE IF NOT EXISTS `bk_domains_blacklist` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL COMMENT 'Email services that are prohibited to register on Bugkick',
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_domains_blacklist: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_domains_blacklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_domains_blacklist` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_email_preference
CREATE TABLE IF NOT EXISTS `bk_email_preference` (
  `email_preference_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`email_preference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_email_preference: ~4 rows (approximately)
/*!40000 ALTER TABLE `bk_email_preference` DISABLE KEYS */;
INSERT INTO `bk_email_preference` (`email_preference_id`, `name`) VALUES
	(1, 'New ticket'),
	(2, 'Ticket status changes'),
	(3, 'New comment'),
	(4, 'Due Date reminder');
/*!40000 ALTER TABLE `bk_email_preference` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_file
CREATE TABLE IF NOT EXISTS `bk_file` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `public_name` varchar(255) NOT NULL COMMENT 'Public name of the file for downloading',
  `user_id` bigint(20) unsigned NOT NULL COMMENT 'ID of user who uploaded ',
  `ticket_id` bigint(20) unsigned NOT NULL,
  `box_file_id` varchar(255) NOT NULL COMMENT 'Box.com file ID',
  `size` bigint(20) unsigned NOT NULL COMMENT 'Size in bytes',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`ticket_id`,`box_file_id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `bk_file_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_file: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_file` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_filter
CREATE TABLE IF NOT EXISTS `bk_filter` (
  `filter_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `filter` text NOT NULL,
  PRIMARY KEY (`filter_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bk_filter_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_filter_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_filter_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_filter_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_filter_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_filter_ibfk_6` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_filter_ibfk_7` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_filter_ibfk_8` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_filter: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_filter` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_forum
CREATE TABLE IF NOT EXISTS `bk_forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_forum: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_forum` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_forum` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_github_issue
CREATE TABLE IF NOT EXISTS `bk_github_issue` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `number` bigint(20) unsigned NOT NULL,
  `html_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_github_issue: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_github_issue` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_github_issue` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_github_user
CREATE TABLE IF NOT EXISTS `bk_github_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `login` varchar(255) NOT NULL,
  `html_url` varchar(255) NOT NULL,
  `avatar_url` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_github_user: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_github_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_github_user` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_invite
CREATE TABLE IF NOT EXISTS `bk_invite` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `invited_by_id` bigint(20) unsigned NOT NULL COMMENT 'The ID of user who made this invite.',
  `token` char(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `token` (`token`),
  KEY `invited_by_id` (`invited_by_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_invite: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_invite` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_invite` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_label
CREATE TABLE IF NOT EXISTS `bk_label` (
  `label_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `label_color` varchar(10) NOT NULL DEFAULT '#DFE2FF',
  `company_id` int(11) DEFAULT NULL,
  `pre_created` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`label_id`),
  KEY `pre_created` (`pre_created`),
  KEY `company_id` (`company_id`),
  KEY `company_id_2` (`company_id`),
  KEY `company_id_3` (`company_id`),
  KEY `company_id_4` (`company_id`),
  KEY `company_id_5` (`company_id`),
  KEY `company_id_6` (`company_id`),
  KEY `company_id_7` (`company_id`),
  KEY `company_id_8` (`company_id`),
  CONSTRAINT `bk_label_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_ibfk_4` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_ibfk_5` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_ibfk_6` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_ibfk_7` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_ibfk_8` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_label: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_label` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_label` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_label_by_project
CREATE TABLE IF NOT EXISTS `bk_label_by_project` (
  `label_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  KEY `label_id` (`label_id`,`project_id`),
  KEY `project_id` (`project_id`),
  KEY `project_id_2` (`project_id`),
  KEY `project_id_3` (`project_id`),
  KEY `project_id_4` (`project_id`),
  KEY `project_id_5` (`project_id`),
  KEY `project_id_6` (`project_id`),
  KEY `project_id_7` (`project_id`),
  KEY `project_id_8` (`project_id`),
  CONSTRAINT `bk_label_by_project_ibfk_1` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_10` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_11` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_12` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_3` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_4` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_5` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_6` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_7` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_8` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_label_by_project_ibfk_9` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_label_by_project: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_label_by_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_label_by_project` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_log
CREATE TABLE IF NOT EXISTS `bk_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '0-system, other-users',
  `action_id` varchar(255) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `success` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0-failed, 1-successfull',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_log: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_log` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_log_action
CREATE TABLE IF NOT EXISTS `bk_log_action` (
  `log_action_id` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`log_action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_log_action: ~2 rows (approximately)
/*!40000 ALTER TABLE `bk_log_action` DISABLE KEYS */;
INSERT INTO `bk_log_action` (`log_action_id`, `description`) VALUES
	('bug::addComment', 'Add new comment for bug'),
	('mail::newComment', 'Send mail bug owner in case add comment');
/*!40000 ALTER TABLE `bk_log_action` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_look_and_feel
CREATE TABLE IF NOT EXISTS `bk_look_and_feel` (
  `name` varchar(255) NOT NULL COMMENT 'The name of look-and-feel',
  `css_file` varchar(255) NOT NULL COMMENT 'Style-sheet file that contains the styles of this look-and-feel scheme',
  `img_preview` varchar(255) NOT NULL,
  PRIMARY KEY (`name`),
  KEY `css_file` (`css_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Site look and feel schemes';

-- Dumping data for table bugkick.bk_look_and_feel: ~6 rows (approximately)
/*!40000 ALTER TABLE `bk_look_and_feel` DISABLE KEYS */;
INSERT INTO `bk_look_and_feel` (`name`, `css_file`, `img_preview`) VALUES
	('Black Linen', 'body__black-Linen.css', 'black-Linen_100x100px.png'),
	('Bright Squares', 'body__bright_squares.css', 'bright_squares_100x100px.png'),
	('Cork #1', 'body__cork_1.css', 'cork_1_100x100px.png'),
	('Default', 'body__default.css', ''),
	('Old Mathematics', 'body__old_mathematics.css', 'old_mathematics_100x100px.png'),
	('Soft Wallpaper', 'body__soft_wallpaper.css', 'soft_wallpaper_100x100px.png');
/*!40000 ALTER TABLE `bk_look_and_feel` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_notification
CREATE TABLE IF NOT EXISTS `bk_notification` (
  `notification_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT 'This is a user who will see this notification(owner or assigned person)',
  `bug_id` bigint(20) unsigned DEFAULT NULL,
  `content` varchar(500) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `changer_id` int(10) unsigned NOT NULL COMMENT 'User who made change(left comment, changed a ticket, etc)',
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`,`date`),
  KEY `bug_id` (`bug_id`),
  KEY `changer_id` (`changer_id`),
  CONSTRAINT `bk_notification_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_notification_ibfk_2` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_notification_ibfk_3` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_notification_ibfk_4` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_notification_ibfk_5` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bk_notification_ibfk_6` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_notification: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_notification` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_page
CREATE TABLE IF NOT EXISTS `bk_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_label` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_page: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_page` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_post
CREATE TABLE IF NOT EXISTS `bk_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `body` text NOT NULL,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_time` (`time`),
  KEY `fk_topic` (`topic_id`),
  CONSTRAINT `fk_topic` FOREIGN KEY (`topic_id`) REFERENCES `bk_topic` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_post: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_post` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_project
CREATE TABLE IF NOT EXISTS `bk_project` (
  `project_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The unique identifier of the project',
  `company_id` int(11) NOT NULL COMMENT 'The identifier of project''s company',
  `name` varchar(255) NOT NULL COMMENT 'The project''s name',
  `url_name` varchar(255) DEFAULT NULL COMMENT 'The project name formatted for SEO URLs',
  `description` text COMMENT 'The project''s description (optional)',
  `logo` varchar(255) DEFAULT NULL COMMENT 'The name of the project''s logo-file (optional)',
  `home_page` varchar(255) DEFAULT NULL COMMENT 'The URL of project''s home page (optional)',
  `api_id` varchar(255) DEFAULT NULL,
  `api_ticket_default_assignee` bigint(20) DEFAULT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `github_user_id` int(11) unsigned DEFAULT NULL COMMENT 'FK(bk_github_user)',
  `github_repo` varchar(255) DEFAULT NULL,
  `translate_tickets` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Whether bugkick should translate the tickets and comments to GitHub Issues or not.',
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `api_id` (`api_id`),
  KEY `company_id` (`company_id`),
  KEY `name` (`name`),
  KEY `url_name` (`url_name`),
  KEY `api_ticket_default_assignee` (`api_ticket_default_assignee`),
  KEY `archived` (`archived`),
  KEY `github_repo` (`github_repo`),
  KEY `translate_tickets` (`translate_tickets`),
  CONSTRAINT `bk_project_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_project_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_project_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_project_ibfk_4` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_project_ibfk_5` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_project_ibfk_6` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_project: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_project` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_project_by_group
CREATE TABLE IF NOT EXISTS `bk_project_by_group` (
  `project_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  KEY `project_id` (`project_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_project_by_group: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_project_by_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_project_by_group` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_settings_by_project
CREATE TABLE IF NOT EXISTS `bk_settings_by_project` (
  `project_id` bigint(20) unsigned NOT NULL,
  `defaultAssignee` int(10) unsigned NOT NULL,
  `defaultLabel` int(10) unsigned NOT NULL,
  `defaultStatus` int(10) unsigned NOT NULL,
  `defaultCompany` int(10) unsigned NOT NULL,
  KEY `project_id` (`project_id`),
  CONSTRAINT `bk_settings_by_project_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_project_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_project_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_project_ibfk_4` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_project_ibfk_5` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_project_ibfk_6` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_settings_by_project: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_settings_by_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_settings_by_project` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_settings_by_user
CREATE TABLE IF NOT EXISTS `bk_settings_by_user` (
  `user_id` bigint(20) unsigned NOT NULL,
  `defaultAssignee` int(10) unsigned NOT NULL,
  `defaultLabel` int(10) unsigned NOT NULL,
  `defaultStatus` int(10) unsigned NOT NULL,
  `defaultCompany` int(10) unsigned NOT NULL,
  KEY `user_id` (`user_id`),
  CONSTRAINT `bk_settings_by_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_user_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_user_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_user_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_settings_by_user_ibfk_6` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_settings_by_user: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_settings_by_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_settings_by_user` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_site_settings
CREATE TABLE IF NOT EXISTS `bk_site_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invites_module` tinyint(1) unsigned NOT NULL COMMENT 'Defines if invites module is enabled',
  `invites_count` int(10) unsigned NOT NULL COMMENT 'Limit of invites per user',
  `invites_limit` tinyint(1) unsigned NOT NULL COMMENT 'Defines if we limit number of available invites per user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_site_settings: ~1 rows (approximately)
/*!40000 ALTER TABLE `bk_site_settings` DISABLE KEYS */;
INSERT INTO `bk_site_settings` (`id`, `invites_module`, `invites_count`, `invites_limit`) VALUES
	(1, 1, 5, 1);
/*!40000 ALTER TABLE `bk_site_settings` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_status
CREATE TABLE IF NOT EXISTS `bk_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(30) NOT NULL,
  `status_color` varchar(10) NOT NULL DEFAULT '#DFE2FF',
  `is_visible_by_default` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `company_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`status_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `bk_status_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_status_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_status_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_status: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_status` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_stripe_customer
CREATE TABLE IF NOT EXISTS `bk_stripe_customer` (
  `customer_id` varchar(255) NOT NULL COMMENT 'Stripe customer ID (PK, varchar)',
  `user_id` bigint(20) unsigned NOT NULL COMMENT 'BugKick user''s ID (FK, index bigint)',
  `company_id` bigint(20) unsigned NOT NULL COMMENT 'BugKick company''s ID (FK, unique bigint)',
  `plan_id` varchar(255) DEFAULT NULL COMMENT 'Stripe plan''s ID (index)',
  `payment_interval` int(10) unsigned NOT NULL DEFAULT '0',
  `last_payment_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `next_payment_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `is_canceled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `notified_at` bigint(20) unsigned DEFAULT NULL,
  `expires_at` bigint(20) DEFAULT NULL COMMENT 'The time of company''s subscription expiration',
  `cancel_at_period_end` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Defines if subscription will be ended at the end of period',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `company_id` (`company_id`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  KEY `payment_interval` (`payment_interval`),
  KEY `last_payment_time` (`last_payment_time`),
  KEY `is_canceled` (`is_canceled`),
  KEY `next_payment_time` (`next_payment_time`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stripe customers';

-- Dumping data for table bugkick.bk_stripe_customer: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_stripe_customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_stripe_customer` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_task
CREATE TABLE IF NOT EXISTS `bk_task` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `ticket_id` bigint(20) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0 - ''new'', 1 - ''completed''',
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `bk_task_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_task: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_task` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_tbl_migration
CREATE TABLE IF NOT EXISTS `bk_tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_tbl_migration: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_tbl_migration` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_tbl_migration` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_tmp_file
CREATE TABLE IF NOT EXISTS `bk_tmp_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The unique identifier of temporary file (PK)',
  `path` varchar(255) NOT NULL COMMENT 'The path relative to ''webroot.tmp''',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of file creation',
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`path`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Temporary files';

-- Dumping data for table bugkick.bk_tmp_file: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_tmp_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_tmp_file` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_topic
CREATE TABLE IF NOT EXISTS `bk_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `forum_id` int(11) NOT NULL,
  `topic_starter_id` int(10) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `archived` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `topic_title` (`title`),
  KEY `fk_forum` (`forum_id`),
  KEY `topic_starter_id` (`topic_starter_id`),
  KEY `archived` (`archived`),
  CONSTRAINT `fk_forum` FOREIGN KEY (`forum_id`) REFERENCES `bk_forum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_topic: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_topic` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_user
CREATE TABLE IF NOT EXISTS `bk_user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_id` varchar(255) DEFAULT NULL COMMENT 'User''s identifier at the Facebook',
  `created_at` datetime DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `encryption_algorithm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `github_auth_token` char(40) DEFAULT NULL COMMENT 'The authorization token retrieved from GitHub during OAuth authorization process.',
  `github_user_id` int(11) unsigned DEFAULT NULL COMMENT 'FK(bk_github_user)',
  `email_notify` tinyint(1) DEFAULT NULL,
  `isadmin` tinyint(1) DEFAULT NULL COMMENT 'Company admin',
  `is_global_admin` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Global Bugkick site admin',
  `profile_img` varchar(1000) DEFAULT NULL,
  `email_preference` tinyint(4) DEFAULT NULL,
  `hotkey_preference` tinyint(4) NOT NULL DEFAULT '1',
  `current_project_id` bigint(20) unsigned DEFAULT NULL COMMENT 'An identifier of last selected project (FK)',
  `look_and_feel` varchar(255) DEFAULT NULL COMMENT 'User''s look-and-feel preference',
  `randomPassword` varchar(255) DEFAULT NULL,
  `userStatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-invited,1- active,2 -rejected, 3-deleted',
  `defaultAssignee` int(11) DEFAULT NULL,
  `defaultCompany` int(11) DEFAULT NULL,
  `defaultStatus` int(11) unsigned NOT NULL,
  `defaultLabel` int(11) unsigned NOT NULL,
  `registration_token` varchar(255) DEFAULT NULL COMMENT 'Registration verification token.',
  `inviteToken` varchar(255) DEFAULT NULL,
  `invited_by_id` int(11) unsigned DEFAULT NULL,
  `resetToken` varchar(255) DEFAULT NULL,
  `tickets_per_page` int(11) DEFAULT '30',
  `ticket_update_return` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `use_wysiwyg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pro_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'pro_status  - means that all companies that the user has, were upgraded to ''Pro'' plan from admin panel',
  `forum_role` varchar(50) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `look_and_feel` (`look_and_feel`),
  KEY `facebook_id` (`facebook_id`),
  KEY `current_project_id` (`current_project_id`),
  KEY `registration_token` (`registration_token`),
  KEY `invited_by_id` (`invited_by_id`),
  KEY `github_user_id` (`github_user_id`),
  KEY `is_global_admin` (`is_global_admin`),
  KEY `pro_status` (`pro_status`),
  KEY `forum_role` (`forum_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_user: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_user` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_user_block
CREATE TABLE IF NOT EXISTS `bk_user_block` (
  `user_ip` varchar(255) NOT NULL,
  `block_to` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `count_entry` int(11) NOT NULL,
  `first_entry` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_user_block: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_user_block` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_user_block` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_user_by_company
CREATE TABLE IF NOT EXISTS `bk_user_by_company` (
  `user_id` bigint(20) unsigned NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'The user''s status in company',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Defines is the user an admin of this company.',
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `user_id_2` (`user_id`,`company_id`),
  KEY `is_admin` (`is_admin`),
  KEY `user_status` (`user_status`),
  CONSTRAINT `bk_user_by_company_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_company_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_company_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_company_ibfk_4` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_user_by_company: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_user_by_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_user_by_company` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_user_by_email_preference
CREATE TABLE IF NOT EXISTS `bk_user_by_email_preference` (
  `user_id` bigint(20) unsigned NOT NULL,
  `email_preference_id` int(10) unsigned NOT NULL,
  `state` enum('on','off') NOT NULL,
  KEY `email_preference_id` (`email_preference_id`),
  KEY `user_id` (`user_id`),
  KEY `state` (`state`),
  CONSTRAINT `bk_user_by_email_preference_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_email_preference_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_user_by_email_preference: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_user_by_email_preference` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_user_by_email_preference` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_user_by_group
CREATE TABLE IF NOT EXISTS `bk_user_by_group` (
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id_2` (`user_id`,`group_id`),
  CONSTRAINT `bk_user_by_group_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_group_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `bk_user_group` (`group_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_group_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_group_ibfk_4` FOREIGN KEY (`group_id`) REFERENCES `bk_user_group` (`group_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_user_by_group: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_user_by_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_user_by_group` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_user_by_project
CREATE TABLE IF NOT EXISTS `bk_user_by_project` (
  `user_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Defines is the user an admin of this project.',
  KEY `user_id` (`user_id`,`project_id`),
  KEY `is_admin` (`is_admin`),
  KEY `project_id` (`project_id`),
  KEY `project_id_2` (`project_id`),
  CONSTRAINT `bk_user_by_project_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_project_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_project_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_by_project_ibfk_4` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_user_by_project: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_user_by_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_user_by_project` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_user_group
CREATE TABLE IF NOT EXISTS `bk_user_group` (
  `group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `project_id` bigint(20) unsigned DEFAULT NULL COMMENT 'If project_id is empty, the group available for all projects',
  `name` varchar(255) NOT NULL,
  `color` varchar(7) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`group_id`),
  KEY `name` (`name`),
  KEY `company_id` (`company_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `bk_user_group_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  CONSTRAINT `bk_user_group_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_user_group: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_user_group` ENABLE KEYS */;


-- Dumping structure for table bugkick.bk_yii_session
CREATE TABLE IF NOT EXISTS `bk_yii_session` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `expire` (`expire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.bk_yii_session: ~0 rows (approximately)
/*!40000 ALTER TABLE `bk_yii_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `bk_yii_session` ENABLE KEYS */;


-- Dumping structure for table bugkick.tbl_migration
CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bugkick.tbl_migration: ~0 rows (approximately)
/*!40000 ALTER TABLE `tbl_migration` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_migration` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
