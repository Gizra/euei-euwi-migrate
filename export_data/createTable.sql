-- This file contain sql scheme for exported tables.

DROP TABLE IF EXISTS `_gizra_node_blog_post`;

CREATE TABLE `_gizra_node_blog_post` (
  `unique_id` varchar (64) NOT NULL,
  `nid` int(11) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `teaser` text,
  `uid` varchar(254) NOT NULL,
  `path` text DEFAULT NULL,
  `promote` int(11) NOT NULL DEFAULT '0',
  `sticky` int(11) NOT NULL DEFAULT '0',
  `gid` varchar(254) NOT NULL DEFAULT '0',
  `tags` text DEFAULT NULL,
  `taxonomy` text DEFAULT NULL,
  `created` int(11) NOT NULL DEFAULT '0',
  `changed` int(11) NOT NULL DEFAULT '0',
  `counter` int(11) NOT NULL DEFAULT '0',
  `ref_documents` VARCHAR (254) DEFAULT NULL,
  PRIMARY KEY (`unique_id`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_gizra_node_document`;
CREATE TABLE `_gizra_node_document` (
  `unique_id` varchar (64) NOT NULL,
  `nid` int(11) unsigned,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `teaser` text,
  `uid` varchar(254) NOT NULL,
  `path` text DEFAULT NULL,
  `promote` int(11) NOT NULL DEFAULT '0',
  `sticky` int(11) NOT NULL DEFAULT '0',
  `gid` varchar(254) NOT NULL DEFAULT '0',
  `tags` text DEFAULT NULL,
  `file_path` varchar (255),
  `file_name` varchar (128),
  `taxonomy` text DEFAULT NULL,
  `created` int(11) NOT NULL DEFAULT '0',
  `changed` int(11) NOT NULL DEFAULT '0',
  `counter` int(11) NOT NULL DEFAULT '0',
  `ref_documents` VARCHAR (254) DEFAULT NULL,
  PRIMARY KEY (`unique_id`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_gizra_user`;

CREATE TABLE `_gizra_user` (
  `unique_id` varchar (64) NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `password` varchar(64),
  `mail` varchar(64) DEFAULT NULL,
  `first_name` varchar (254),
  `last_name` varchar (254),
  `picture_path` varchar (254) DEFAULT NULL,
  `organization` varchar (254),
  `organization_category` varchar (254),
  `about_me` text DEFAULT NULL,
  `taxonomy` text DEFAULT NULL,
  `country` varchar (254),
  `created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`unique_id`),
  KEY `uid` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_gizra_comment`;

CREATE TABLE IF NOT EXISTS `_gizra_comment` (
  `unique_id` varchar (64) NOT NULL,
  `cid` int(11) NOT NULL,
  `pid` varchar (64) NOT NULL DEFAULT '0',
  `nid` varchar (64) NOT NULL DEFAULT '0',
  `uid` varchar (64) NOT NULL DEFAULT '0',
  `subject` varchar(64) NOT NULL DEFAULT '',
  `comment` longtext NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(64) DEFAULT NULL,
  `mail` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`unique_id`),
  KEY `lid` (`nid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_gizra_node_event`;

CREATE TABLE `_gizra_node_event` (
  `unique_id` varchar (64) NOT NULL,
  `nid` int(11) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `teaser` text,
  `uid` varchar(254) NOT NULL,
  `path` text DEFAULT NULL,
  `promote` int(11) NOT NULL DEFAULT '0',
  `sticky` int(11) NOT NULL DEFAULT '0',
  `gid` varchar(254) NOT NULL DEFAULT '0',
  `tags` text DEFAULT NULL,
  `event_start` int(10) unsigned NOT NULL DEFAULT '0',
  `event_end` int(10) unsigned NOT NULL DEFAULT '0',
  `taxonomy` text DEFAULT NULL,
  `created` int(11) NOT NULL DEFAULT '0',
  `changed` int(11) NOT NULL DEFAULT '0',
  `counter` int(11) NOT NULL DEFAULT '0',
  `ref_documents` VARCHAR (254) DEFAULT NULL,
  PRIMARY KEY (`unique_id`),
  KEY `nid` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `_gizra_og_membership`;

CREATE TABLE IF NOT EXISTS `_gizra_og_membership` (
  `unique_id` varchar (64) NOT NULL,
  `nid` varchar(64) NOT NULL DEFAULT '0',
  `og_role` int(1) NOT NULL DEFAULT '0',
  `is_active` int(1) NOT NULL DEFAULT '0',
  `is_admin` int(1) NOT NULL DEFAULT '0',
  `uid` varchar(64) NOT NULL DEFAULT '0',
  `created` int(11) DEFAULT '0',
  `changed` int(11) DEFAULT '0',
  PRIMARY KEY (`unique_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

