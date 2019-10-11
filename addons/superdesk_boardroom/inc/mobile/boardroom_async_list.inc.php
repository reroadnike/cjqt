<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/2/17
 * Time: 2:09 PM
 */

global $_GPC, $_W;

$Ymd = $_GPC['ymd'];
$now = strtotime("+30 minutes", time());

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$page = $_GPC['page'];
$page_size = 20;

$result = $boardroom->queryByMobile(array(),$page,$page_size);
$total = $result['total'];
$page = $result['page'];
$page_size = $result['page_size'];
$list = $result['data'];

foreach ($list as $index => &$_boardroom){
    $_boardroom['situation'] = $this->get_boardroom_situation($_boardroom['id'],$Ymd);
}

unset($_boardroom);

$pager = pagination($total, $page, $page_size);

include $this->template('boardroom_async_list');