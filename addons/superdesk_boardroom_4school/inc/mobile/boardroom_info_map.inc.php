<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/2/17
 * Time: 3:07 PM
 *
 * @url http://192.168.1.124/superdesk/app/index.php?i=15&c=entry&do=boardroom_info_map&m=superdesk_boardroom
 */

global $_GPC, $_W;

$id = $_GPC['id'];
$Ymd = $_GPC['ymd'];

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$item = $boardroom->getOne($id);

include $this->template('boardroom_info_map');