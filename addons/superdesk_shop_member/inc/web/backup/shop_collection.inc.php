<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=shop_collection */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/shop_collection.class.php');
$shop_collection = new shop_collectionModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $shop_collection->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'collectionid' => $_GPC['collectionid'],// id
    'goodsid' => $_GPC['goodsid'],// 商品ID
    'showPrice' => $_GPC['showPrice'],// 展示价格
    'ctime' => $_GPC['ctime'],// 关注时间
    'fansid' => $_GPC['fansid'],// 粉丝ID

        );
        $shop_collection->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('shop_collection', array('op' => 'list')), 'success');


    }
    include $this->template('shop_collection_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $shop_collection->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('shop_collection', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $shop_collection->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('shop_collection_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $shop_collection->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $shop_collection->delete($id);

    message('删除成功！', referer(), 'success');
}

