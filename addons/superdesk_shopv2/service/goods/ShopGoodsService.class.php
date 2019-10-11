<?php


include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/12/18
 * Time: 11:20 AM
 */
class ShopGoodsService
{
    private $_goodsModel;

    public function __construct()
    {
        $this->_goodsModel = new goodsModel();
    }


    /**
     * 处理批量价格更新时返回的resultMessage不在您的商品池中
     *
     * @param $sku
     */
    public function processingJdApiPriceGetPriceErrorNotInYourCommodityPool($skuId)
    {

        global $_W;
        global $_GPC;

        $sku = $this->_goodsModel->getOneByColumn(array(
            'jd_vop_sku' => $skuId
        ));

        if ($sku) {
            $this->_goodsModel->updateByColumn(array(
                'deleted' => 1,// 删除
                'status'  => 0 // 0 下架 1 上架 2 增品    //zjh 2018年9月26日 10:22:10 这原先是0,改成了1,因为回收站的条件是deleted=1,status>0,为0的话无法进入回收站,也无法被找到 //暂时不改了..
            ), array(
                'jd_vop_sku' => $skuId
            ));

            plog('goods.edit', '京东任务:不在您的商品池中 ID: ' . $sku['id'] . ' 商品名称: ' . $sku['title'] . ' 状态: ' . '下架');
        }

    }


    /**
     * 更新 by ID
     *
     * @param $params
     * @param $id
     *
     * @return bool
     */
    public function update($params, $id)
    {

        global $_W;
        global $_GPC;

        return $this->_goodsModel->update($params, $id);
    }

    /**
     * 更新 by 字段 [ col1 = xxx , col2 = xxx]
     *
     * @param       $params
     * @param array $column
     *
     * @return bool
     */
    public function updateByColumn($params, $column = array())
    {
        global $_W;
        global $_GPC;

        return $this->_goodsModel->updateByColumn($params, $column);

    }

    /**
     * 京东任务:修改商品状态 上下架
     *
     * @param $_struct
     *
     * @return bool|string
     */
    public function updateByStatus($_struct)
    {

//        {"state": 0, "skuId": 4607021}
        global $_W;
        global $_GPC;

        $sku = $this->_goodsModel->getOneByColumn(array(
            'jd_vop_sku' => $_struct['skuId']
        ));

//        echo json_encode($sku,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE).PHP_EOL;

        if ($sku) {
            $this->_goodsModel->updateByColumn(array(
                'status'     => intval($_struct['state']),
                'updatetime' => time()
            ), array(
                'jd_vop_sku' => $_struct['skuId']
            ));

            plog('goods.edit', '京东任务:修改商品状态 ID: ' . $sku['id'] . ' 商品名称: ' . $sku['title'] . ' 状态: ' . ($_struct['state'] == 1 ? '上架' : '下架'));
            return '京东任务:修改商品状态 ID: ' . $sku['id'] . ' 商品名称: ' . $sku['title'] . ' 状态: ' . ($_struct['state'] == 1 ? '上架' : '下架') . PHP_EOL;
        } else {
            return false;
        }


    }

    /**
     * @param $skuId
     *
     * @return bool
     */
    public function getOneBySkuId($skuId)
    {
        global $_W;
        global $_GPC;

        return $this->_goodsModel->getOneByColumn(array(
            'jd_vop_sku' => $skuId
        ));
    }
}