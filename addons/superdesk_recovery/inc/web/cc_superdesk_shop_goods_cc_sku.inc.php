<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/05/15
 * Time: 15:09
 * http://192.168.1.124/smart_office_building/web/index.php?c=site&a=entry&m=superdesk_recovery&do=cc_superdesk_shop_goods_cc_sku
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_recovery/model/cc_superdesk_shop_goods_cc_sku.class.php');


$_cc_superdesk_shop_goods_cc_skuModel = new cc_superdesk_shop_goods_cc_skuModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $_cc_superdesk_shop_goods_cc_skuModel->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'sku'       => $_GPC['sku'],//
            'num'       => $_GPC['num'],//
            'ids'       => $_GPC['ids'],//
            'is_delete' => $_GPC['is_delete'],//

        );
        $_cc_superdesk_shop_goods_cc_skuModel->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('cc_superdesk_shop_goods_cc_sku', array('op' => 'list')), 'success');


    }
    include $this->template('cc_superdesk_shop_goods_cc_sku_edit');

} elseif ($op == 'list') {



    $page      = $_GPC['page'];
    $page_size = 1000;

    $result    = $_cc_superdesk_shop_goods_cc_skuModel->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    foreach ($list as $index => $item){

//        echo json_encode($item);
//        echo ' SELECT * '.
//            ' FROM ' . tablename('superdesk_shop_goods') .
//            ' WHERE id in( ' . $item['ids'] . ' ) ';

        $list[$index]['goods_list'] = pdo_fetchall(
            ' SELECT * '.
            ' FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE id in( ' . $item['ids'] . ' ) ');

        $tmp_ids = explode(',',$item['ids']);

        foreach ($tmp_ids as $index_ids => $item_goods_id){

            if($index_ids == 0){
                continue;
            }

//            echo 'udpate ims_superdesk_shop_order_goods set goodsid = '.$tmp_ids[0].' where goodsid = '.$tmp_ids[$index_ids];
//            echo '<br/>';
//            pdo_update(
//                'superdesk_shop_order_goods',// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
//                array(
//                    'goodsid' => $tmp_ids[0]
//                ),
//                array(
//                    'goodsid' => $tmp_ids[$index_ids]
//                )
//            );

//            echo 'udpate ims_superdesk_jd_vop_order_submit_order_sku set shop_goods_id = '.$tmp_ids[0].' where shop_goods_id = '.$tmp_ids[$index_ids];
//            echo '<br/>';
//            pdo_update(
//                'superdesk_jd_vop_order_submit_order_sku',
//                array(
//                    'shop_goods_id' => $tmp_ids[0]
//                ),
//                array(
//                    'shop_goods_id' => $tmp_ids[$index_ids]
//                )
//            );

//            echo 'DELETE FROM ims_superdesk_shop_goods WHERE id = '.$tmp_ids[$index_ids];
//            echo '<br/>';
//            pdo_delete('superdesk_shop_goods', array('id' => $tmp_ids[$index_ids]));


//            pdo_update(
//                'cc_superdesk_shop_goods_cc_sku',
//                array(
//                    'is_delete' => 1
//                ),
//                array(
//                    'sku' => $item['sku']
//                )
//            );
        }
    }

    $pager = pagination($total, $page, $page_size);

    include $this->template('cc_superdesk_shop_goods_cc_sku_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $_cc_superdesk_shop_goods_cc_skuModel->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $_cc_superdesk_shop_goods_cc_skuModel->delete($id);

    message('删除成功！', referer(), 'success');
}

