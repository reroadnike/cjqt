<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_store_import_user_note */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_store_import_user_note.class.php');
$sll_store_import_user_note = new sll_store_import_user_noteModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_store_import_user_note->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'store_import_user_note_id' => $_GPC['store_import_user_note_id'],// 水店导入用户记录id
    'store_import_user_note_ctime' => $_GPC['store_import_user_note_ctime'],// 水店导入用户记录创建时间
    'store_import_user_note_number' => $_GPC['store_import_user_note_number'],// 操作结果数
    'store_import_user_note_status' => $_GPC['store_import_user_note_status'],// 操作结果状态0:失败 1成功
    'store_id' => $_GPC['store_id'],// 水店id
    'store_import_user_note_downurl' => $_GPC['store_import_user_note_downurl'],// 导入数据文件下载地址
    'store_import_user_note_filename' => $_GPC['store_import_user_note_filename'],// 导入数据文件的文件名

        );
        $sll_store_import_user_note->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_store_import_user_note', array('op' => 'list')), 'success');


    }
    include $this->template('sll_store_import_user_note_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_store_import_user_note->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_store_import_user_note', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_store_import_user_note->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_store_import_user_note_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_store_import_user_note->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_store_import_user_note->delete($id);

    message('删除成功！', referer(), 'success');
}

