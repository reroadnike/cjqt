<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:06 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$id = intval($_GPC['id']);

$item = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " where weid = :weid AND id=:id ORDER BY displayorder DESC", array(':weid' => $weid, ':id' => $id));
$title = $item['title'];

if (empty($item)) {
    message('店面不存在！');
}

$collection = pdo_fetch("SELECT * FROM " . tablename($this->table_collection) . " where weid = :weid AND storeid=:storeid AND from_user=:from_user LIMIT 1", array(':weid' => $weid, ':storeid' => $id, ':from_user' => $from_user));

//智能点餐
$intelligents = pdo_fetchall("SELECT 1 FROM " . tablename($this->table_intelligent) . " WHERE weid={$weid} AND storeid={$id} GROUP BY name ORDER by name");

include $this->template('diandan/detail');