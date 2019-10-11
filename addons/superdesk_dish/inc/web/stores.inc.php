<?php
/**
 *
 * 门店管理
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:18 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;

$GLOBALS['frames'] = $this->getNaveMenu();

$action = 'stores';
$title = '门店管理';
$url = $this->createWebUrl($action, array('op' => 'display'));
$area = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid));
$shoptype = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " where weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid));

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    if (checksubmit('submit')) { //排序
        if (is_array($_GPC['displayorder'])) {
            foreach ($_GPC['displayorder'] as $id => $val) {
                $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                pdo_update($this->table_stores, $data, array('id' => $id));
            }
        }
        message('操作成功!', $url);
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    $where = "WHERE weid = '{$_W['uniacid']}'";
    $storeslist = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " {$where} order by displayorder desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
    if (!empty($storeslist)) {
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_stores) . " $where");
        $pager = pagination($total, $pindex, $psize);
    }
} elseif ($operation == 'post') {
    load()->func('tpl');
    $id = intval($_GPC['id']); //门店编号
    $reply = pdo_fetch("select * from " . tablename($this->table_stores) . " where id=:id and weid =:weid", array(':id' => $id, ':weid' => $_W['uniacid']));

    if (empty($reply)) {
        $reply['begintime'] = "09:00";
        $reply['endtime'] = "18:00";
    }

    $piclist = unserialize($reply['thumb_url']);

    if (checksubmit('submit')) {
        $data = array(
            'weid' => intval($_W['uniacid']),
            'areaid' => intval($_GPC['area']),
            'typeid' => intval($_GPC['type']),
            'title' => trim($_GPC['title']),
            'info' => trim($_GPC['info']),
            'content' => trim($_GPC['content']),
            'tel' => trim($_GPC['tel']),
            'announce' => trim($_GPC['announce']),
            'logo' => trim($_GPC['logo']),
            'address' => trim($_GPC['address']),
            'location_p' => trim($_GPC['location_p']),
            'location_c' => trim($_GPC['location_c']),
            'location_a' => trim($_GPC['location_a']),
            'lng' => trim($_GPC['baidumap']['lng']),
            'lat' => trim($_GPC['baidumap']['lat']),
            'password' => trim($_GPC['password']),
            'recharging_password' => trim($_GPC['recharging_password']),
            'is_show' => intval($_GPC['is_show']),
            'place' => trim($_GPC['place']),
            'hours' => trim($_GPC['hours']),
            'consume' => trim($_GPC['consume']),
            'level' => intval($_GPC['level']),
            'enable_wifi' => intval($_GPC['enable_wifi']),
            'enable_card' => intval($_GPC['enable_card']),
            'enable_room' => intval($_GPC['enable_room']),
            'enable_park' => intval($_GPC['enable_park']),
            'is_meal' => intval($_GPC['is_meal']),
            'is_delivery' => intval($_GPC['is_delivery']),
            'is_sms' => intval($_GPC['is_sms']),
            'is_hot' => intval($_GPC['is_hot']),
            'sendingprice' => trim($_GPC['sendingprice']),
            'dispatchprice' => trim($_GPC['dispatchprice']),
            'freeprice' => trim($_GPC['freeprice']),
            'begintime' => trim($_GPC['begintime']),
            'endtime' => trim($_GPC['endtime']),
            'updatetime' => TIMESTAMP,
            'dateline' => TIMESTAMP,
        );

        if (istrlen($data['title']) == 0) {
            message('没有输入标题.', '', 'error');
        }
        if (istrlen($data['title']) > 30) {
            message('标题不能多于30个字。', '', 'error');
        }
        if (istrlen($data['tel']) == 0) {
//                    message('没有输入联系电话.', '', 'error');
        }
        if (istrlen($data['address']) == 0) {
            //message('请输入地址。', '', 'error');
        }

        if (is_array($_GPC['thumbs'])) {
            $data['thumb_url'] = serialize($_GPC['thumbs']);
        }

        if (!empty($id)) {
            unset($data['dateline']);
            pdo_update($this->table_stores, $data, array('id' => $id, 'weid' => $_W['uniacid']));
        } else {
            pdo_insert($this->table_stores, $data);
        }
        message('操作成功!', $url);
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $store = pdo_fetch("SELECT id FROM " . tablename($this->table_stores) . " WHERE id = '$id'");
    if (empty($store)) {
        message('抱歉，不存在或是已经被删除！', $this->createWebUrl('stores', array('op' => 'display')), 'error');
    }
    pdo_delete($this->table_stores, array('id' => $id, 'weid' => $_W['uniacid']));
    message('删除成功！', $this->createWebUrl('stores', array('op' => 'display')), 'success');
}
include $this->template('stores');