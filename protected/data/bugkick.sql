-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
-- 2013.02.09

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- `bugkick`
--

-- --------------------------------------------------------

--
-- `bk_article`
--

CREATE TABLE IF NOT EXISTS `bk_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- `bk_bug`
--

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
  `type` enum('Bug','Feature request','Suggestion') DEFAULT NULL COMMENT 'The type of ticket created via BugKick API',
  `user_set` text NOT NULL COMMENT 'This field stores all assigned users. Data is stored as serialized array of users ID. It duplicates the data from bk_bug_by_user and neccessary for fast work of the ticket list',
  `label_set` text NOT NULL COMMENT 'This field stores all ticket''s labels. Data is stored as serialized array of labels ID. It duplicates the data from bk_bug_by_label and neccessary for fast work of the ticket list',
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
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=433 ;

-- --------------------------------------------------------

--
-- `bk_bug_by_label`
--

CREATE TABLE IF NOT EXISTS `bk_bug_by_label` (
  `bug_id` bigint(20) unsigned NOT NULL,
  `label_id` bigint(20) unsigned NOT NULL,
  KEY `bug_id` (`bug_id`),
  KEY `label_id` (`label_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_bug_by_user`
--

CREATE TABLE IF NOT EXISTS `bk_bug_by_user` (
  `bug_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  KEY `bug_id` (`bug_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_bug_changelog`
--

CREATE TABLE IF NOT EXISTS `bk_bug_changelog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bug_id` bigint(20) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `change` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bug_id` (`bug_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=692 ;

-- --------------------------------------------------------

--
-- `bk_comment`
--

CREATE TABLE IF NOT EXISTS `bk_comment` (
  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `message` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `bug_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_FI_1` (`user_id`),
  KEY `comment_FI_2` (`bug_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=537 ;

-- --------------------------------------------------------

--
-- `bk_company`
--

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
  PRIMARY KEY (`company_id`),
  UNIQUE KEY `api_key` (`api_key`),
  KEY `company_name` (`company_name`),
  KEY `account_plan` (`account_plan`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- `bk_email_preference`
--

CREATE TABLE IF NOT EXISTS `bk_email_preference` (
  `email_preference_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`email_preference_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- `bk_filter`
--

CREATE TABLE IF NOT EXISTS `bk_filter` (
  `filter_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `filter` text NOT NULL,
  PRIMARY KEY (`filter_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- `bk_github_issue`
--

CREATE TABLE IF NOT EXISTS `bk_github_issue` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `number` bigint(20) unsigned NOT NULL,
  `html_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- `bk_github_user`
--

CREATE TABLE IF NOT EXISTS `bk_github_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `login` varchar(255) NOT NULL,
  `html_url` varchar(255) NOT NULL,
  `avatar_url` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- `bk_invite`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- `bk_label`
--

CREATE TABLE IF NOT EXISTS `bk_label` (
  `label_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `label_color` varchar(10) NOT NULL DEFAULT '#DFE2FF',
  `company_id` int(11) DEFAULT NULL,
  `pre_created` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`label_id`),
  KEY `pre_created` (`pre_created`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=165 ;

-- --------------------------------------------------------

--
-- `bk_label_by_project`
--

CREATE TABLE IF NOT EXISTS `bk_label_by_project` (
  `label_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  KEY `label_id` (`label_id`,`project_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_log`
--

CREATE TABLE IF NOT EXISTS `bk_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '0-system, other-users',
  `action_id` varchar(255) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `success` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0-failed, 1-successfull',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=913 ;

-- --------------------------------------------------------

--
-- `bk_log_action`
--

CREATE TABLE IF NOT EXISTS `bk_log_action` (
  `log_action_id` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`log_action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_look_and_feel`
--

CREATE TABLE IF NOT EXISTS `bk_look_and_feel` (
  `name` varchar(255) NOT NULL COMMENT 'The name of look-and-feel',
  `css_file` varchar(255) NOT NULL COMMENT 'Style-sheet file that contains the styles of this look-and-feel scheme',
  `img_preview` varchar(255) NOT NULL,
  PRIMARY KEY (`name`),
  KEY `css_file` (`css_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Site look and feel schemes';

-- --------------------------------------------------------

--
-- `bk_notification`
--

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
  KEY `changer_id` (`changer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=709 ;

-- --------------------------------------------------------

--
-- `bk_page`
--

CREATE TABLE IF NOT EXISTS `bk_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_label` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- `bk_project`
--

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
  KEY `translate_tickets` (`translate_tickets`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- `bk_project_by_group`
--

CREATE TABLE IF NOT EXISTS `bk_project_by_group` (
  `project_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  KEY `project_id` (`project_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_settings_by_project`
--

CREATE TABLE IF NOT EXISTS `bk_settings_by_project` (
  `project_id` bigint(20) unsigned NOT NULL,
  `defaultAssignee` int(10) unsigned NOT NULL,
  `defaultLabel` int(10) unsigned NOT NULL,
  `defaultStatus` int(10) unsigned NOT NULL,
  `defaultCompany` int(10) unsigned NOT NULL,
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_settings_by_user`
--

CREATE TABLE IF NOT EXISTS `bk_settings_by_user` (
  `user_id` bigint(20) unsigned NOT NULL,
  `defaultAssignee` int(10) unsigned NOT NULL,
  `defaultLabel` int(10) unsigned NOT NULL,
  `defaultStatus` int(10) unsigned NOT NULL,
  `defaultCompany` int(10) unsigned NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_status`
--

CREATE TABLE IF NOT EXISTS `bk_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(30) NOT NULL,
  `status_color` varchar(10) NOT NULL DEFAULT '#DFE2FF',
  `is_visible_by_default` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `company_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`status_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- `bk_stripe_customer`
--

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

-- --------------------------------------------------------

--
-- `bk_tbl_migration`
--

CREATE TABLE IF NOT EXISTS `bk_tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_tmp_file`
--

CREATE TABLE IF NOT EXISTS `bk_tmp_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The unique identifier of temporary file (PK)',
  `path` varchar(255) NOT NULL COMMENT 'The path relative to ''webroot.tmp''',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time of file creation',
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`path`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Temporary files' AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- `bk_user`
--

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
  `pro_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'pro_status  - means that every company that the user has, will have ''Pro'' plan',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `look_and_feel` (`look_and_feel`),
  KEY `facebook_id` (`facebook_id`),
  KEY `current_project_id` (`current_project_id`),
  KEY `registration_token` (`registration_token`),
  KEY `invited_by_id` (`invited_by_id`),
  KEY `github_user_id` (`github_user_id`),
  KEY `is_global_admin` (`is_global_admin`),
  KEY `pro_status` (`pro_status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=128 ;

-- --------------------------------------------------------

--
-- `bk_user_by_company`
--

CREATE TABLE IF NOT EXISTS `bk_user_by_company` (
  `user_id` bigint(20) unsigned NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'The user''s status in company',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Defines is the user an admin of this company.',
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `user_id_2` (`user_id`,`company_id`),
  KEY `is_admin` (`is_admin`),
  KEY `user_status` (`user_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_user_by_email_preference`
--

CREATE TABLE IF NOT EXISTS `bk_user_by_email_preference` (
  `user_id` bigint(20) unsigned NOT NULL,
  `email_preference_id` int(10) unsigned NOT NULL,
  `state` enum('on','off') NOT NULL,
  KEY `email_preference_id` (`email_preference_id`),
  KEY `user_id` (`user_id`),
  KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_user_by_group`
--

CREATE TABLE IF NOT EXISTS `bk_user_by_group` (
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id_2` (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_user_by_project`
--

CREATE TABLE IF NOT EXISTS `bk_user_by_project` (
  `user_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Defines is the user an admin of this project.',
  KEY `user_id` (`user_id`,`project_id`),
  KEY `is_admin` (`is_admin`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `bk_user_group`
--

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
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- `bk_yii_session`
--

CREATE TABLE IF NOT EXISTS `bk_yii_session` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `expire` (`expire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- `bk_bug`
--
ALTER TABLE `bk_bug`
  ADD CONSTRAINT `bk_bug_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bk_bug_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE;

--
-- `bk_bug_by_label`
--
ALTER TABLE `bk_bug_by_label`
  ADD CONSTRAINT `bk_bug_by_label_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bk_bug_by_label_ibfk_2` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE;

--
-- `bk_bug_by_user`
--
ALTER TABLE `bk_bug_by_user`
  ADD CONSTRAINT `bk_bug_by_user_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bk_bug_by_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE;

--
-- `bk_bug_changelog`
--
ALTER TABLE `bk_bug_changelog`
  ADD CONSTRAINT `bk_bug_changelog_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE;

--
-- `bk_comment`
--
ALTER TABLE `bk_comment`
  ADD CONSTRAINT `bk_comment_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE;

--
-- `bk_filter`
--
ALTER TABLE `bk_filter`
  ADD CONSTRAINT `bk_filter_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE;

--
-- `bk_label`
--
ALTER TABLE `bk_label`
  ADD CONSTRAINT `bk_label_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE;

--
-- `bk_label_by_project`
--
ALTER TABLE `bk_label_by_project`
  ADD CONSTRAINT `bk_label_by_project_ibfk_1` FOREIGN KEY (`label_id`) REFERENCES `bk_label` (`label_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bk_label_by_project_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE;

--
-- `bk_notification`
--
ALTER TABLE `bk_notification`
  ADD CONSTRAINT `bk_notification_ibfk_1` FOREIGN KEY (`bug_id`) REFERENCES `bk_bug` (`id`) ON DELETE CASCADE;

--
-- `bk_project`
--
ALTER TABLE `bk_project`
  ADD CONSTRAINT `bk_project_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE;

--
-- `bk_settings_by_project`
--
ALTER TABLE `bk_settings_by_project`
  ADD CONSTRAINT `bk_settings_by_project_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE;

--
-- `bk_settings_by_user`
--
ALTER TABLE `bk_settings_by_user`
  ADD CONSTRAINT `bk_settings_by_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE;

--
-- `bk_status`
--
ALTER TABLE `bk_status`
  ADD CONSTRAINT `bk_status_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE;

--
-- `bk_user_by_company`
--
ALTER TABLE `bk_user_by_company`
  ADD CONSTRAINT `bk_user_by_company_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bk_user_by_company_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE;

--
-- `bk_user_by_email_preference`
--
ALTER TABLE `bk_user_by_email_preference`
  ADD CONSTRAINT `bk_user_by_email_preference_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE;

--
-- `bk_user_by_group`
--
ALTER TABLE `bk_user_by_group`
  ADD CONSTRAINT `bk_user_by_group_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bk_user_by_group_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `bk_user_group` (`group_id`) ON DELETE CASCADE;

--
-- `bk_user_by_project`
--
ALTER TABLE `bk_user_by_project`
  ADD CONSTRAINT `bk_user_by_project_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bk_user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bk_user_by_project_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `bk_project` (`project_id`) ON DELETE CASCADE;

--
-- `bk_user_group`
--
ALTER TABLE `bk_user_group`
  ADD CONSTRAINT `bk_user_group_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `bk_company` (`company_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
