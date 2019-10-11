<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/18 * Time: 10:18 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=order_submit_order */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/order_submit_order.class.php');
$order_submit_order = new order_submit_orderModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $order_submit_order->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'order_id' => $_GPC['order_id'],// 
    'thirdOrder' => $_GPC['thirdOrder'],// 第三方的订单单号
    'sku' => $_GPC['sku'],// [{"skuId":商品编号, "num":商品数量,"bNeedAnnex":true, "bNeedGift":true, "price":100, "yanbao":[{"skuId":商品编号}]}] (最高支持50种商品)
    'name' => $_GPC['name'],// 收货人
    'province' => $_GPC['province'],// 一级地址
    'city' => $_GPC['city'],// 二级地址
    'county' => $_GPC['county'],// 三级地址
    'town' => $_GPC['town'],// 四级地址 (如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
    'address' => $_GPC['address'],// 详细地址
    'zip' => $_GPC['zip'],// 邮编
    'phone' => $_GPC['phone'],// 座机号
    'mobile' => $_GPC['mobile'],// 手机号
    'email' => $_GPC['email'],// 邮箱
    'remark' => $_GPC['remark'],// 备注（少于100字）
    'invoiceState' => $_GPC['invoiceState'],// 开票方式(1为随货开票，0为订单预借，2为集中开票 )
    'invoiceType' => $_GPC['invoiceType'],// 1普通发票2增值税发票
    'selectedInvoiceTitle' => $_GPC['selectedInvoiceTitle'],// 发票类型：4个人，5单位
    'companyName' => $_GPC['companyName'],// 发票抬头 (如果selectedInvoiceTitle=5则此字段必须)
    'invoiceContent' => $_GPC['invoiceContent'],// 1:明细，3：电脑配件，19:耗材，22：办公用品 备注:若增值发票则只能选1 明细
    'paymentType' => $_GPC['paymentType'],// 支付方式 (1：货到付款，2：邮局付款，4：在线支付，5：公司转账，6：银行转账，7：网银钱包，101：金采支付)
    'isUseBalance' => $_GPC['isUseBalance'],// 使用余额paymentType=4时，此值固定是1 其他支付方式0
    'submitState' => $_GPC['submitState'],// 是否预占库存，0是预占库存（需要调用确认订单接口），1是不预占库存 金融支付必须预占库存传0
    'invoiceName' => $_GPC['invoiceName'],// 增值票收票人姓名 备注：当invoiceType=2 且invoiceState=1时则此字段必填
    'invoicePhone' => $_GPC['invoicePhone'],// 增值票收票人电话 备注：当invoiceType=2 且invoiceState=1时则此字段必填
    'invoiceProvice' => $_GPC['invoiceProvice'],// 增值票收票人所在省(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填
    'invoiceCity' => $_GPC['invoiceCity'],// 增值票收票人所在市(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填
    'invoiceCounty' => $_GPC['invoiceCounty'],// 增值票收票人所在区/县(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填
    'invoiceAddress' => $_GPC['invoiceAddress'],// 增值票收票人所在地址 备注：当invoiceType=2 且invoiceState=1时则此字段必填
    'doOrderPriceMode' => $_GPC['doOrderPriceMode'],// "0: 客户端订单价格快照不做验证对比，还是以京东价格正常下单; 1:必需验证客户端订单价格快照，如果快照与京东价格不一致返回下单失败，需要更新商品价格后，重新下单;"
    'orderPriceSnap' => $_GPC['orderPriceSnap'],// 客户端订单价格快照	 "Json格式的数据，格式为:[{""price"":21.30,"skuId":123123 },{ "price":99.55, "skuId":22222 }] //商品价格 ,类型：BigDecimal" //商品编号,类型：long
    'reservingDate' => $_GPC['reservingDate'],// 大家电配送日期	 | 	默认值为-1，0表示当天，1表示明天，2：表示后天; 如果为-1表示不使用大家电预约日历
    'installDate' => $_GPC['installDate'],// 大家电安装日期	 | 	不支持默认按-1处理，0表示当天，1表示明天，2：表示后天
    'needInstall' => $_GPC['needInstall'],// 大家电是否选择了安装	 | 	是否选择了安装，默认为true，选择了“暂缓安装”，此为必填项，必填值为false。
    'promiseDate' => $_GPC['promiseDate'],// 中小件配送预约日期	 | 	格式：yyyy-MM-dd
    'promiseTimeRange' => $_GPC['promiseTimeRange'],// 中小件配送预约时间段	 | 	时间段如： 9:00-15:00
    'promiseTimeRangeCode' => $_GPC['promiseTimeRangeCode'],// 中小件预约时间段的标记
    'response' => $_GPC['response'],// api response
    'jd_vop_success' => $_GPC['jd_vop_success'],// 
    'jd_vop_resultMessage' => $_GPC['jd_vop_resultMessage'],// 
    'jd_vop_resultCode' => $_GPC['jd_vop_resultCode'],// 
    'jd_vop_code' => $_GPC['jd_vop_code'],// 
    'jd_vop_result_jdOrderId' => $_GPC['jd_vop_result_jdOrderId'],// 京东订单编号
    'jd_vop_result_freight' => $_GPC['jd_vop_result_freight'],// 运费(合同配置了才返回此字段)
    'jd_vop_result_orderPrice' => $_GPC['jd_vop_result_orderPrice'],// 商品总价格
    'jd_vop_result_orderNakedPrice' => $_GPC['jd_vop_result_orderNakedPrice'],// 订单裸价
    'jd_vop_result_orderTaxPrice' => $_GPC['jd_vop_result_orderTaxPrice'],// 订单税额

        );
        $order_submit_order->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('order_submit_order', array('op' => 'list')), 'success');


    }
    include $this->template('order_submit_order_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $order_submit_order->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('order_submit_order', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $order_submit_order->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('order_submit_order_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $order_submit_order->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $order_submit_order->delete($id);

    message('删除成功！', referer(), 'success');
}

