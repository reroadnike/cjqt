

20181106_林进雨_隐性问题_内购商城_退货退款_有一个价格便宜且购买数量多的商品退了_记录的退退款理由过长_导致一直处理不了


return_goods_result 长度问题 改为 longtext




{"id":"1999220790","accountType":"1","amount":"0.00","pin":"中航物业VOP","orderId":"76152781265","tradeType":"416","tradeTypeName":"退款-售后原返（老）写余额","createdDate":"2018-06-12 19:46:19","notePub":"退货返款:订单号:76152781265,商品编号:5039880,商品名称:欧普照明（OPPLE）T8灯管led一体化节能灯管 4000K暖白光0.6米8瓦,申请理由:null,退款金额:15.900,服务单:436775577,退款方式：原返","tradeNo":"3016795637","processing":"0","process_result":""}  处理::
START====>
{
    "订单号": "76152781265",
    "商品编号": "5039880",
    "商品名称": "欧普照明（OPPLE）T8灯管led一体化节能灯管 4000K暖白光0.6米8瓦",
    "申请理由": "null",
    "退款金额": "15.900",
    "服务单": "436775577",
    "退款方式": "原返"
}

SELECT * FROM `ims_superdesk_jd_vop_balance_detail` WHERE id = 1999220790

SELECT * FROM `ims_superdesk_jd_vop_balance_detail_processing` WHERE id = 1999220790


echo '分析备注信息';
{
    "订单号": "76152781265",
    "商品编号": "5039880",
    "商品名称": "欧普照明（OPPLE）T8灯管led一体化节能灯管 4000K暖白光0.6米8瓦",
    "申请理由": "null",
    "退款金额": "15.900",
    "服务单": "436775577",
    "退款方式": "原返"
}


echo '_order_submit_orderModel getOneByColumn (jd_vop_result_jdOrderId '. $_balance_detail_416['订单号'] . ')';

SELECT thirdOrder , order_id FROM ims_superdesk_jd_vop_order_submit_order WHERE jd_vop_result_jdOrderId = 76152781265
ME20180604180202394066
10923

echo PHP_EOL;
$_shop_order = $this->_orderModel->getOneByColumn(array(
    'ordersn'   => $_jd_vop_submit_order['thirdOrder'],
    'id'        => $_jd_vop_submit_order['order_id'],
    'expresssn' => $_balance_detail_416['订单号']
));
SELECT * FROM ims_superdesk_shop_order WHERE ordersn = 'ME20180604180202394066' and id =10923 and expresssn = 76152781265




$_jd_vop_submit_order_sku = $this->_order_submit_order_skuModel->getOneByColumn(array(
    'jdOrderId' => $_balance_detail_416['订单号'],
    'skuId'     => $_balance_detail_416['商品编号']
));
SELECT * FROM ims_superdesk_jd_vop_order_submit_order_sku WHERE skuId = 5039880 and jdOrderId = 76152781265


$_jd_vop_submit_order_sku['shop_order_id'] = $_shop_order['id'];
$_jd_vop_submit_order_sku['shop_order_sn'] = $_shop_order['ordersn'];
$_jd_vop_submit_order_sku['shop_goods_id'] = $this->_goodsModel->getGoodsIdBySkuId($_balance_detail_416['商品编号']);// 正常

$_jd_vop_submit_order_sku['return_goods_nun'] = $_jd_vop_submit_order_sku['return_goods_nun'] - 1;


if (empty($_jd_vop_submit_order_sku['return_goods_result'])) {
    $_jd_vop_submit_order_sku['return_goods_result'] = array();
} else {
    $_jd_vop_submit_order_sku['return_goods_result'] = iunserializer($_jd_vop_submit_order_sku['return_goods_result']);
}

$_jd_vop_submit_order_sku['return_goods_result'][] = $item_balance_detail;

$_jd_vop_submit_order_sku['return_goods_result'] = iserializer($_jd_vop_submit_order_sku['return_goods_result']);

echo json_encode($_jd_vop_submit_order_sku, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
echo PHP_EOL;

$this->_order_submit_order_skuModel->saveOrUpdateByColumn($_jd_vop_submit_order_sku, array(
    'jdOrderId' => $_balance_detail_416['订单号'],
    'skuId'     => $_balance_detail_416['商品编号']
));


$_shop_order_goods = $this->_order_goodsModel->getOneByColumn(array(
    'orderid' => $_jd_vop_submit_order_sku['shop_order_id'],
    'goodsid' => $_jd_vop_submit_order_sku['shop_goods_id'],
));

if ($_shop_order_goods) {
    $this->_order_goodsModel->update(array(
        'return_goods_nun'    => $_jd_vop_submit_order_sku['return_goods_nun'],
        'return_goods_result' => $_jd_vop_submit_order_sku['return_goods_result']
    ), $_shop_order_goods['id']);
}

echo json_encode($_shop_order_goods, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
echo PHP_EOL;


$result_msg = '系统订单 ';
echo $result_msg;
//2018年9月11日 16:39:24 zjh _balance_detailModel -> _balance_detail_processingModel
$this->_balance_detail_processingModel->saveOrUpdateToId(array(
    'processing'     => 1,
    'process_result' => $result_msg,
), $item_balance_detail['id']);