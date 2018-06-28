CREATE TABLE IF NOT EXISTS `tks_agenda_select` (
    `eid` 	int(10) NOT NULL,
    `description` 	text NOT NULL,
    `item` 	varchar(25) NOT NULL,
     PRIMARY KEY (`eid`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tks_agenda_data` (
    `eid` 	int(10) NOT NULL,
    `select_id`	int(10) NOT NULL,
    `textdata` 	varchar(255) NOT NULL,
    PRIMARY KEY (`eid`),
    CONSTRAINT fk_agenda_select_data FOREIGN KEY (`select_id`) REFERENCES tks_agenda_select(`eid`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;


INSERT INTO `tks_agenda_select` (`eid`, `description`, `item`) VALUES (1, 'Eerste test geselecteerd',  'Eerste test item');
INSERT INTO `tks_agenda_select` (`eid`,`description`, `item`) VALUES (2, 'Tweede test geselecteerd', 'Tweede test item');
INSERT INTO `tks_agenda_select` (`eid`,`description`, `item`) VALUES (3, 'Derde test geselecteerd',  'Derde test item');

INSERT INTO `tks_agenda_data` (`eid`,`select_id`,`textdata`)  VALUES (1,1,  'Deze test data hoort bij het eerste item');
INSERT INTO `tks_agenda_data` (`eid`,`select_id`,`textdata`)  VALUES (2,1, 'Deze test data hoort ook bij het eerste item');
INSERT INTO `tks_agenda_data`(`eid`,`select_id`,`textdata`)  VALUES (3,1,  'Deze test data hoort ook al bij het eerste item ');

INSERT INTO `tks_agenda_data` (`eid`,`select_id`,`textdata`)  VALUES (4,2,  'Deze test data hoort bij het tweede item');
INSERT INTO `tks_agenda_data` (`eid`,`select_id`,`textdata`)  VALUES (5,2, 'Deze test data hoort ook bij het tweede item');
INSERT INTO `tks_agenda_data`(`eid`,`select_id`,`textdata`)  VALUES (6,2,  'Deze test data hoort ook al bij het tweede item ');

INSERT INTO `tks_agenda_data` (`eid`,`select_id`,`textdata`)  VALUES (7,3,  'Deze test data hoort bij het derde item');
INSERT INTO `tks_agenda_data` (`eid`,`select_id`,`textdata`)  VALUES (8, 3, 'Deze test data hoort ook bij het derde item');
INSERT INTO `tks_agenda_data`(`eid`,`select_id`,`textdata`)  VALUES (9,3,  'Deze test data hoort ook al bij het derde item ');
INSERT INTO `tks_agenda_data`(`eid`,`select_id`,`textdata`)  VALUES (10,3,  'Deze test data hoort ook al bij het derde item - 2');

