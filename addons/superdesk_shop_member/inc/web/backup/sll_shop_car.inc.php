<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_shop_car */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_shop_car.class.php');
$sll_shop_car = new sll_shop_carModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_shop_car->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'car_id' => $_GPC['car_id'],// 
    'client_goods_id' => $_GPC['client_goods_id'],// 水店商品id
    'price' => $_GPC['price'],// 单价
    'number' => $_GPC['number'],// 数量
    'total' => $_GPC['total'],// 总价
    'user_id' => $_GPC['user_id'],// 
    'fansid' => $_GPC['fansid'],// 
    'ctime' => $_GPC['ctime'],// 

        );
        $sll_shop_car->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_shop_car', array('op' => 'list')), 'success');


    }
    include $this->template('sll_shop_car_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_shop_car->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_shop_car', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_shop_car->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_shop_car_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_shop_car->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_shop_car->delete($id);

    message('删除成功！', referer(), 'success');
}

