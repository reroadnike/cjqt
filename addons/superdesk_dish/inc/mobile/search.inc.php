<?php
/**
 * 门店搜索
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:04 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " where weid = :weid ORDER BY id DESC", array(':weid' => $weid));
$word = $setting['searchword'];
if ($word) {
    $words = explode(' ', $word);
}

$searchword = trim($_GPC['searchword']);
if ($searchword) {
    $strwhere = " AND title like '%" . $searchword . "%' ";
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " where weid = :weid {$strwhere} ORDER BY displayorder DESC,id DESC", array(':weid' => $weid));
} else {
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " where weid = :weid AND is_hot=1 ORDER BY displayorder DESC,id DESC", array(':weid' => $weid));
}

include $this->template('diandan/search');