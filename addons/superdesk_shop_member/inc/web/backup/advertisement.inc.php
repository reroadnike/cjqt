<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=advertisement */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/advertisement.class.php');
$advertisement = new advertisementModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $advertisement->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'name' => $_GPC['name'],// 
    'url' => $_GPC['url'],// 
    'imgUrl' => $_GPC['imgUrl'],// 
    'status' => $_GPC['status'],// 1:有效 0：失效
    'ctime' => $_GPC['ctime'],// 
    'user_id' => $_GPC['user_id'],// 

        );
        $advertisement->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('advertisement', array('op' => 'list')), 'success');


    }
    include $this->template('advertisement_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $advertisement->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('advertisement', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $advertisement->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('advertisement_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $advertisement->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $advertisement->delete($id);

    message('删除成功！', referer(), 'success');
}

