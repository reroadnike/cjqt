<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/17/17
 * Time: 6:41 PM
 *
 * @url: http://192.168.1.124/superdesk/app/index.php?i=15&c=entry&m=superdesk_boardroom_4school&do=ajax_boardroom_get_floor&structures_parentid=1
 */


global $_GPC, $_W;

$structures_parentid    = $_GPC['structures_parentid'];

/************ 初始数据 start ************/



$sql = 'SELECT * FROM ' . tablename('superdesk_boardroom_4school_building_structures') . ' WHERE `uniacid` = :uniacid ORDER BY `parentid`, `displayorder` DESC';
$category = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']), 'id');
if (!empty($category)) {
    $parent = $children = array();
    foreach ($category as $cid => $cate) {
        if (!empty($cate['parentid'])) {
            $children[$cate['parentid']][] = $cate;
        } else {
            $parent[$cate['id']] = $cate;
        }
    }
}


/************ 初始数据 end   ************/

include $this->template('ajax_boardroom_get_floor');

