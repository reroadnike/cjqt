<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Express_SuperdeskShopV2Model
{
    public function getExpressList()
    {
        global $_W;


//CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_express` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `name` varchar(50) DEFAULT '',
//  `express` varchar(50) DEFAULT '',
//  `status` tinyint(1) DEFAULT '1',
//  `displayorder` tinyint(3) unsigned DEFAULT '0',
//  `code` varchar(30) NOT NULL DEFAULT '',
//  PRIMARY KEY (`id`)
//) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=95 ;

//        (92, '京东快递', 'jd', 1, 0, 'JH_046'),

        $sql =
            ' select * ' .
            ' from ' . tablename('superdesk_shop_express') .
            ' where ' .
            '       status=1 ' .
            ' order by displayorder desc,id asc';

        $data = pdo_fetchall($sql);

        return $data;
    }
}