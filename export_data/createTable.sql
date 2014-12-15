DROP TABLE IF EXISTS `_gizra_node_blog_post`;

CREATE TABLE `_gizra_node_blog_post` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `uid` int(11) unsigned NOT NULL,
  `path` text DEFAULT NULL,
  PRIMARY KEY (`nid`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_gizra_user_user`;

CREATE TABLE `_gizra_user_user` (
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `password` varchar(64),
  `mail` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `uid` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_gizra_node_document`;
CREATE TABLE `_gizra_node_document` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `uid` int(11) unsigned NOT NULL,
  `path` text DEFAULT NULL,
  `file_path` varchar (255),
  `file_name` varchar (128),
  PRIMARY KEY (`nid`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
