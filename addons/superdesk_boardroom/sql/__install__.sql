ALTER TABLE `ims_business_dongyuantangguoyiguang_department`
ADD `createtime` INT(11) NOT NULL DEFAULT '0' AFTER `department_name`,
ADD `updatetime` INT(11) NOT NULL DEFAULT '0' AFTER `createtime`,
ADD `uniacid` INT(11) NOT NULL DEFAULT '0' AFTER `updatetime`;
-- phpMyAdmin SQL Dump
-- version 4.4.15.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-07-24 19:20:43
-- 服务器版本： 5.6.29-log
-- PHP Version: 7.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom` (
  `id` int(10) NOT NULL COMMENT 'ID',

  `name` varchar(50) NOT NULL COMMENT '名称',
  `address` varchar(100) NOT NULL COMMENT '地址',
  `floor` int(10) NOT NULL DEFAULT '1' COMMENT '楼层',
  `traffic` varchar(255) NOT NULL COMMENT '交通',
  `lat` decimal(9,6) NOT NULL COMMENT '经度',
  `lng` decimal(9,6) NOT NULL COMMENT '纬度',

  `images` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `basic` text NOT NULL DEFAULT '',
  `carousel` text NOT NULL DEFAULT '' COMMENT '轮播图',

  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '租金/小时',

  `equipment` varchar(255) NOT NULL COMMENT '设备 序列化的设备编号数组',
  `max_num` int(10) NOT NULL DEFAULT '1' COMMENT '容纳人数',
  `appointment_num` int(10) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '注意事项',
  `cancle_rule` varchar(255) NOT NULL DEFAULT '' COMMENT '取消预约规则',

  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '修改时间',

  `deleted` int(10) NOT NULL DEFAULT '0' COMMENT '删除(1已删除)',

  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid'

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_appointment` (
  `id` int(10) NOT NULL COMMENT 'ID',

  `boardroom_id` int(10) NOT NULL COMMENT '会议室ID',

  `openid` varchar(64) NOT NULL COMMENT 'openid',
  `client_name` varchar(64) NOT NULL COMMENT '客户名字',
  `client_telphone` varchar(25) NOT NULL COMMENT '客户电话',

  `deleted` int(10) NOT NULL DEFAULT '0' COMMENT '删除(1已删除)',
  `state` int(1) NOT NULL DEFAULT '0' COMMENT '审核状态(1同意)',

  `relate_id` varchar(80) NOT NULL DEFAULT '0' COMMENT '关联会员表',

  `people_num` int(1) NOT NULL DEFAULT '1' COMMENT '会议人数',



  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',

  `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '预约开始时间',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '预约结束时间',

  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid'

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_equipment` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `name` varchar(10) NOT NULL COMMENT '设备名字',
  `images` varchar(255) NOT NULL COMMENT '设备图片',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `deleted` int(1) NOT NULL DEFAULT '0' COMMENT '删除',
  `uniacid` int(10) NOT NULL COMMENT 'uniacid'
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;




ALTER TABLE `ims_superdesk_boardroom`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ims_superdesk_boardroom_appointment`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ims_superdesk_boardroom_equipment`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `ims_superdesk_boardroom`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `ims_superdesk_boardroom_appointment`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `ims_superdesk_boardroom_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;



--
-- 转存表中的数据 `ims_superdesk_boardroom_equipment`
--

INSERT INTO `ims_superdesk_boardroom_equipment` (`id`, `name`, `images`, `updatetime`, `createtime`, `deleted`, `uniacid`) VALUES
(1, '空调', 'images/8/2016/06/r85tcbiZ2rvRF6SBJzwFR2QIQQLIv4.png', 1465396073, 1459436356, 0, 8),
(2, '免费WIFI', 'images/8/2016/06/LOrAt4OD9KXU9yY99B2lyH8YDAoPaD.png', 1465395968, 1459436356, 0, 8),
(4, 'Ariplay', 'images/8/2016/06/K6lMKQc66RIc565mCIWWlKzFqlqF5M.png', 1465395995, 1459436356, 0, 8),
(5, '户外空间', 'images/8/2016/06/bBo4K45xzXbbxqD8qmrVJvqMKvrqVj.png', 1465396008, 1459436356, 0, 8),
(11, '音乐播放', 'images/8/2016/06/rQmM42Qat4cCtJnghdTwUjtGtkRk1R.png', 1465396021, 1459436356, 0, 8),
(12, '固定电话', 'images/8/2016/06/j2YMN1y1J1YjMAzMmMFTytT7TWgY2y.png', 1465396031, 1459436356, 0, 8),
(13, '饮用水', 'images/8/2016/06/jEFj4EijTuthcJ2z7YujtU9b7tj2h4.png', 1465396043, 1459436356, 0, 8),
(14, '快递收发', 'images/8/2016/06/n5BbOUN5Dd7600570uzuw5D25dz7dd.png', 1465396058, 1459436356, 0, 8),

(15, '快递收发', 'images/8/2016/06/n5BbOUN5Dd7600570uzuw5D25dz7dd.png', 1464872026, 1464872026, 0, 9),
(16, '空调', 'images/8/2016/06/r85tcbiZ2rvRF6SBJzwFR2QIQQLIv4.png', 1465396073, 1459436356, 0, 9),
(17, '免费WIFI', 'images/8/2016/06/LOrAt4OD9KXU9yY99B2lyH8YDAoPaD.png', 1465395968, 1459436356, 0, 9),
(18, 'Ariplay', 'images/8/2016/06/K6lMKQc66RIc565mCIWWlKzFqlqF5M.png', 1465395995, 1459436356, 0, 9),
(19, '户外空间', 'images/8/2016/06/bBo4K45xzXbbxqD8qmrVJvqMKvrqVj.png', 1465396008, 1459436356, 0, 9),
(20, '音乐播放', 'images/8/2016/06/rQmM42Qat4cCtJnghdTwUjtGtkRk1R.png', 1465396021, 1459436356, 0, 9),
(21, '固定电话', 'images/8/2016/06/j2YMN1y1J1YjMAzMmMFTytT7TWgY2y.png', 1465396031, 1459436356, 0, 9),
(22, '饮用水', 'images/8/2016/06/jEFj4EijTuthcJ2z7YujtU9b7tj2h4.png', 1465396043, 1459436356, 0, 9),

(25, '空调', 'images/8/2016/06/r85tcbiZ2rvRF6SBJzwFR2QIQQLIv4.png', 1465396073, 1459436356, 0, 6),
(26, '免费WIFI', 'images/8/2016/06/LOrAt4OD9KXU9yY99B2lyH8YDAoPaD.png', 1465395968, 1459436356, 0, 6),
(27, 'Ariplay', 'images/8/2016/06/K6lMKQc66RIc565mCIWWlKzFqlqF5M.png', 1465395995, 1459436356, 0, 6),
(28, '户外空间', 'images/8/2016/06/bBo4K45xzXbbxqD8qmrVJvqMKvrqVj.png', 1465396008, 1459436356, 0, 6),
(29, '音乐播放', 'images/8/2016/06/rQmM42Qat4cCtJnghdTwUjtGtkRk1R.png', 1465396021, 1459436356, 0, 6),
(30, '固定电话', 'images/8/2016/06/j2YMN1y1J1YjMAzMmMFTytT7TWgY2y.png', 1465396031, 1459436356, 0, 6),
(31, '饮用水', 'images/8/2016/06/jEFj4EijTuthcJ2z7YujtU9b7tj2h4.png', 1465396043, 1459436356, 0, 6),
(32, '快递收发', 'images/8/2016/06/n5BbOUN5Dd7600570uzuw5D25dz7dd.png', 1465396058, 1459436356, 0, 6),

(34, '空调', 'images/7/2016/07/LYVfKx7ypkX3kF9qGh31uG3vZQp3xg.png', 1468069511, 1467987762, 0, 7),
(35, '投影', 'images/7/2016/07/S6kfAdZdP5A7g4p6pK4DP2fmwt1aVv.png', 1468069520, 1467987779, 0, 7),
(36, '不间断电源', 'images/7/2016/07/sYLgSexLFesOlSe9I477Iy77l44fGi.png', 1468069415, 1467987799, 0, 7),
(37, '饮用水', 'images/7/2016/07/uE0WgdsKu9WKKDiIKLy8KBGlSUH1Us.png', 1468069528, 1467987817, 0, 7);