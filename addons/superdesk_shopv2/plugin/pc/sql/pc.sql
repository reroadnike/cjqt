

--
-- 表的结构 `ims_superdesk_shop_pc_link`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_pc_link` (
  `id` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned NOT NULL,
  `linkname` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_shop_pc_menu`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_pc_menu` (
  `id` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned NOT NULL,
  `type` int(11) unsigned DEFAULT '0',
  `displayorder` int(11) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `enabled` tinyint(3) unsigned DEFAULT '1',
  `createtime` int(11) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_shop_pc_slide`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_pc_slide` (
  `id` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned DEFAULT '0',
  `type` int(11) unsigned DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `backcolor` varchar(255) DEFAULT NULL,
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `shopid` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_shop_pc_link`
--
ALTER TABLE `ims_superdesk_shop_pc_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_shop_pc_menu`
--
ALTER TABLE `ims_superdesk_shop_pc_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_shop_pc_slide`
--
ALTER TABLE `ims_superdesk_shop_pc_slide`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uniacid` (`uniacid`) USING BTREE,
  ADD KEY `idx_enabled` (`enabled`) USING BTREE,
  ADD KEY `idx_displayorder` (`displayorder`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_shop_pc_link`
--
ALTER TABLE `ims_superdesk_shop_pc_link`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_shop_pc_menu`
--
ALTER TABLE `ims_superdesk_shop_pc_menu`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_shop_pc_slide`
--
ALTER TABLE `ims_superdesk_shop_pc_slide`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
