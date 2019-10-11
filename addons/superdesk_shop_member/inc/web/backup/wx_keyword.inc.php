<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_keyword */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_keyword.class.php');
$wx_keyword = new wx_keywordModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_keyword->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'keywordid' => $_GPC['keywordid'],// 
    'keyword' => $_GPC['keyword'],// 
    'token' => $_GPC['token'],// 
    'module' => $_GPC['module'],// keyword存在的表名
    'ctime' => $_GPC['ctime'],// 创建时间
    'state' => $_GPC['state'],// 1:表示启用，0表示停用
    'keyworduuid' => $_GPC['keyworduuid'],// 

        );
        $wx_keyword->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_keyword', array('op' => 'list')), 'success');


    }
    include $this->template('wx_keyword_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_keyword->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_keyword', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_keyword->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_keyword_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_keyword->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_keyword->delete($id);

    message('删除成功！', referer(), 'success');
}

