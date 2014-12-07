DROP TABLE IF EXISTS `_gizra_migrate`;

CREATE TABLE `_gizra_migrate` (
`nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  PRIMARY KEY (`nid`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
