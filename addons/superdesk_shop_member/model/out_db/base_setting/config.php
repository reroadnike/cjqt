<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/8/18
 * Time: 3:41 PM
 */

$database_config_shop_member = array(
    'host' => '127.0.0.1', //数据库IP或是域名
    'username' => 'root', // 数据库连接用户名
    'password' => 'root@2016', // 数据库连接密码
    'database' => 'membershop', // 数据库名
    'port' => 3306, // 数据库连接端口
    'tablepre' => '', // 表前缀，如果没有前缀留空即可
    'charset' => 'utf8', // 数据库默认编码
    'pconnect' => 0, // 是否使用长连接
);

$db_shop_member = new DB($database_config_shop_member);

