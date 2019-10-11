<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_goods */

global $_GPC, $_W;
$active='sll_goods';

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_goods.class.php');
$_sll_goodsModel = new sll_goodsModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $_sll_goodsModel->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'goods_id'       => $_GPC['goods_id'],//
            'brand_id'       => $_GPC['brand_id'],// 品牌
            'name'           => $_GPC['name'],//
            'referprice'     => $_GPC['referprice'],// 参考价
            'status'         => $_GPC['status'],// 0:下架，1:上架 400：删除 2:审核不通过
            'pic'            => $_GPC['pic'],//
            'introductions'  => $_GPC['introductions'],// 简介
            'model'          => $_GPC['model'],// 型号
            'content'        => $_GPC['content'],// 详情页
            'number'         => $_GPC['number'],// 数量（水票专用）
            'type'           => $_GPC['type'],// 1：精选  2：特价  0：普通
            'toGoodsid'      => $_GPC['toGoodsid'],// 水票对应的商品id
            'sort'           => $_GPC['sort'],// 排序
            'classify_id'    => $_GPC['classify_id'],// 分类id
            'stock'          => $_GPC['stock'],// 库存
            'unit_id'        => $_GPC['unit_id'],// 单位
            'ctime'          => $_GPC['ctime'],// 创建时间
            'user_id'        => $_GPC['user_id'],// 拥有者id ( 0:总端 )
            'oldprice'       => $_GPC['oldprice'],// 原价
            'origin'         => $_GPC['origin'],// 来源1：本商城，2：京东
            'sku'            => $_GPC['sku'],// 京东商品sku
            'agreementprice' => $_GPC['agreementprice'],// 京东商品协议价
            'modified_at'    => $_GPC['modified_at'],//

        );
        $_sll_goodsModel->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_goods', array('op' => 'list')), 'success');


    }
    include $this->template('sll_goods_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where  = array('id' => $id);

            $_sll_goodsModel->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_goods', array('op' => 'list')), 'success');
    }

    $page      = $_GPC['page'];
    $page_size = 1000;

    $result    = $_sll_goodsModel->queryAll(array(
        'origin' => 1 // 过滤2京东
    ), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_goods_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $_sll_goodsModel->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $_sll_goodsModel->delete($id);

    message('删除成功！', referer(), 'success');
}

