







## 已更新到服务器

INSERT INTO `ims_superdesk_shop_express` (`id`, `name`, `express`, `status`, `displayorder`, `code`) VALUES
(92, '京东快递', 'jd', 1, 0, 'JH_046'),
(93, '微特派', 'weitepai', 1, 0, ''),
(94, '九曳供应链', 'jiuyescm', 1, 0, '');



CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '',
  `express` varchar(50) DEFAULT '',
  `status` tinyint(1) DEFAULT '1',
  `displayorder` tinyint(3) unsigned DEFAULT '0',
  `code` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=95 ;


INSERT INTO `ims_superdesk_shop_express` (`id`, `name`, `express`, `status`, `displayorder`, `code`) VALUES
(1, '顺丰', 'shunfeng', 1, 0, 'JH_014'),
(2, '申通', 'shentong', 1, 0, 'JH_005'),
(3, '韵达快运', 'yunda', 1, 0, 'JH_003'),
(4, '天天快递', 'tiantian', 1, 0, 'JH_004'),
(5, '圆通速递', 'yuantong', 1, 0, 'JH_002'),
(6, '中通速递', 'zhongtong', 1, 0, 'JH_006'),
(7, 'ems快递', 'ems', 1, 0, 'JH_001'),
(8, '百世汇通', 'huitongkuaidi', 1, 0, 'JH_012'),
(9, '全峰快递', 'quanfengkuaidi', 1, 0, 'JH_009'),
(10, '宅急送', 'zhaijisong', 1, 0, 'JH_007'),
(11, 'aae全球专递', 'aae', 1, 0, 'JHI_049'),
(12, '安捷快递', 'anjie', 1, 0, ''),
(13, '安信达快递', 'anxindakuaixi', 1, 0, 'JH_131'),
(14, '彪记快递', 'biaojikuaidi', 1, 0, ''),
(15, 'bht', 'bht', 1, 0, 'JHI_008'),
(16, '百福东方国际物流', 'baifudongfang', 1, 0, 'JH_062'),
(17, '中国东方（COE）', 'coe', 1, 0, 'JHI_038'),
(18, '长宇物流', 'changyuwuliu', 1, 0, ''),
(19, '大田物流', 'datianwuliu', 1, 0, 'JH_050'),
(20, '德邦物流', 'debangwuliu', 1, 0, 'JH_011'),
(21, 'dhl', 'dhl', 1, 0, 'JHI_002'),
(22, 'dpex', 'dpex', 1, 0, 'JHI_011'),
(23, 'd速快递', 'dsukuaidi', 1, 0, 'JH_049'),
(24, '递四方', 'disifang', 1, 0, 'JHI_080'),
(25, 'fedex（国外）', 'fedex', 1, 0, 'JHI_014'),
(26, '飞康达物流', 'feikangda', 1, 0, 'JH_088'),
(27, '凤凰快递', 'fenghuangkuaidi', 1, 0, ''),
(28, '飞快达', 'feikuaida', 1, 0, 'JH_151'),
(29, '国通快递', 'guotongkuaidi', 1, 0, 'JH_010'),
(30, '港中能达物流', 'ganzhongnengda', 1, 0, 'JH_033'),
(31, '广东邮政物流', 'guangdongyouzhengwuliu', 1, 0, 'JH_135'),
(32, '共速达', 'gongsuda', 1, 0, 'JH_039'),
(33, '恒路物流', 'hengluwuliu', 1, 0, 'JH_048'),
(34, '华夏龙物流', 'huaxialongwuliu', 1, 0, 'JH_129'),
(35, '海红', 'haihongwangsong', 1, 0, 'JH_132'),
(36, '海外环球', 'haiwaihuanqiu', 1, 0, 'JHI_013'),
(37, '佳怡物流', 'jiayiwuliu', 1, 0, 'JH_035'),
(38, '京广速递', 'jinguangsudikuaijian', 1, 0, 'JH_041'),
(39, '急先达', 'jixianda', 1, 0, 'JH_040'),
(40, '佳吉物流', 'jiajiwuliu', 1, 0, 'JH_030'),
(41, '加运美物流', 'jymwl', 1, 0, 'JH_054'),
(42, '金大物流', 'jindawuliu', 1, 0, 'JH_079'),
(43, '嘉里大通', 'jialidatong', 1, 0, 'JH_060'),
(44, '晋越快递', 'jykd', 1, 0, 'JHI_046'),
(45, '快捷速递', 'kuaijiesudi', 1, 0, 'JH_008'),
(46, '联邦快递（国内）', 'lianb', 1, 0, 'JH_122'),
(47, '联昊通物流', 'lianhaowuliu', 1, 0, 'JH_021'),
(48, '龙邦物流', 'longbanwuliu', 1, 0, 'JH_019'),
(49, '立即送', 'lijisong', 1, 0, 'JH_044'),
(50, '乐捷递', 'lejiedi', 1, 0, 'JH_043'),
(51, '民航快递', 'minghangkuaidi', 1, 0, 'JH_100'),
(52, '美国快递', 'meiguokuaidi', 1, 0, 'JHI_044'),
(53, '门对门', 'menduimen', 1, 0, 'JH_036'),
(54, 'OCS', 'ocs', 1, 0, 'JHI_012'),
(55, '配思货运', 'peisihuoyunkuaidi', 1, 0, ''),
(56, '全晨快递', 'quanchenkuaidi', 1, 0, 'JH_055'),
(57, '全际通物流', 'quanjitong', 1, 0, 'JH_127'),
(58, '全日通快递', 'quanritongkuaidi', 1, 0, 'JH_029'),
(59, '全一快递', 'quanyikuaidi', 1, 0, 'JH_020'),
(60, '如风达', 'rufengda', 1, 0, 'JH_017'),
(61, '三态速递', 'santaisudi', 1, 0, 'JH_065'),
(62, '盛辉物流', 'shenghuiwuliu', 1, 0, 'JH_066'),
(63, '速尔物流', 'sue', 1, 0, 'JH_016'),
(64, '盛丰物流', 'shengfeng', 1, 0, 'JH_082'),
(65, '赛澳递', 'saiaodi', 1, 0, 'JH_042'),
(66, '天地华宇', 'tiandihuayu', 1, 0, 'JH_018'),
(67, 'tnt', 'tnt', 1, 0, 'JHI_003'),
(68, 'ups', 'ups', 1, 0, 'JHI_004'),
(69, '万家物流', 'wanjiawuliu', 1, 0, ''),
(70, '文捷航空速递', 'wenjiesudi', 1, 0, ''),
(71, '伍圆', 'wuyuan', 1, 0, ''),
(72, '万象物流', 'wxwl', 1, 0, 'JH_115'),
(73, '新邦物流', 'xinbangwuliu', 1, 0, 'JH_022'),
(74, '信丰物流', 'xinfengwuliu', 1, 0, 'JH_023'),
(75, '亚风速递', 'yafengsudi', 1, 0, 'JH_075'),
(76, '一邦速递', 'yibangwuliu', 1, 0, 'JH_064'),
(77, '优速物流', 'youshuwuliu', 1, 0, 'JH_013'),
(78, '邮政快递包裹', 'youzhengguonei', 1, 0, 'JH_077'),
(79, '邮政国际包裹挂号信', 'youzhengguoji', 1, 0, ''),
(80, '远成物流', 'yuanchengwuliu', 1, 0, 'JH_024'),
(81, '源伟丰快递', 'yuanweifeng', 1, 0, 'JH_141'),
(82, '元智捷诚快递', 'yuanzhijiecheng', 1, 0, 'JH_126'),
(83, '运通快递', 'yuntongkuaidi', 1, 0, 'JH_145'),
(84, '越丰物流', 'yuefengwuliu', 1, 0, 'JH_068'),
(85, '源安达', 'yad', 1, 0, 'JH_067'),
(86, '银捷速递', 'yinjiesudi', 1, 0, 'JH_148'),
(87, '中铁快运', 'zhongtiekuaiyun', 1, 0, 'JH_015'),
(88, '中邮物流', 'zhongyouwuliu', 1, 0, 'JH_027'),
(89, '忠信达', 'zhongxinda', 1, 0, 'JH_086'),
(90, '芝麻开门', 'zhimakaimen', 1, 0, 'JH_026'),
(91, '安能物流', 'annengwuliu', 1, 0, 'JH_059'),
(92, '京东快递', 'jd', 1, 0, 'JH_046'),
(93, '微特派', 'weitepai', 1, 0, ''),
(94, '九曳供应链', 'jiuyescm', 1, 0, '');