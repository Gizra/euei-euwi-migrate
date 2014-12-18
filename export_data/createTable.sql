DROP TABLE IF EXISTS `_gizra_node_blog_post`;

CREATE TABLE `_gizra_node_blog_post` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `uid` int(11) unsigned NOT NULL,
  `path` text DEFAULT NULL,
  `promote` int(11) NOT NULL DEFAULT '0',
  `sticky` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nid`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_gizra_node_document`;
CREATE TABLE `_gizra_node_document` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `uid` int(11) unsigned NOT NULL,
  `path` text DEFAULT NULL,
  `promote` int(11) NOT NULL DEFAULT '0',
  `sticky` int(11) NOT NULL DEFAULT '0',
  `file_path` varchar (255),
  `file_name` varchar (128),
  PRIMARY KEY (`nid`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_gizra_user`;

CREATE TABLE `_gizra_user` (
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `password` varchar(64),
  `mail` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `uid` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_gizra_node_event`;

CREATE TABLE `_gizra_node_event` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `uid` int(11) unsigned NOT NULL,
  `path` text DEFAULT NULL,
  `promote` int(11) NOT NULL DEFAULT '0',
  `sticky` int(11) NOT NULL DEFAULT '0',
  `event_start` int(10) unsigned NOT NULL DEFAULT '0',
  `event_end` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`nid`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
