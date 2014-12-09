DROP TABLE IF EXISTS `_gizra_blog_post`;

CREATE TABLE `_gizra_blog_post` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `uid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`nid`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
