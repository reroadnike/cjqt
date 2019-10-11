<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:08 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$id = intval($_GPC['id']);

$restlist = pdo_fetchall("SELECT a.* FROM " . tablename($this->table_stores) . " a INNER JOIN " . tablename($this->table_collection) . " b ON a.id = b.storeid where  a.weid = :weid and is_show=1 and from_user=:from_user ORDER BY a.displayorder DESC, a.id DESC", array(':weid' => $weid, ':from_user' => $from_user));

include $this->template('diandan/collection');