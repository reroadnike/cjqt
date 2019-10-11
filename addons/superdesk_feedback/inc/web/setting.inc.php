<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 3/6/18
 * Time: 2:40 PM
 */

global $_W, $_GPC;
checklogin();
load()->func('tpl');

$item = pdo_fetch(
    " select * " .
    " from " . tablename($this->modulename . '_setting') .
    " where uniacid =:uniacid",
    array(
        ':uniacid' => $_W['uniacid']
    )
);

if (checksubmit('submit')) {

    $data = array(
        'uniacid'   => $_W['uniacid'],
        'topimgurl' => trim($_GPC['topimgurl']),
        'pagecolor' => trim($_GPC['pagecolor']),
        'pagesize'  => trim($_GPC['pagesize']),
        'ischeck'   => intval($_GPC['ischeck']),
        'dateline'  => TIMESTAMP,
    );

    if (!empty($_GPC['topimgurl'])) {
        $data['topimgurl'] = $_GPC['topimgurl'];
        load()->func('file');
        file_delete($_GPC['topimgurl-old']);
    }

    if (!empty($item)) {

        unset($data['dateline']);
        
        pdo_update($this->modulename . '_setting', $data, array('uniacid' => $_W['uniacid']));
    } else {
        pdo_insert($this->modulename . '_setting', $data);
    }

    message('操作成功', $this->createWebUrl('setting'), 'success');
}

include $this->template('setting');