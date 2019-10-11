<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=zc_import_member_note */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_import_member_note.class.php');
$zc_import_member_note = new zc_import_member_noteModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $zc_import_member_note->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'n_import_id' => $_GPC['n_import_id'],// 
    'n_import_time' => $_GPC['n_import_time'],// 导入时间
    'n_import_status' => $_GPC['n_import_status'],// 导状态0:失败 1成功
    'n_import_fileName' => $_GPC['n_import_fileName'],// 导入数据文件的文件名
    'n_import_file_downUrl' => $_GPC['n_import_file_downUrl'],// 导入数据文件下载地址
    'n_import_number' => $_GPC['n_import_number'],// 操作结果数

        );
        $zc_import_member_note->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('zc_import_member_note', array('op' => 'list')), 'success');


    }
    include $this->template('zc_import_member_note_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $zc_import_member_note->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('zc_import_member_note', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $zc_import_member_note->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('zc_import_member_note_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $zc_import_member_note->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $zc_import_member_note->delete($id);

    message('删除成功！', referer(), 'success');
}

