SELECT * FROM `ims_superdesk_shop_member_message_template_default`

0

SELECT * FROM `ims_superdesk_shop_member_message_template_type`

29

SELECT * FROM `ims_superdesk_shop_member_message_template`

0

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_member_message_template` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `template_id` varchar(255) DEFAULT '',
  `first` text NOT NULL,
  `firstcolor` varchar(255) DEFAULT '',
  `data` text NOT NULL,
  `remark` text NOT NULL,
  `remarkcolor` varchar(255) DEFAULT '',
  `url` varchar(255) NOT NULL,
  `createtime` int(11) DEFAULT '0',
  `sendtimes` int(11) DEFAULT '0',
  `sendcount` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_shop_member_message_template_default`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_member_message_template_default` (
  `id` int(11) NOT NULL,
  `typecode` varchar(255) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `templateid` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_shop_member_message_template_type`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_member_message_template_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `typecode` varchar(255) DEFAULT NULL,
  `templatecode` varchar(255) DEFAULT NULL,
  `templateid` varchar(255) DEFAULT NULL,
  `templatename` varchar(255) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `typegroup` varchar(255) DEFAULT '',
  `groupname` varchar(255) DEFAULT '',
  `showtotaladd` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;