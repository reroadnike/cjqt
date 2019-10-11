<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_custom_menu_class */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_custom_menu_class.class.php');
$wx_custom_menu_class = new wx_custom_menu_classModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_custom_menu_class->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'token' => $_GPC['token'],// 
    'pid' => $_GPC['pid'],// 
    'title' => $_GPC['title'],// 
    'keyword' => $_GPC['keyword'],// 
    'url' => $_GPC['url'],// 
    'is_show' => $_GPC['is_show'],// 
    'sort' => $_GPC['sort'],// 
    'wxsys' => $_GPC['wxsys'],// 
    'text' => $_GPC['text'],// 
    'emoji_code' => $_GPC['emoji_code'],// 图标码

        );
        $wx_custom_menu_class->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_custom_menu_class', array('op' => 'list')), 'success');


    }
    include $this->template('wx_custom_menu_class_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_custom_menu_class->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_custom_menu_class', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_custom_menu_class->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_custom_menu_class_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_custom_menu_class->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_custom_menu_class->delete($id);

    message('删除成功！', referer(), 'success');
}

