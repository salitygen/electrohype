CREATE TABLE IF NOT EXISTS `#__jkreview_attachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `review_id` int(11) NOT NULL,
  `data` varchar(255) NOT NULL,
  `mindata` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jkreview_reviews` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `rating` int(11) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `public_status` tinyint(1) NOT NULL,
  `public_vm_prod_id` varchar(255) NOT NULL,
  `public_vm_cat_id` varchar(255) NOT NULL,
  `source_id` int(11) NOT NULL,
  `system_user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `public_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jkreview_sources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `attr1` varchar(255) NOT NULL,
  `attr2` varchar(255) NOT NULL,
  `attr3` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
