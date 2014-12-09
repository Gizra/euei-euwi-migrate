DROP TABLE IF EXISTS `_gizra_users`;

CREATE TABLE `_gizra_users` (
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `password` varchar(64),
  `mail` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `uid` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
