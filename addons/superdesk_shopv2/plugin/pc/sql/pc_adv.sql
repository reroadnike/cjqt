

--
-- 表的结构 `ims_superdesk_shop_pc_adv`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_pc_adv` (
  `id` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned NOT NULL,
  `advname` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `src` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `enabled` tinyint(3) unsigned NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `width` int(11) unsigned NOT NULL,
  `height` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_shop_pc_adv`
--
ALTER TABLE `ims_superdesk_shop_pc_adv`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_shop_pc_adv`
--
ALTER TABLE `ims_superdesk_shop_pc_adv`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;

