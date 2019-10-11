# 已更新到 福利内购
# 已更新到 企业采购

-- 楼层表
CREATE TABLE `ims_superdesk_shop_pc_floor_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `category_id` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `updatetime` int(10) DEFAULT '0',
  `enabled` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- 表的结构 `ims_superdesk_shop_pc_adv`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_pc_adv` (
  `id` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned NOT NULL,
  `advname` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT '',
  `src` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT '',
  `enabled` tinyint(3) unsigned NOT NULL,
  `link` varchar(255) DEFAULT '',
  `width` int(11) unsigned NOT NULL,
  `height` int(11) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_shop_pc_adv`
--

INSERT INTO `ims_superdesk_shop_pc_adv` (`id`, `uniacid`, `advname`, `title`, `src`, `alt`, `enabled`, `link`, `width`, `height`) VALUES
(23, 16, '首页广告2号位', 'main_600_200', '', '', 1, '', 600, 200),
(22, 16, '首页广告1号位', 'main_600_200', '', '', 1, '', 600, 200);

--
-- Indexes for table `ims_superdesk_shop_pc_adv`
--
ALTER TABLE `ims_superdesk_shop_pc_adv`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `ims_superdesk_shop_pc_adv`
--
ALTER TABLE `ims_superdesk_shop_pc_adv`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;



-- 清理掉原先的那些PC端广告
delete from `ims_superdesk_shop_pc_adv` where 1;

INSERT INTO `ims_superdesk_shop_pc_adv` (`uniacid`, `advname`, `title`, `src`, `alt`, `enabled`, `link`, `width`, `height`)
VALUES
 (16, '首页广告1号位', 'main_600_200', '', '', 1, '', 600, 200)
,(16, '首页广告2号位', 'main_600_200', '', '', 1, '', 600, 200)
,(17, '首页广告1号位', 'main_600_200', '', '', 1, '', 600, 200)
,(17, '首页广告2号位', 'main_600_200', '', '', 1, '', 600, 200);


-- PC端.菜单栏 添加链接类型
ALTER TABLE `ims_superdesk_shop_pc_menu`
ADD `link_type` tinyint(3) DEFAULT '1' COMMENT '1:自定义页面,2:内部链接,3:外部链接' AFTER `createtime`;