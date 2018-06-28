

#CREATE TABLE IF NOT EXISTS `tks_agenda_recurring` (
#  `id` int(11) NOT NULL AUTO_INCREMENT,
#  `rid` int(11) NOT NULL,
#  `rstart` datetime NOT NULL,
#  `rend` datetime NOT NULL,
#  PRIMARY KEY (`id`)
#) ENGINE=InnoDB AUTO_INCREMENT=2419 DEFAULT CHARSET=latin1;



#CREATE TABLE IF NOT EXISTS `tks_agenda_items` (
#  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
#  `alias` varchar(255) NOT NULL,
#  `asset_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
#  `catid` int(11) NOT NULL,
#  `ordering` int(11) NOT NULL,
#  `state` tinyint(1) NOT NULL,
#  `checked_out` int(11) NOT NULL,
#  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
#  `created_by` int(11) NOT NULL,
#  `start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
#  `end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
#  `recurring` varchar(255) NOT NULL,
#  `recur_type` varchar(255) NOT NULL,
#  `end_recur` datetime NOT NULL,
#  `reason` varchar(1000) NOT NULL,
#  `recurring_id` int NOT NULL,
#  CONSTRAINT `fk_agenda_recur` FOREIGN KEY (recurring_id) REFERENCES `tks_agenda_recurring`(id),
#  PRIMARY KEY (`id`)
#) ENGINE=InnoDB DEFAULT CHARSET=utf8;


