
CREATE TABLE IF NOT EXISTS `ims_superdesk_core_build` (
  `id` int(11) NOT NULL COMMENT '主键id',
  `name` varchar(20) DEFAULT NULL COMMENT '名称',
  `organizationId` int(11) DEFAULT NULL COMMENT '学校id',
  `vip` varchar(10) DEFAULT NULL COMMENT 'vip标识：0 否，1 是',
  `remark` varchar(200) DEFAULT NULL COMMENT '备注信息',
  `address` varchar(200) DEFAULT NULL COMMENT '详细地址',
  `lng` decimal(20,4) DEFAULT NULL COMMENT '地图x坐标',
  `lat` decimal(20,4) DEFAULT NULL COMMENT '地图Y坐标',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `creator` varchar(20) DEFAULT NULL COMMENT '创建人',
  `modifier` varchar(20) DEFAULT NULL COMMENT '修改人',
  `modifyTime` datetime DEFAULT NULL COMMENT '修改时间',
  `isEnabled` varchar(10) DEFAULT NULL COMMENT '是否可用或删除：0 禁用，1 可用'
) ENGINE=InnoDB AUTO_INCREMENT=410 DEFAULT CHARSET=utf8 COMMENT='高校楼栋地理位置表';

CREATE TABLE IF NOT EXISTS `ims_superdesk_core_dictionary_group` (
  `id` bigint(20) NOT NULL,
  `groupcode` varchar(30) NOT NULL,
  `groupname` varchar(30) NOT NULL,
  `isenabled` char(1) DEFAULT NULL,
  `oprateversion` bigint(20) DEFAULT NULL,
  `opratetype` char(1) DEFAULT NULL,
  `createby` varchar(30) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `lastupdateby` varchar(30) DEFAULT NULL,
  `lastupdatetime` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `ims_superdesk_core_dictionary_item` (
  `id` bigint(20) NOT NULL,
  `itemcode` varchar(30) NOT NULL,
  `itemname` varchar(30) NOT NULL,
  `groupid` bigint(11) NOT NULL,
  `isenabled` char(1) DEFAULT NULL,
  `oprateversion` bigint(20) DEFAULT NULL,
  `opratetype` char(1) DEFAULT NULL,
  `createby` varchar(30) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `lastupdateby` varchar(30) DEFAULT NULL,
  `orderno` int(11) DEFAULT NULL,
  `lastupdatetime` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superdesk_core_organization` (
  `id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL COMMENT '名称',
  `code` varchar(128) DEFAULT NULL COMMENT '编码',
  `type` varchar(20) DEFAULT NULL COMMENT '项目类型（高校校园、小区住宅、CBD写字楼）',
  `telephone` varchar(32) DEFAULT NULL COMMENT '服务热线',
  `provinceCode` varchar(32) DEFAULT NULL COMMENT '所在省份编码',
  `provinceName` varchar(40) DEFAULT NULL COMMENT '所在省份名称',
  `cityCode` varchar(32) DEFAULT NULL COMMENT '所在城市编码',
  `cityName` varchar(40) DEFAULT NULL COMMENT '所在城市名称',
  `address` varchar(256) DEFAULT NULL COMMENT '详细地址',
  `lng` decimal(20,4) DEFAULT NULL COMMENT '经度',
  `lat` decimal(20,4) DEFAULT NULL COMMENT '纬度',
  `status` varchar(2) DEFAULT NULL COMMENT '项目状态（0-待审核，1-通过，2-不通过）',
  `applicantName` varchar(40) DEFAULT NULL COMMENT '申请人姓名',
  `applicantPhone` varchar(12) DEFAULT NULL COMMENT '申请人电话',
  `reviewRemark` varchar(200) DEFAULT NULL COMMENT '审核信息说明',
  `applicantIdentity` varchar(40) DEFAULT NULL COMMENT '申请人身份',
  `wxUserId` int(11) DEFAULT NULL COMMENT '申请人的微信信息ID',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `creator` varchar(20) DEFAULT NULL COMMENT '创建者',
  `modifyTime` datetime DEFAULT NULL COMMENT '修改时间',
  `modifier` varchar(20) DEFAULT NULL COMMENT '修改人',
  `isEnabled` varchar(1) DEFAULT NULL COMMENT '是否可用或删除'
) ENGINE=InnoDB AUTO_INCREMENT=400 DEFAULT CHARSET=utf8 COMMENT='组织架构';


CREATE TABLE IF NOT EXISTS `ims_superdesk_core_provincecity` (
  `ID` int(11) NOT NULL COMMENT 'ID',
  `type` varchar(2) DEFAULT NULL COMMENT '类型(1-代表省份，2-代表城市)',
  `name` varchar(200) DEFAULT NULL COMMENT '省份/城市名称',
  `provinceCode` varchar(200) DEFAULT NULL COMMENT '省份编码',
  `cityCode` varchar(200) DEFAULT NULL COMMENT '城市编码',
  `description` varchar(50) DEFAULT NULL COMMENT '名称首字母',
  `creator` varchar(40) DEFAULT NULL COMMENT '创建人',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `modifier` varchar(40) DEFAULT NULL COMMENT '修改人',
  `modifyTime` datetime DEFAULT NULL COMMENT '修改时间',
  `isEnabled` varchar(2) DEFAULT NULL COMMENT '是否可用'
) ENGINE=InnoDB AUTO_INCREMENT=386 DEFAULT CHARSET=utf8 COMMENT='省份-城市参照信息表';


--
-- Indexes for table `ims_superdesk_core_build`
--
ALTER TABLE `ims_superdesk_core_build`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_core_dictionary_group`
--
ALTER TABLE `ims_superdesk_core_dictionary_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_core_dictionary_item`
--
ALTER TABLE `ims_superdesk_core_dictionary_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_core_organization`
--
ALTER TABLE `ims_superdesk_core_organization`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ims_superdesk_core_provincecity`
--
ALTER TABLE `ims_superdesk_core_provincecity`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_core_build`
--
ALTER TABLE `ims_superdesk_core_build`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',AUTO_INCREMENT=410;
--
-- AUTO_INCREMENT for table `ims_superdesk_core_dictionary_group`
--
ALTER TABLE `ims_superdesk_core_dictionary_group`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT for table `ims_superdesk_core_dictionary_item`
--
ALTER TABLE `ims_superdesk_core_dictionary_item`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=252;
--
-- AUTO_INCREMENT for table `ims_superdesk_core_organization`
--
ALTER TABLE `ims_superdesk_core_organization`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=400;
--
-- AUTO_INCREMENT for table `ims_superdesk_core_provincecity`
--
ALTER TABLE `ims_superdesk_core_provincecity`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',AUTO_INCREMENT=386;
