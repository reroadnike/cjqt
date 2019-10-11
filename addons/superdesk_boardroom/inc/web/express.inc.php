<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 5:04 AM
 */

global $_W, $_GPC;
// pdo_query('DROP TABLE ims_superdesk_boardroom_s_express');
// pdo_query("CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_express` ( `id` int(10) unsigned NOT NULL AUTO_INCREMENT, `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',  `express_name` varchar(50) NOT NULL COMMENT '分类名称',  `express_price` varchar(10) NOT NULL DEFAULT '0',  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',  `express_area` varchar(50) NOT NULL COMMENT '配送区域',  `enabled` tinyint(1) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 ");
// pdo_query("ALTER TABLE  `ims_superdesk_boardroom_s_order` ADD  `expressprice` VARCHAR( 10 ) NOT NULL AFTER  `totalnum` ;");
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    $list = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_s_express') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (checksubmit('submit')) {
        if (empty($_GPC['express_name'])) {
            message('抱歉，请输入物流名称！');
        }
        $data = array(
            'uniacid' => $_W['uniacid'],
            'displayorder' => intval($_GPC['displayorder']),
            'express_name' => $_GPC['express_name'],
            'express_url' => $_GPC['express_url'],
            'express_area' => $_GPC['express_area'],
        );
        if (!empty($id)) {
            unset($data['parentid']);
            pdo_update('superdesk_boardroom_s_express', $data, array('id' => $id));
        } else {
            pdo_insert('superdesk_boardroom_s_express', $data);
            $id = pdo_insertid();
        }
        message('更新物流成功！', $this->createWebUrl('express', array('op' => 'display')), 'success');
    }
    //修改
    $express = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_s_express') . " WHERE id = '$id' and uniacid = '{$_W['uniacid']}'");
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $express = pdo_fetch("SELECT id  FROM " . tablename('superdesk_boardroom_s_express') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($express)) {
        message('抱歉，物流方式不存在或是已经被删除！', $this->createWebUrl('express', array('op' => 'display')), 'error');
    }
    pdo_delete('superdesk_boardroom_s_express', array('id' => $id));
    message('物流方式删除成功！', $this->createWebUrl('express', array('op' => 'display')), 'success');
} else {
    message('请求方式不存在');
}
include $this->template('express', TEMPLATE_INCLUDEPATH, true);