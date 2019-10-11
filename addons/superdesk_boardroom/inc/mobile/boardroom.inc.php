<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/27/17
 * Time: 5:11 AM
 * 
 * 192.168.1.244/superdesk/app/index.php?i=15&c=entry&do=boardroom&m=superdesk_boardroom
 */


global $_GPC, $_W;

$title = "会议室预定";
$now = strtotime("+30 minutes", time());
$Ymd = date('Y-m-d', time());



$url_boardroom_async_list = $this->createMobileUrl('boardroom_async_list');



include $this->template('boardroom');