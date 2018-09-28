
CREATE TABLE `#__tks_agenda_download` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `file` text NOT NULL,
  `description` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__tks_agenda_newsitems` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `newscatid` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `#__tks_agenda_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `asset_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recurring` varchar(255) NOT NULL,
  `recur_type` varchar(255) NOT NULL,
  `end_recur` datetime NOT NULL,
  `reason` varchar(1000) NOT NULL,
  `recurring_id` int NOT NULL,
  /*CONSTRAINT `fk_agenda_recur` FOREIGN KEY (recurring_id) REFERENCES `#__tks_agenda_recurring`(id),*/
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__tks_agenda_recurring` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `rstart` datetime NOT NULL,
  `rend` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=536870912 DEFAULT CHARSET=latin1;


ALTER TABLE `#__tks_agenda_recurring`
    ADD CONSTRAINT `fk_item_recur` FOREIGN KEY (`rid`) REFERENCES `#__tks_agenda_items`(`id`) ON DELETE CASCADE;










