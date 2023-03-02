CREATE TABLE IF NOT EXISTS `#__members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `memname` varchar(255) NOT NULL DEFAULT '',
  `mememail` varchar(255) NOT NULL DEFAULT '',
  `memphone` varchar(255),
  `user_id` int NOT NULL DEFAULT 0,

  PRIMARY KEY (`id`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__members` ADD COLUMN  `access` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__members` ADD KEY `idx_access` (`access`);

ALTER TABLE `#__members` ADD COLUMN  `catid` int(11) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__members` ADD KEY `idx_catid` (`catid`);

ALTER TABLE `#__members` ADD COLUMN  `state` tinyint(3) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__members` ADD COLUMN  `published` tinyint(1) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__members` ADD COLUMN  `publish_up` datetime AFTER `alias`;

ALTER TABLE `#__members` ADD COLUMN  `publish_down` datetime AFTER `alias`;

ALTER TABLE `#__members` ADD KEY `idx_state` (`published`);

ALTER TABLE `#__members` ADD COLUMN  `ordering` int(11) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__members` ADD COLUMN  `params` text NOT NULL AFTER `alias`;

ALTER TABLE `#__members` ADD COLUMN `checked_out` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__members` ADD COLUMN `checked_out_time` datetime AFTER `alias`;

ALTER TABLE `#__members` ADD KEY `idx_checkout` (`checked_out`);

ALTER TABLE `#__members` ADD COLUMN  `featured` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT 'Set if member is featured.';

ALTER TABLE `#__members` ADD KEY `idx_featured_catid` (`featured`,`catid`);