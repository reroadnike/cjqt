



## 已更新到服务器

CREATE TABLE `ims_superdesk_core_tb_user` (
  `id` int(11) NOT NULL,
  `userName` varchar(40) DEFAULT NULL COMMENT '姓名',
  `nickName` varchar(40) DEFAULT NULL COMMENT '昵称',
  `userMobile` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `userType` varchar(2) DEFAULT NULL COMMENT '用户类型',
  `userSex` varchar(2) DEFAULT NULL COMMENT '性别',
  `userCardNo` varchar(40) DEFAULT NULL COMMENT '学生号/身份证',
  `birthday` varchar(20) DEFAULT NULL COMMENT '生日',
  `userPhotoUrl` varchar(200) DEFAULT NULL COMMENT '头像',
  `password` varchar(100) DEFAULT NULL COMMENT '密码',
  `status` varchar(2) DEFAULT NULL COMMENT '认证状态',
  `suggestion` varchar(250) DEFAULT NULL COMMENT '审核建议',
  `address` varchar(200) DEFAULT NULL COMMENT '详细地址',
  `imageUrl01` varchar(200) DEFAULT NULL COMMENT '证件照片1',
  `imageUrl02` varchar(200) DEFAULT NULL COMMENT '证件照片2',
  `imageUrl03` varchar(200) DEFAULT NULL,
  `organizationId` int(11) DEFAULT NULL COMMENT '用户所属组织',
  `virtualArchId` int(11) DEFAULT NULL COMMENT '学院/系部ID',
  `userNumber` varchar(40) DEFAULT NULL COMMENT '员工编号',
  `enteringTime` date DEFAULT NULL COMMENT '入司时间',
  `positionName` varchar(40) DEFAULT NULL COMMENT '职位名称',
  `departmentId` int(11) DEFAULT NULL COMMENT '部门ID',
  `facePlusUserId` int(11) DEFAULT NULL COMMENT 'face++用户唯一标识',
  `roleType` varchar(2) DEFAULT NULL COMMENT '企业用户角色（1-管理员，2-普通用户）',
  `noticePower` varchar(2) DEFAULT NULL COMMENT '接受审核通知（0-不接收用户申请通知，关，1-接收用户申请通知，开）',
  `creator` varchar(20) DEFAULT NULL COMMENT '创建者',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `modifier` varchar(20) DEFAULT NULL COMMENT '修改人',
  `modifyTime` datetime DEFAULT NULL COMMENT '修改时间',
  `isEnabled` varchar(2) DEFAULT NULL COMMENT '是否可用',
  `isSyncNeigou` int(11) DEFAULT NULL COMMENT '是否同步内购网',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前端用户信息表';

ALTER TABLE `ims_superdesk_core_tb_user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ims_superdesk_core_tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4468;
