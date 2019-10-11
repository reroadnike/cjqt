

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_shop_version`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_version` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` tinyint(3) NOT NULL DEFAULT '0',
  `uniacid` int(11) NOT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_shop_version`
--

INSERT INTO `ims_superdesk_shop_version` (`id`, `uid`, `type`, `uniacid`, `version`) VALUES
(1, 1, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_shop_version`
--
ALTER TABLE `ims_superdesk_shop_version`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid` (`uid`) USING BTREE,
  ADD KEY `idx_version` (`version`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_shop_version`
--
ALTER TABLE `ims_superdesk_shop_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

