<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_client_goods */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_client_goods.class.php');
$sll_client_goods = new sll_client_goodsModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_client_goods->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'client_goods_id' => $_GPC['client_goods_id'],// 
    'goods_id' => $_GPC['goods_id'],// 商品ID
    'brand_id' => $_GPC['brand_id'],// 品牌ID
    'classify_id' => $_GPC['classify_id'],// 分类id
    'referprice' => $_GPC['referprice'],// 参考价格
    'trueprice' => $_GPC['trueprice'],// 交易价格
    'isHot' => $_GPC['isHot'],// 0 : 非热卖 1 : 热卖
    'status' => $_GPC['status'],// 0:下架，1:上架 400：删除
    'sort' => $_GPC['sort'],// 排序
    'stock' => $_GPC['stock'],// 库存
    'user_id' => $_GPC['user_id'],// 用户id
    'ctime' => $_GPC['ctime'],// 创建时间

        );
        $sll_client_goods->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_client_goods', array('op' => 'list')), 'success');


    }
    include $this->template('sll_client_goods_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_client_goods->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_client_goods', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_client_goods->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_client_goods_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_client_goods->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_client_goods->delete($id);

    message('删除成功！', referer(), 'success');
}

