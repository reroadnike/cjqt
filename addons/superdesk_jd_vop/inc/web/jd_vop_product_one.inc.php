<?php
/**
 * 京东同步单个商品
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/1/17
 * Time: 3:33 PM
 */

global $_GPC, $_W;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'sync') {

    include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
    $_productService = new ProductService();

    $sku       = intval($_GPC['sku']);
    $page_num  = intval($_GPC['page_num'], 0);
    $overwrite = intval($_GPC['overwrite']);
    $overwrite = 1;

    if (empty($sku) || $sku <= 0) {
        message('sku不能为空');
        return;
    }

    if (strlen($_message['result']['skuId']) < 8) {


    } elseif (strlen($_message['result']['skuId']) == 8) {

        message('图书或音像不在销售范围');
        return;

    } elseif (strlen($_message['result']['skuId']) < 11
        && strlen($_message['result']['skuId']) > 8) {

        message('图书或音像不在销售范围');
        return;

    } elseif (strlen($_message['result']['skuId']) == 11) {


    } elseif (strlen($_message['result']['skuId']) == 12) {


    }

//    var_dump($overwrite);

    $result = $_productService->businessProcessingGetDetailOne($sku, $page_num, $overwrite);

//    var_dump($result);
//    array(5) { ["success"]=> bool(false) ["resultMessage"]=> string(21) "参数格式不正确" ["resultCode"]=> string(4) "1002" ["result"]=> NULL ["code"]=> int(200) }


    include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
    $_goodsModel = new goodsModel();
    $goods       = $_goodsModel->getOneByColumn(array('jd_vop_sku' => $sku));

    $goods_status = '不存在';

    if (!empty($goods)) {
        if ($goods['status'] > 0 && $goods['checked'] == 0 && $goods['total'] > 0 && $goods['deleted'] == 0) {
            $goods_status = '出售中';
        } else if ($goods['status'] > 0 && $goods['total'] <= 0 && $goods['deleted'] == 0) {
            $goods_status = '已售罄';
        } else if (($goods['status'] == 0 || $goods['checked'] == 1) && $goods['deleted'] == 0) {
            $goods_status = '仓库中';
        } else if ($goods['status'] > 0 && $goods['deleted'] == 1) {
            $goods_status = '回收站';
        } else if ($goods['checked'] == 1 && $goods['deleted'] == 0) {
            $goods_status = '待审核';
        } else if ($goods['status'] == 0 && $goods['deleted'] == 1) {
            $goods_status = '不在商品池中';
        }
    }

    include $this->template('jd_vop_product_one');

} elseif ($op == 'list') {

    include $this->template('jd_vop_product_one');

}

