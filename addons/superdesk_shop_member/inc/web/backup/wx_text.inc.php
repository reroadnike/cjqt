<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_text */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_text.class.php');
$wx_text = new wx_textModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_text->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'uid' => $_GPC['uid'],// 
    'uname' => $_GPC['uname'],// 
    'keyword' => $_GPC['keyword'],// 
    'precisions_type' => $_GPC['precisions_type'],// 匹配类型
    'precisions' => $_GPC['precisions'],// 
    'usorts' => $_GPC['usorts'],// 排序
    'text' => $_GPC['text'],// 
    'click' => $_GPC['click'],// 
    'token' => $_GPC['token'],// 
    'stauts' => $_GPC['stauts'],// 0:关闭，1：开启
    'keyworduuid' => $_GPC['keyworduuid'],// 

        );
        $wx_text->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_text', array('op' => 'list')), 'success');


    }
    include $this->template('wx_text_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_text->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_text', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_text->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_text_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_text->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_text->delete($id);

    message('删除成功！', referer(), 'success');
}

