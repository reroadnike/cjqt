<?php

//namespace sdk\jd_vop\service;
//use sdk\jd_vop\service;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order_sku.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_price.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_refund.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_examine.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/order/ShopOrderService.class.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/23/17
 * Time: 4:55 PM
 */
class OrderService
{


    private $jd_sdk;
//    private $_redis;

//    private $_areaModel;
    private $_order_submit_orderModel;
    private $_order_submit_order_skuModel;

    private $_product_detailModel;
    private $_product_priceModel;
    private $_goodsModel;

    private $_orderModel;
    private $_order_goodsModel;
    private $_order_refundModel;
    private $_order_examineModel;

    private $_shopOrderService;

    function __construct()
    {
        $this->_order_submit_orderModel     = new order_submit_orderModel();
        $this->_order_submit_order_skuModel = new order_submit_order_skuModel();
        $this->_product_detailModel         = new product_detailModel();
        $this->_product_priceModel          = new product_priceModel();
        $this->_goodsModel                  = new goodsModel();

        $this->_orderModel         = new orderModel();
        $this->_order_goodsModel   = new order_goodsModel();
        $this->_order_refundModel  = new order_refundModel();
        $this->_order_examineModel = new order_examineModel();

        $this->_shopOrderService = new ShopOrderService();

        $this->jd_sdk        = new JDVIPOpenPlatformSDK();
        $this->jd_sdk->debug = false;
        $this->jd_sdk->init_access_token();
    }

    public function message($success = false, $resultMessage = '', $resultCode = '0000', $result = false, $code = 200)
    {

        $data                  = array();
        $data['success']       = $success;
        $data['resultMessage'] = $resultMessage;
        $data['resultCode']    = $resultCode;
        $data['result']        = $result;
        $data['code']          = $code;

        return $data;


    }


    /**
     * @param $multiple_order
     * @param $order_id
     */
    public function rollBackOrder($multiple_order, $order_id)
    {

        if ($multiple_order == 0) {

            $this->_order_goodsModel->deleteByOrderid($order_id);
            $this->_orderModel->delete($order_id);

        } else {

            // 删除子订单
            $order_ids = $this->_orderModel->queryIdByParentId($order_id);
            foreach ($order_ids as $_order_id) {

                $this->_order_goodsModel->deleteByOrderid($_order_id);
                $this->_orderModel->delete($_order_id);

            }

            // 删除主订单
            $this->_orderModel->delete($order_id);

        }


    }

    /**
     * 7.1  统一下单接口
     *
     * @param $order_id
     * @param $thirdOrder
     * @param $sku
     * @param $orderPriceSnap
     * @param $address
     * @param $remark
     *
     * @return mixed
     */
    public function submitOrder($order_id, $thirdOrder, $sku/*(最高支持50种商品)*/, $orderPriceSnap, $address, $remark, $jd_vop_sku_id_2_shop_goods_id = array())
    {
        global $_W, $_GPC;

        $submit_order_data = array();

        $submit_order_data["order_id"] = $order_id;

        $submit_order_data["thirdOrder"] = $thirdOrder;//thirdOrder	 | 	String	 | 	必须	 | 	第三方的订单单号
        $submit_order_data["sku"]        = json_encode($sku);      //sku	 | 	String	 | 	必须	 | 	[{"skuId":商品编号, "num":商品数量,"bNeedAnnex":true, "bNeedGift":true, "price":100, "yanbao":[{"skuId":商品编号}]}]
        // (最高支持50种商品)
        //bNeedAnnex表示是否需要附件，默认每个订单都给附件，默认值为：true，如果客户实在不需要附件bNeedAnnex可以给false，该参数配置为false时请谨慎，真的不会给客户发附件的;
        //bNeedGift表示是否需要增品，默认不给增品，默认值为：false，如果需要增品bNeedGift请给true,建议该参数都给true,但如果实在不需要增品可以给false;
        //price 表示透传价格，需要合同权限，接受价格权限，否则不允许传该值；
        $submit_order_data["name"] = $address['realname'];//name	 | 	String	 | 	必须	 | 	收货人

        $submit_order_data["province"] = $address['jd_vop_province_code'];//province	 | 	int	 | 	必须	 | 	一级地址
        $submit_order_data["city"]     = $address['jd_vop_city_code'];//city	 | 	int	 | 	必须	 | 	二级地址
        $submit_order_data["county"]   = $address['jd_vop_county_code'];//county	 | 	int	 | 	必须	 | 	三级地址
        $submit_order_data["town"]     = $address['jd_vop_town_code'];//town	 | 	int	 | 	必须	 | 	四级地址 (如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)

        $submit_order_data["address"] = $address['address'];//address	 | 	Stirng	 | 	必须	 | 	详细地址

        $submit_order_data["mobile"] = $address['mobile'];//mobile	 | 	Stirng	 | 	必须	 | 	手机号
        $submit_order_data["email"]  = "13760000000@qq.com";//email	 | 	Stirng	 | 	必须	 | 	邮箱
        $submit_order_data["remark"] = $remark;//remark	 | 	Stirng	 | 	非必须	 | 	备注（少于100字）


        $submit_order_data["invoiceState"]         = "2";//invoiceState	 | 	int	 | 	必须	 | 	开票方式(1为随货开票，0为订单预借，2为集中开票 )
        $submit_order_data["invoiceType"]          = "2";//invoiceType	 | 	int	 | 	必须	 | 	1普通发票2增值税发票
        $submit_order_data["selectedInvoiceTitle"] = "5";//selectedInvoiceTitle	 | 	int	 | 	必须	 | 	发票类型：4个人，5单位
        $submit_order_data["companyName"]          = "前海超级前台（深圳）信息技术有限公司";//companyName	 | 	String	 | 	必须	 | 	发票抬头 (如果selectedInvoiceTitle=5则此字段必须)
        $submit_order_data["invoiceContent"]       = "1";//invoiceContent	 | 	int	 | 	必须	 | 	1:明细，3：电脑配件，19:耗材，22：办公用品 备注:若增值发票则只能选1 明细


//        $submit_order_data["paymentType"]          = "4";//paymentType	 | 	int	 | 	必须	 | 	支付方式 (1：货到付款，2：邮局付款，4：在线支付，5：公司转账，6：银行转账，7：网银钱包，101：金采支付)
//        $submit_order_data["isUseBalance"]         = "1";//isUseBalance	 | 	int	 | 	必须	 | 	使用余额paymentType=4时，此值固定是1 其他支付方式0

        // #2176 - 2018-12-04_桑琪_企业商城_京东现由预付改为金采_月结的方式 “28+17” 28天账期 17天结算期
        $submit_order_data["paymentType"]  = "101";//paymentType	 | 	int	 | 	必须	 | 	支付方式 (1：货到付款，2：邮局付款，4：在线支付，5：公司转账，6：银行转账，7：网银钱包，101：金采支付)
        $submit_order_data["isUseBalance"] = "0";//isUseBalance	 | 	int	 | 	必须	 | 	使用余额paymentType=4时，此值固定是1 其他支付方式0

        $submit_order_data["submitState"]      = "0";//submitState	 | 	Int	 | 	必须	 | 	是否预占库存，0是预占库存（需要调用确认订单接口），1是不预占库存 金融支付必须预占库存传0
        $submit_order_data["doOrderPriceMode"] = "1";//doOrderPriceMode	 | 	int	 | 	下单价格模式	 	"0: 客户端订单价格快照不做验证对比，还是以京东价格正常下单; 1:必需验证客户端订单价格快照，如果快照与京东价格不一致返回下单失败，需要更新商品价格后，重新下单;"
        $submit_order_data["orderPriceSnap"]   = json_encode($orderPriceSnap);//orderPriceSnap	 | 	String	 | 	客户端订单价格快照	 "Json格式的数据，格式为:[{""price"":21.30,"skuId":123123 },{ "price":99.55, "skuId":22222 }] //商品价格 ,类型：BigDecimal" //商品编号,类型：long

//        zip	 | 	Stirng	 | 	非必须	 | 	邮编
//        phone	 | 	Stirng	 | 	非必须	 | 	座机号

//        invoiceName	 | 	String	 | 	非必须	 | 	增值票收票人姓名 备注：当invoiceType=2 且invoiceState=1时则此字段必填
//        invoicePhone	 | 	String	 | 	非必须	 | 	增值票收票人电话 备注：当invoiceType=2 且invoiceState=1时则此字段必填
//        invoiceProvice	 | 	int	 | 	非必须	 | 	增值票收票人所在省(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填
//        invoiceCity	 | 	int	 | 	非必须	 | 	增值票收票人所在市(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填
//        invoiceCounty	 | 	int	 | 	非必须	 | 	增值票收票人所在区/县(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填
//        invoiceAddress	 | 	String	 | 	非必须	 | 	增值票收票人所在地址 备注：当invoiceType=2 且invoiceState=1时则此字段必填
//        
//        
//        reservingDate	 | 	int	 | 	大家电配送日期	 | 	默认值为-1，0表示当天，1表示明天，2：表示后天; 如果为-1表示不使用大家电预约日历
//        installDate	 | 	int	 | 	大家电安装日期	 | 	不支持默认按-1处理，0表示当天，1表示明天，2：表示后天
//        needInstall	 | 	boolean	 | 	大家电是否选择了安装	 | 	是否选择了安装，默认为true，选择了“暂缓安装”，此为必填项，必填值为false。
//        promiseDate	 | 	String	 | 	中小件配送预约日期	 | 	格式：yyyy-MM-//        promiseTimeRange	 | 	String	 | 	中小件配送预约时间段	 | 	时间段如： 9:00-15:00
//        promiseTimeRangeCode	 | 	Integer	 | 	中小件预约时间段的标记	 |

        /*if (payconf.equals("0")) {
        }else if (payconf.equals("1")) {
            $submit_order_data["paymentType"] = "1";
        }*/

        // 入库
        $id = $this->_order_submit_orderModel->insert($submit_order_data);

        if (empty($id)) {

            return $this->message(false, '提交订单内失败');

        } else {

            $update_params = array();
            // 调用接口
            unset($submit_order_data["order_id"]);
            $response = $this->jd_sdk->api_order_submit_order($submit_order_data);

            $update_params['response'] = $response;

            $response = json_decode($response, true);

            $update_params['jd_vop_success']       = $response['success'];
            $update_params['jd_vop_resultMessage'] = $response['resultMessage'];
            $update_params['jd_vop_resultCode']    = isset($response['resultCode']) ? $response['resultCode'] : "";
            $update_params['jd_vop_code']          = isset($response['code']) ? $response['code'] : "";


            if ($response['success'] == true) {

                $update_params['jd_vop_result_jdOrderId']       = $response['result']['jdOrderId'];
                $update_params['jd_vop_result_freight']         = $response['result']['freight'];
                $update_params['jd_vop_result_orderPrice']      = $response['result']['orderPrice'];
                $update_params['jd_vop_result_orderNakedPrice'] = $response['result']['orderNakedPrice'];
                $update_params['jd_vop_result_orderTaxPrice']   = $response['result']['orderTaxPrice'];


                foreach ($response['result']['sku'] as $index => $sku) {
                    $sku['jdOrderId'] = $response['result']['jdOrderId'];

                    // update_20180329.sql N次拆单后加的字段 修正此也是为了Excel 导出时成本价的问题
//                    ADD `shop_order_id` INT(11) NOT NULL DEFAULT '0' COMMENT '商城订单ID' AFTER `oid`,
//                    ADD `shop_order_sn` VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'shop_order_sn' AFTER `shop_order_id`,
//                    ADD `shop_goods_id` INT(11) NOT NULL DEFAULT '0' COMMENT '商城商品ID' AFTER `shop_order_sn`;

                    $sku['shop_order_id'] = $order_id;
                    $sku['shop_order_sn'] = $thirdOrder;
                    $sku['shop_goods_id'] = $jd_vop_sku_id_2_shop_goods_id[$sku['skuId']];

                    $this->_order_submit_order_skuModel->insert($sku);
                }


            } else {
                // TODO return error

//success false;
//resultCode 3004;
//resultMessage [5729524]商品已下架，不能下单 ;


//success false;
//resultCode 3008;
//resultMessage 编号为2586166的赠品无货，主商品为:929693;

//ME20180327105258974110
//success false;
//resultCode 3009;
//resultMessage 【4838382】商品在该配送区域内受限;


//success false;
//resultCode 3017;
//resultMessage 您的余额不足;


            }

            $this->_order_submit_orderModel->update($update_params, $id);

            return $response;

        }


    }

    /**
     * 自动发货
     *
     * @param $uniacid
     * @param $merchid
     * @param $order_id
     *
     * @return bool
     */
    public function autoSendByShopOrderId($uniacid, $merchid, $order_id)
    {
        global $_W, $_GPC;

        // opData part start
        $item = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE id = :id ' .
            '       and uniacid=:uniacid ' .
            '       and merchid = :merchid',
            array(
                ':id'      => $order_id,
                ':uniacid' => $uniacid,
                ':merchid' => $merchid,
            )
        );

        if (empty($item)) {

            plog('order.op.send_auto',
                '订单发货 ID: ' . $order_id .
                ' 未找到订单!');
            return false;
        }

        // opData part end

        if (empty($item['addressid'])) {

            plog('order.op.send_auto',
                '订单发货 ID: ' . $order_id .
                ' 无收货地址，无法发货！');

            return false;
        }

        if ($item['paytype'] != 3) {

            if ($item['status'] != 1) {

                plog('order.op.send_auto',
                    '订单发货 ID: ' . $order_id .
                    ' 订单未付款，无法发货！');
                return false;
            }

        }

        if ($item['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {

            $_order_submit_order = $this->getJDOrderSubmitByShopOrderId($order_id);

            if ($_order_submit_order) {
                $item['express']    = 'jd';
                $item['expresssn']  = $_order_submit_order['jd_vop_result_jdOrderId'];
                $item['expresscom'] = '京东快递';
            }
        }

        // ispost part start


        $time = time();

        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'status'     => 2,
                'express'    => trim($item['express']),
                'expresscom' => trim($item['expresscom']),
                'expresssn'  => trim($item['expresssn']),
                'sendtime'   => $time,
            ),
            array(
                'id'      => $item['id'],
                'uniacid' => $uniacid,
                'merchid' => $item['merchid'],
            )
        );

        if (!empty($item['refundid'])) {

            $refund = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_order_refund') .
                ' where id=:id limit 1',
                array(
                    ':id' => $item['refundid'],
                )
            );

            if (!empty($refund)) {

                pdo_update('superdesk_shop_order_refund',
                    array(
                        'status'  => -1,
                        'endtime' => $time,
                    ),
                    array(
                        'id' => $item['refundid'],
                    )
                );

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'refundstate' => 0,
                    ),
                    array(
                        'id' => $item['id'],
                    )
                );
            }
        }

        if ($item['paytype'] == 3) {
            m('order')->setStocksAndCredits($item['id'], 1);
        }

//        m('notice')->sendOrderMessage($item['id']);//http://112.74.45.208:9000/zentao/bug-view-596.html

        plog('order.op.send_auto',
            '订单发货 ID: ' . $item['id'] .
            ' 订单号: ' . $item['ordersn'] .
            ' <br/>快递公司: ' . $item['expresscom'] .
            ' 快递单号: ' . $item['expresssn']);


        // TODO jd_vop 如果是货到付款与商户SUPERDESK_SHOPV2_JD_VOP_MERCHID 要向京东提交确认订单
        if ($item['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {
            return $this->confirmOrderByShopOrderId($item['id']);
        }

        // ispost part end

        return true;


    }

    public function getJDOrderSubmitByShopOrderId($order_id)
    {
        global $_W, $_GPC;

        $_order_submit_order = $this->_order_submit_orderModel->getOneByColumn(array(
            "order_id" => $order_id,
        ));

//        $_order_submit_order['jd_vop_result_jdOrderId']

        return $_order_submit_order;
    }


    public function confirmOrderByShopOrderId($order_id)
    {
        global $_W, $_GPC;

        $_order_submit_order = $this->_order_submit_orderModel->getOneByColumn(array(
            "order_id" => $order_id,
        ));

        if ($_order_submit_order) {

            // 确认预占库存 父订单单号 确认后大约有5S是付款状态 之前状态变成 暂停 -> 等待打印 -> 等待出库
            $result_confirm = $this->confirmOrder($_order_submit_order['jd_vop_result_jdOrderId']);


            plog('order.op.send_auto',
                '订单发货 ID: ' . $order_id .
                ' ' . ($result_confirm['resultMessage'] ? $result_confirm['resultMessage'] : json_encode($result_confirm)));

            if($_GPC['test'] == 1){
                show_json(1,$result_confirm);
            }


            // 支付 确认的预占库存 父订单单号 验证过，不用doPay
//            $result_pay = $this->doPay($_order_submit_order['jd_vop_result_jdOrderId']);

            return $result_confirm;

        }


        plog('order.op.send_auto',
            '订单发货 ID: ' . $order_id .
            ' false ');

        return false;


    }

    /**
     * 7.2  确认预占库存订单接口
     *
     * @param $jdOrderId
     *
     * @return mixed
     */
    public function confirmOrder($jdOrderId)
    {
        global $_W, $_GPC;

        $response = $this->jd_sdk->api_order_confirm_order($jdOrderId);
        $response = json_decode($response, true);
        return $response;

    }

    /**
     * 7.3  取消未确认订单接口
     * 注意事项
     * 该接口仅能取消未确认的预占库存父订单单号。不能取消已经确认的订单单号。
     * 如果需要取消已确认的订单，可以调用取消订单接口进行取消操作，参数必须为子订单才能取消 。
     *
     * @param $jdOrderId
     *
     * @return mixed
     */
    public function cancel($jdOrderId)
    {
        global $_W, $_GPC;

        $response = $this->jd_sdk->api_order_cancel($jdOrderId);
        $response = json_decode($response, true);

        return $response;

    }


    /**
     * 7.4  发起支付接口
     *
     * @param $jdOrderId
     *
     * @return mixed
     */
    public function doPay($jdOrderId)
    {
        global $_W, $_GPC;

        $response = $this->jd_sdk->api_order_do_pay($jdOrderId);
        $response = json_decode($response, true);
        //如果
        //success 为true   则代表发起支付成功
        //success 为false  则代表因为某种原因发起支付失败了
        return $response;
    }


    /**
     * 3.11  运费查询接口
     *
     * @param        $sku
     * @param        $province
     * @param        $city
     * @param        $county
     * @param int $town
     * @param string $paymentType
     *
     * @return bool
     */
    public function getFreight($sku, $province, $city, $county, $town = 0, $paymentType = "4")
    {
        global $_W, $_GPC;
//        die(json_encode($sku));
        $response = $this->jd_sdk->api_order_get_freight(json_encode($sku), $province, $city, $county, $town, $paymentType);
        $response = json_decode($response, true);
//        {"success":true,"resultMessage":"","resultCode":"0000","result":{"freight":6,"baseFreight":6,"remoteRegionFreight":0,"remoteSku":"[]"},"code":200}

//        {"freight":6,"baseFreight":6,"remoteRegionFreight":0,"remoteSku":[123456,234567]}
//        die(json_encode($response));

        if ($response['success'] == true) {
            return $response['result'];
        } else {
            return false;
        }
    }


    /**
     * 计划任务:待处理的反查订单 table.jd_vop_order_submit_order->checking_by_third = 0
     *
     * @param int $page
     * @param int $page_size
     *
     * @return string
     */
    public function runPendingJdOrderByCheckingOrder($page = 1, $page_size = 1)
    {
        global $_W, $_GPC;

        $result    = $this->_order_submit_orderModel->queryForJdVopPendingJdOrderByCheckingOrder($page, $page_size);
        $total     = $result['total'];
        $page      = $result['page'];
        $page_size = $result['page_size'];
        $list      = $result['data'];

//        print_r($list);

        if ($total == 0) {
            return "Task end : There's no more data in checking_by_third = 0";
        } else {

            foreach ($list as $index => $_jd_vop_order) {

                $this->businessProcessingSelectJdOrderIdByThirdOrder($_jd_vop_order['thirdOrder']);
            }


            return "Task total " . $total . "|" . $page_size;
        }


    }

    /**
     * 计划任务:待处理的反查订单 table.jd_vop_order_submit_order->checking_by_third = 1
     *
     * @param int $page
     * @param int $page_size
     *
     * @return string
     */
    public function runReadyJdOrderByCheckingOrder($page = 1, $page_size = 1)
    {
        global $_W, $_GPC;
        $result    = $this->_order_submit_orderModel->queryForJdVopReadyJdOrderByCheckingOrder($page, $page_size);
        $total     = $result['total'];
        $page      = $result['page'];
        $page_size = $result['page_size'];
        $list      = $result['data'];

        echo json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;

        if ($total == 0) {
            return "Task end : There's no more data in checking_by_third = 1";
        } else {

            foreach ($list as $index => $_jd_vop_order) {

                $this->businessProcessingSelectJdOrder($_jd_vop_order['jd_vop_result_jdOrderId']);
            }


            return "Task total " . $total . "|" . $page_size;
        }
    }

    /**
     * 业务 通过本商城ordersn 来反查是否正常下单了
     *
     * @param $thirdOrder
     */
    public function businessProcessingSelectJdOrderIdByThirdOrder($thirdOrder)
    {
        global $_W, $_GPC;
        $result = $this->selectJdOrderIdByThirdOrder($thirdOrder);

//        checking_by_third
//        0 待验证
//        1 已验证
//        3401 订单不存在

        if ($result['success'] == true) {


            $this->_order_submit_orderModel->updateByColumn(array(
                'checking_by_third' => 1,
            ), array(
                'thirdOrder' => $thirdOrder,
            ));


        } else {
            $this->_order_submit_orderModel->updateByColumn(array(
                'checking_by_third' => $result['resultCode'],
            ), array(
                'thirdOrder' => $thirdOrder,
            ));
        }


    }

    /**
     * 7.6  订单反查接口
     *
     * @param $thirdOrder
     *
     * @return mixed
     */
    public function selectJdOrderIdByThirdOrder($thirdOrder)
    {
        global $_W, $_GPC;

        $response = $this->jd_sdk->api_order_select_jd_order_id_by_third_order($thirdOrder);
        $response = json_decode($response, true);

//        die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        return $response;
    }


    /**
     * jdOrderId 来源于message,有可能这个订单不是本系统     * @param $jdOrderId
     * 存在返回True
     *
     * @return bool
     */
    public function checkOrderByjdOrderId($jdOrderId)
    {

        global $_W, $_GPC;
        // INIT
        $_jd_vop_submit_order = $this->_order_submit_orderModel->getOneByColumn(array(
            'jd_vop_result_jdOrderId' => $jdOrderId,
        ));

        if (!$_jd_vop_submit_order) {
            return false;
        }

        $_shop_order = $this->_orderModel->getOneByColumn(array(
            'ordersn'   => $_jd_vop_submit_order['thirdOrder'],
            'id'        => $_jd_vop_submit_order['order_id'],
            'expresssn' => $jdOrderId,
        ));

        if (!$_shop_order) {
            return false;
        }

        return true;
    }


    public function businessProcessingCompleteJdOrder($jdOrderId)
    {

        global $_W, $_GPC;

        $is_delete_message = false;

        // INIT
        $_jd_vop_submit_order = $this->_order_submit_orderModel->getOneByColumn(array(
            'jd_vop_result_jdOrderId' => $jdOrderId,
        ));

        // 如果已经是已完成，就不更新了
        $_shop_order = $this->_orderModel->getOneByColumn(array(
            'ordersn'   => $_jd_vop_submit_order['thirdOrder'],
            'id'        => $_jd_vop_submit_order['order_id'],
            'expresssn' => $jdOrderId,
            'status'    => 3,
        ));
        echo PHP_EOL;
        echo 'shop_order';
        echo PHP_EOL;
        echo json_encode($_shop_order, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if ($_shop_order == false || $_shop_order['status'] != -1) {    //2019年5月9日 15:16:42 zjh 原处理 $_shop_order == false 现处理增加  || $_shop_order['status'] != -1

            // 2019年1月15日 16:48:37 zjh 新处理方法
            // 关联 /data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/task/order/receive.php
            $this->_orderModel->updateByColumn(
                array(
                    'status'     => 3,
                    'finishtime' => time(),
                ),
                array(
                    'ordersn'   => $_jd_vop_submit_order['thirdOrder'],
                    'id'        => $_jd_vop_submit_order['order_id'],
                    'expresssn' => $jdOrderId,
                )
            );
        }

        $is_delete_message = true;


        echo PHP_EOL;
        echo PHP_EOL;

        return $this->message($is_delete_message);
    }

    /**
     * 主要是处理京东过来的取消订单
     *
     * --10 代表订单取消 不区分取消原因
     *
     * {"id":推送id, "result":{" orderId": 京东订单编号 }, "type" : 10, "time":推送时间}，
     *
     * @param $jdOrderId
     *
     * @return array
     */
    public function businessProcessingCancelJdOrder($jdOrderId, $refund_reason = '')
    {
        global $_W, $_GPC;

        $is_delete_message = false;

        // INIT
        $_jd_vop_submit_order = $this->_order_submit_orderModel->getOneByColumn(array(
            'jd_vop_result_jdOrderId' => $jdOrderId,
        ));

        $_shop_order = $this->_orderModel->getOneByColumn(array(
            'ordersn'   => $_jd_vop_submit_order['thirdOrder'],
            'id'        => $_jd_vop_submit_order['order_id'],
            'expresssn' => $jdOrderId,
        ));

        $_shop_order_keyword = array(
            'refundtime' => $_shop_order['refundtime'],
        );

        echo PHP_EOL;
//        echo 'jd_order';
//        echo PHP_EOL;
//        echo json_encode($_jd_vop_submit_order,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        echo 'shop_order';
        echo PHP_EOL;
        echo json_encode($_shop_order, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;

//        status	状态


        $order_status = array(
            -1 => '取消状态(交易关闭)',
            0  => '普通状态(没付款:待付款;付了款:待发货)',
            1  => '买家已付款(待发货)',
            2  => '卖家已发货(待收货)',
            3  => '成功(可评价:等待评价;不可评价:交易完成)',
            4  => '退款申请',
        );

        $order_paytype = array(
            0  => '为未支付',
            1  => '为余额支付',
            2  => '在线支付',
            3  => '企业月结|货到付款',
            11 => '后台付款',
            21 => '微信支付',
            22 => '支付宝支付',
            23 => '银联支付',
        );

        echo PHP_EOL;
        $msg_paytype = '支付类型:' . $_shop_order['paytype'] . ' ' . $order_paytype[$_shop_order['paytype']];
        echo $msg_paytype;
        echo PHP_EOL;
        $msg_status = '订单状态:' . $_shop_order['status'] . ' ' . $order_status[$_shop_order['status']];
        echo $msg_status;

        switch ($_shop_order['paytype']) {// paytype	支付类型

            case 0:// 0 为未支付

                // status 有可能= -1
                // TODO 主改订单为用户取消

                break;

            case 1:// 1 为余额支付 除了已退款，都能要退款


                if (!empty($_shop_order['refundtime'])) {

                    $is_delete_message = true;
                    echo '已退款';

                } else {

                    // TODO 退款申请
                    $_openid    = $_shop_order['openid'];
                    $_core_user = $_shop_order['core_user'];
                    $_orderid   = $_shop_order['id'];

//                    $refund_reason = '系统提交:京东订单取消(不区分取消原因)';

                    $refund_result_main = $this->_shopOrderService->refundMain($_W['uniacid'], $_openid, $_core_user, $_orderid);

                    echo json_encode($refund_result_main, JSON_UNESCAPED_UNICODE);

                    if ($refund_result_main['success']) {

                        $refund_price = $refund_result_main['result'];

                        $refund_result_submit = $this->_shopOrderService->refundSubmit(
                            $_W['uniacid'],
                            $_openid,
                            $_core_user,
                            $_orderid,
                            $refund_price, 0, $refund_reason, $msg_paytype . ' | ' . $msg_status);

                        echo PHP_EOL;
                        echo $refund_result_submit['resultMessage'];

                    } else {
                        echo PHP_EOL;
                        echo $refund_result_main['resultMessage'];
                    }

                    $is_delete_message = true;
                }

                // TODO 取消订单(维权->维权申请)

                break;

            case 2:// 2 在线支付

//                2是包括(21\22\23)
                break;

            case 3:// 3 企业月结 | 货到付款

                if (!empty($_shop_order['refundtime'])) {

                    $is_delete_message = true;
                    echo '已退款';

                } else {

                    // TODO 退款申请
                    $_openid    = $_shop_order['openid'];
                    $_core_user = $_shop_order['core_user'];
                    $_orderid   = $_shop_order['id'];

//                    $refund_reason = '系统提交:京东订单取消(不区分取消原因)';

                    $refund_result_main = $this->_shopOrderService->refundMain($_W['uniacid'], $_openid, $_core_user, $_orderid);

                    echo json_encode($refund_result_main, JSON_UNESCAPED_UNICODE);

                    if ($refund_result_main['success']) {

                        $refund_price = $refund_result_main['result'];

                        $refund_result_submit = $this->_shopOrderService->refundSubmit(
                            $_W['uniacid'],
                            $_openid,
                            $_core_user,
                            $_orderid,
                            $refund_price, 0, $refund_reason, $msg_paytype . ' | ' . $msg_status);

                        echo PHP_EOL;
                        echo $refund_result_submit['resultMessage'];

                    } else {
                        echo PHP_EOL;
                        echo $refund_result_main['resultMessage'];
                    }

                    $is_delete_message = true;
                }

                break;

            case 11:// 11 后台付款

                if (!empty($_shop_order['refundtime'])) {

                    $is_delete_message = true;
                    echo '已退款';

                } else {

                    // TODO 退款申请
                    $_openid    = $_shop_order['openid'];
                    $_core_user = $_shop_order['core_user'];
                    $_orderid   = $_shop_order['id'];

//                    $refund_reason = '系统提交:京东订单取消(不区分取消原因)';

                    $refund_result_main = $this->_shopOrderService->refundMain($_W['uniacid'], $_openid, $_core_user, $_orderid);

                    echo json_encode($refund_result_main, JSON_UNESCAPED_UNICODE);

                    if ($refund_result_main['success']) {

                        $refund_price = $refund_result_main['result'];

                        $refund_result_submit = $this->_shopOrderService->refundSubmit(
                            $_W['uniacid'],
                            $_openid,
                            $_core_user,
                            $_orderid,
                            $refund_price, 0, $refund_reason, $msg_paytype . ' | ' . $msg_status);

                        echo PHP_EOL;
                        echo $refund_result_submit['resultMessage'];

                    } else {
                        echo PHP_EOL;
                        echo $refund_result_main['resultMessage'];
                    }

                    $is_delete_message = true;
                }


                break;
            case 21:// 21 微信支付 除了已退款，都能要退款

                if (!empty($_shop_order['refundtime'])) {

                    echo '已退款';

                } else {
                    // TODO 退款申请
                    $_openid    = $_shop_order['openid'];
                    $_core_user = $_shop_order['core_user'];
                    $_orderid   = $_shop_order['id'];

//                    $refund_reason = '系统提交:京东订单取消(不区分取消原因)';

                    $refund_result_main = $this->_shopOrderService->refundMain($_W['uniacid'], $_openid, $_core_user, $_orderid);

                    echo json_encode($refund_result_main, JSON_UNESCAPED_UNICODE);

                    if ($refund_result_main['success']) {
                        $refund_price         = $refund_result_main['result'];
                        $refund_result_submit = $this->_shopOrderService->refundSubmit(
                            $_W['uniacid'],
                            $_openid,
                            $_core_user,
                            $_orderid,
                            $refund_price, 0, $refund_reason, $msg_paytype . ' | ' . $msg_status);

                        echo PHP_EOL;
                        echo $refund_result_submit['resultMessage'];

                    } else {
                        echo PHP_EOL;
                        echo $refund_result_main['resultMessage'];
                    }

                    $is_delete_message = true;

                }

                // TODO 取消订单(维权->维权申请)


                break;

            case 22:// 22 支付宝支付
                break;

            case 23:// 23 银联支付
                break;

        }

        return $this->message($is_delete_message);


    }

    /**
     * 业务 执行 jd_vop_order checking_by_third = 1 的检查
     *
     * @param $jdOrderId
     */
    public function businessProcessingSelectJdOrder($jdOrderId)
    {
        global $_W, $_GPC;

        $result = $this->selectJdOrder($jdOrderId);
//        die(json_encode($result , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

        if ($result['success'] == true) {

//            $result['result']['state'] // 物流状态 0:是新建 1:是妥投 2:是拒收
//            $result['result']['orderState'] // "orderState": 1,   // 订单状态  0为取消订单  1为有效
//            $result['result']['submitState'] // "submitState": 1, // 0 为 未确认下单订单 1 为 确认下单订单

            if ($result['result']['pOrder'] == 0 && $result['result']['type'] == 2/*"type": 2*/) { //为没拆单

                // TODO 基本上就是update ims_superdesk_jd_vop_order_submit_order
                $_jd_vop_submit_order                               = array();
                $_jd_vop_submit_order['jd_vop_recheck_orderState']  = $result['result']['orderState'];
                $_jd_vop_submit_order['jd_vop_recheck_submitState'] = $result['result']['submitState'];
                $_jd_vop_submit_order['checking_by_third']          = 2;// 1的话会再执行 2的意思暂时定为

//                echo(PHP_EOL.json_encode($_jd_vop_submit_order,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

                $this->_order_submit_orderModel->updateByColumn($_jd_vop_submit_order, array(
                    'jd_vop_result_jdOrderId' => $result['result']['jdOrderId'],
                ));


//                {
//
//                    "jdOrderId": 71579267022,
//                    "freight": 0,
//                    "state": 1,
//                    "orderPrice": 745,
//                    "orderNakedPrice": 636.75,
//                    "sku": [
//                        {
//                            "category": 750,
//                            "num": 5,
//                            "price": 149,
//                            "tax": 17,
//                            "oid": 0,
//                            "name": "浪木（LM）YL-106 立式单门温热饮水机",
//                            "taxPrice": 21.65,
//                            "skuId": 3250291,
//                            "nakedPrice": 127.35,
//                            "type": 0
//                        }
//                    ],
//
//                    "orderTaxPrice": 108.25
//                }

            } else if ($result['result']['type'] == 1/*"type": 1*/) { // 不然是个对像 为要拆单

//                SELECT * FROM `ims_superdesk_jd_vop_order_submit_order` WHERE jd_vop_result_jdOrderId = '71411883073' or jd_vop_recheck_pOrder = '71411883073'
//                SELECT * FROM `ims_superdesk_jd_vop_order_submit_order_sku` WHERE pOrder = '71411883073' or jdOrderId = '71411883073'

//                ME20180117190343444566
//                292

//                SELECT * FROM `ims_superdesk_jd_vop_order_submit_order` WHERE jd_vop_result_jdOrderId = '70703127395'


                // INIT 初始化 京东 order submit
                $_jd_vop_submit_order = $this->_order_submit_orderModel->getOneByColumn(array(
                    'jd_vop_result_jdOrderId' => $result['result']['pOrder']['jdOrderId'],
                ));

//                die(json_encode($_jd_vop_submit_order,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

                // INIT 初始化 商城 order
                $_shop_order = $this->_orderModel->getOneByColumn(array(
                    'ordersn'   => $_jd_vop_submit_order['thirdOrder'],
                    'id'        => $_jd_vop_submit_order['order_id'],
                    'expresssn' => $result['result']['pOrder']['jdOrderId'],
                ));


//                die(json_encode($_shop_order,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

                // TODO !!!!!订单审核在这拆分 ims_superdesk_shop_order_examine !!!!!
                $_shop_order_examine = $this->_order_examineModel->getOneByColumn(['orderid' => $_shop_order['id']]);
                // 清理 不要的字段后再 按拆单填充
                unset($_shop_order_examine['id']);
                unset($_shop_order_examine['orderid']);
                unset($_shop_order_examine['parent_order_id']);
                unset($_shop_order_examine['price']);

                // update main order isparent = 1
                $this->_order_submit_orderModel->updateByColumn(array(
                    'isparent'                   => 1,
                    'checking_by_third'          => 2, // 1的话会再执行 2的意思暂时定为
                    'remark'                     => '您订单中的商品在不同库房或属不同商家，故拆分为以下订单分开配送，敬请谅解。',
                    'jd_vop_recheck_orderState'  => $result['result']['orderState'], // 订单状态  0为取消订单  1为有效
                    'jd_vop_recheck_submitState' => $result['result']['submitState'] // 0 为 未确认下单订单 1 为 确认下单订单
                ), array(
                    'jd_vop_result_jdOrderId' => $result['result']['pOrder']['jdOrderId'],
                ));

                // shop order 1.update shop order deleted = 1
                $this->_orderModel->update(array(
                    'deleted' => 1,
                ), $_jd_vop_submit_order['order_id']);

                // TODO step 01 ims_superdesk_jd_vop_order_submit_order_sku - 人类补全计划

                // $_jd_item_c_order 结构
//                {
//                    "pOrder": 72915601854,
//                    "orderState": 1,
//                    "jdOrderId": 72957466266,
//                    "state": 0,
//                    "freight": 0,
//                    "submitState": 1,
//                    "orderPrice": 36,
//                    "orderNakedPrice": 30.77,
//                    "type": 2,
//                    "sku": [
//                        {
//                            "category": 12079,
//                            "num": 1,
//                            "price": 36,
//                            "tax": 17,
//                            "oid": 0,
//                            "name": "斯图sitoo 泡沫广告泡绵家用双面胶白色海绵双面胶带 三米长 3.6cm 8个装",
//                            "taxPrice": 5.23,
//                            "skuId": 6890028,
//                            "nakedPrice": 30.77,
//                            "type": 0
//                        }
//                    ],
//                    "orderTaxPrice": 5.23
//                }
                //$_item_corder_sku 结构
//                    {
//                        "category": 1671,
//                        "num": 5,
//                        "price": 49.9,
//                        "tax": 17,
//                        "oid": 0,
//                        "name": "洁柔（C&S）卷纸 蓝精品3层140g卫生纸*27卷（细腻光滑 整箱销售）",
//                        "taxPrice": 7.25,
//                        "skuId": 1495690,
//                        "nakedPrice": 42.65,
//                        "type": 0
//                    }

                // TODO step 01 01 ims_superdesk_jd_vop_order_submit_order 构建 jd_vop_result_jdOrderId => shop_order_id
//                $update_jd_vop_ TODO
                foreach ($result['result']['cOrder'] as $_jd_order_sku_index => $_jd_item_c_order) {


//                    echo json_encode($_jd_item_c_order,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
//                    echo PHP_EOL;


                    /**** 数据来源 拆分单->子订单  修正 table order_submit_order start ***/

                    $_shop_c_order = $_shop_order;// 大部分信息从 shop_order 继承
                    unset($_shop_c_order['id']);
                    unset($_shop_c_order['ordersn']);
                    $_shop_c_order['expresssn'] = $_jd_item_c_order['jdOrderId'];
                    $_shop_c_order['deleted']   = 0;
                    $_shop_c_order['parentid']  = $_shop_order['id'];
//                    $_shop_c_order['refundstate']   = 0;   //2019年7月25日 18:44:56 zjh 因先提交售后申请后拆单导致的售后表问题修复 还原子单的售后状态 使子单还原成未申请的情况.然后由京东推送那边处理
//                    $_shop_c_order['refundid']   = 0;   //2019年7月25日 18:44:56 zjh 因先提交售后申请后拆单导致的售后表问题修复 还原子单的售后状态 使子单还原成未申请的情况.然后由京东推送那边处理

                    // 入库 shop_order表 返回 id ordersn
                    $new_splitting_up_c_order_struct = $this->_orderModel->saveOrUpdateByColumn($_shop_c_order, array(
                        'expresssn' => $_jd_item_c_order['jdOrderId'],
                    ));


//                    echo json_encode($new_splitting_up_c_order_struct,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
//                    echo PHP_EOL;

                    // 到目前 还有 ims_superdesk_shop_order 价格未更新
                    /**** 数据来源 拆分单->子订单  修正 table order_submit_order end ***/


                    /**** 数据来源 拆分单->子订单->sku_list 修正 table order_submit_order_sku start ***/
                    foreach ($_jd_item_c_order['sku'] as $_corder_sku_index => $_item_c_order_sku) {

                        $_item_c_order_sku['pOrder']    = $_jd_item_c_order['pOrder'];
                        $_item_c_order_sku['jdOrderId'] = $_jd_item_c_order['jdOrderId'];

                        $_item_c_order_sku['shop_order_id'] = $new_splitting_up_c_order_struct['id'];
                        $_item_c_order_sku['shop_order_sn'] = $new_splitting_up_c_order_struct['ordersn'];
//                        $_item_c_order_sku['shop_goods_id'] = $this->_goodsModel->getGoodsIdBySkuId($_item_c_order_sku['skuId']);// 如果skuId对多个goodsId会有问题

                        $_item_c_order_sku['shop_goods_id'] = $this->_order_goodsModel->getGoodsIdBySkuIdAndCreatetime(
                            $_item_c_order_sku['skuId'],
                            $_shop_order['createtime'],
                            $_shop_order['openid'],
                            $_shop_order['core_user']
                        );

                        $this->_order_submit_order_skuModel->saveOrUpdateByColumn($_item_c_order_sku, array(
                            'pOrder' => $_jd_item_c_order['pOrder'],
                            'skuId'  => $_item_c_order_sku['skuId'],
                        ));

                        // TODO 构建 jd_vop_result_jdOrderId => shop_order_id
                    }
                    /**** 数据来源 拆分单->子订单->sku_list 修正 table order_submit_order_sku end ***/


                    /**** 数据来源 拆分单->子订单  修正 table order_submit_order start ***/
                    $_jd_vop_submit_c_order = $_jd_vop_submit_order;// 大部分信息从pOder继承
                    unset($_jd_vop_submit_c_order['id']);
                    $_jd_vop_submit_c_order['remark']                     = $_jd_vop_submit_c_order['remark'] . '您订单中的商品在不同库房或属不同商家，故拆分为以下订单分开配送，敬请谅解。';//　买家备注
                    $_jd_vop_submit_c_order['jd_vop_recheck_orderState']  = $_jd_item_c_order['orderState'];
                    $_jd_vop_submit_c_order['jd_vop_recheck_submitState'] = $_jd_item_c_order['submitState'];
                    $_jd_vop_submit_c_order['checking_by_third']          = 2;// 1的话会再执行 2的意思暂时定为
                    $_jd_vop_submit_c_order['jd_vop_recheck_pOrder']      = $_jd_item_c_order['pOrder'];// "pOrder": 71411883073,
//                    $_jd_vop_submit_c_order['jd_vop_success']  = 1;
                    $_jd_vop_submit_c_order['jd_vop_resultMessage'] = '拆单成功！';
//                    $_jd_vop_submit_c_order['jd_vop_resultCode']  = '0001';
//                    $_jd_vop_submit_c_order['jd_vop_code']  = 200;
                    $_jd_vop_submit_c_order['jd_vop_result_jdOrderId'] = $_jd_item_c_order['jdOrderId'];// "jdOrderId": 70612405692,
//                    $_jd_vop_submit_c_order['jd_vop_recheck_state'] = $_item_corder['state'];// "state": 1,
                    $_jd_vop_submit_c_order['jd_vop_result_freight']         = $_jd_item_c_order['freight'];// "freight": 0,
                    $_jd_vop_submit_c_order['jd_vop_result_orderPrice']      = $_jd_item_c_order['orderPrice'];// "orderPrice": 249.5,
                    $_jd_vop_submit_c_order['jd_vop_result_orderNakedPrice'] = $_jd_item_c_order['orderNakedPrice'];// "orderNakedPrice": 213.25,
//                    $_jd_vop_submit_c_order['jd_vop_recheck_type'] = $_item_corder['type'];// "type": 2,
                    $_jd_vop_submit_c_order['jd_vop_result_orderTaxPrice'] = $_jd_item_c_order['orderTaxPrice'];// "orderTaxPrice": 36.25
                    $_jd_vop_submit_c_order['isparent']                    = 0; // 1为父 0为子
                    $_jd_vop_submit_c_order['thirdOrder']                  = $new_splitting_up_c_order_struct['ordersn']; // 以下是错的 TODO thirdOrder
                    $_jd_vop_submit_c_order['order_id']                    = $new_splitting_up_c_order_struct['id']; // 以下是错的 TODO order_id
//                    $_jd_vop_submit_c_order['sku'] // sku 未拆分
//                    $_jd_vop_submit_c_order['orderPriceSnap'] // orderPriceSnap 未拆分
//                    $_jd_vop_submit_c_order['response'] // response 未拆分
                    $this->_order_submit_orderModel->saveOrUpdateByColumn($_jd_vop_submit_c_order, array(
                        'jd_vop_result_jdOrderId' => $_jd_item_c_order['jdOrderId'],
                    ));
                    /**** 数据来源 拆分单->子订单  修正 table order_submit_order end ***/

                }


                // TODO step 02 ims_superdesk_shop_goods - 再从数据库取出 更新 superdesk_shop_order_goods
                $updated_jd_order_submit_order_sku_list = $this->_order_submit_order_skuModel->queryBypOrder($result['result']['pOrder']['jdOrderId']);

                foreach ($updated_jd_order_submit_order_sku_list as $_jd_order_sku_index => $_item_c_order_sku) {

                    // TODO 修正 20190228 如果 $_item_c_order_sku['shop_goods_id'] 为 0

                    $this->_order_goodsModel->saveOrUpdateByColumnForSplitSecondTime(
                        array(
                            'parent_order_id' => $_shop_order['id'],
                            'orderid'         => $_item_c_order_sku['shop_order_id'],
                            'createtime'      => $_shop_order['createtime'],
                            'goodsid'         => $_item_c_order_sku['shop_goods_id'],
                        ),
                        array(
                            'createtime' => $_shop_order['createtime'],
                            'goodsid'    => $_item_c_order_sku['shop_goods_id'],
                            'openid'     => $_shop_order['openid'],
                            //                            'core_user'  => $_shop_order['core_user'],// 暂时屏蔽
                        )
                    );
                }


                // TODO step 03 ims_superdesk_shop_order 价格 更新 price start
                $for_shop_order_update_deleted_1 = array();

//                die('dddd'.$_shop_order['id']);
                $update_price_shop_order_goods = $this->_order_goodsModel->queryByParentIdForSplitOrderId($_shop_order['id']);
//                die(json_encode($update_price_shop_order_goods,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

                foreach ($update_price_shop_order_goods as $_shop_order_id => $_shop_c_order_item) {

                    $for_shop_order_update_deleted_1[] = $_shop_order_id;

                    $_shop_c_order_pirce                    = array();
                    $_shop_c_order_pirce['price']           = 0.00;
                    $_shop_c_order_pirce['goodsprice']      = 0.00;
                    $_shop_c_order_pirce['grprice']         = 0.00;
                    $_shop_c_order_pirce['oldprice']        = 0.00;
                    $_shop_c_order_pirce['isdiscountprice'] = 0.00;
                    $_shop_c_order_pirce['changeprice']     = 0.00;
//                    $_shop_c_order_pirce['refundprice']     = 0.00; // 退款金额不能超过' . $order['refundprice'] . '元'


                    foreach ($_shop_c_order_item as $_shop_c_order_item_goods) {

                        //2019年7月4日 14:08:22 zjh 新处理 因为价套发现的.原处理加的是未计算折扣的金额
                        $_shop_c_order_pirce['price']           += $_shop_c_order_item_goods['realprice'];// 应收款 订单金额(含运费)
                        $_shop_c_order_pirce['goodsprice']      += $_shop_c_order_item_goods['price'];// 商品金额
                        $_shop_c_order_pirce['grprice']         += $_shop_c_order_item_goods['realprice'];
                        $_shop_c_order_pirce['oldprice']        += $_shop_c_order_item_goods['oldprice'];// 原订单金额（含运费）
                        $_shop_c_order_pirce['isdiscountprice'] += $_shop_c_order_item_goods['isdiscountprice'];// v2 折扣价
                        $_shop_c_order_pirce['changeprice']     += $_shop_c_order_item_goods['changeprice'];// 订单改价金额
                        $_shop_c_order_pirce['category_enterprise_discount'] += $_shop_c_order_item_goods['category_enterprise_discount'];// 订单价套折扣金额

                        //原处理
//                        $_shop_c_order_pirce['price']           += $_shop_c_order_item_goods['price'];// 应收款 订单金额(含运费)
//                        $_shop_c_order_pirce['goodsprice']      += $_shop_c_order_item_goods['price'];// 商品金额
//                        $_shop_c_order_pirce['grprice']         += $_shop_c_order_item_goods['price'];
//                        $_shop_c_order_pirce['oldprice']        += $_shop_c_order_item_goods['oldprice'];// 原订单金额（含运费）
//                        $_shop_c_order_pirce['isdiscountprice'] += $_shop_c_order_item_goods['isdiscountprice'];// v2 折扣价
//                        $_shop_c_order_pirce['changeprice']     += $_shop_c_order_item_goods['changeprice'];// 订单改价金额

//                        $_shop_c_order_pirce['refundprice'] += $_shop_c_order_item_goods['price'];// // 退款金额不能超过' . $order['refundprice'] . '元'

                    }

                    $this->_orderModel->saveOrUpdateByColumn($_shop_c_order_pirce, array(
                        'id' => $_shop_order_id,
                    ));

                    // TODO !!!!!订单审核在这拆分 ims_superdesk_shop_order_examine !!!!!

                    $_shop_order_examine['orderid']         = $_shop_order_id;
                    $_shop_order_examine['parent_order_id'] = $_shop_order['id'];
                    $_shop_order_examine['price']           = $_shop_c_order_pirce['price'];

                    $this->_order_examineModel->saveOrUpdateByColumn($_shop_order_examine, [
                        'orderid' => $_shop_order_id,
                    ]);
                    // TODO !!!!!订单审核在这拆分 ims_superdesk_shop_order_examine !!!!!


                }
                // TODO step 03 ims_superdesk_shop_order 价格 更新 price end


                // TODO step 04 ims_superdesk_shop_order 排除更新 delete = 1 start
                // update `ims_superdesk_shop_order` set deleted = 1 where parentid = 756  and id NOT IN(839,843,844);
                $this->_orderModel->updateDeletedForSplit($_shop_order['id'], $for_shop_order_update_deleted_1);
                // TODO step 04 ims_superdesk_shop_order 排除更新 delete = 1 end

//            pOrder{
//          "jdOrderId": 71411883073,
//            "freight": 0,
//            "orderPrice": 283.82,
//            "orderNakedPrice": 242.59,
//            "sku": [
//                {
//                    "category": 7371,
//                    "num": 2,
//                    "price": 8.05,
//                    "tax": 17,
//                    "oid": 0,
//                    "name": "得力(deli)76×76mm\/400页便签纸\/便签本\/便利贴\/百事贴\/易事贴4色 7151",
//                    "taxPrice": 1.17,
//                    "skuId": 1140673,
//                    "nakedPrice": 6.88,
//                    "type": 0
//                }
//            ],
//            "orderTaxPrice": 41.23}


//                $result['result']['cOrder']
//        "cOrder": [
//            {
//                "pOrder": 71411883073,
//                "orderState": 1,
//                "jdOrderId": 70612405692,
//                "state": 1,
//                "freight": 0,
//                "submitState": 1,
//                "orderPrice": 249.5,
//                "orderNakedPrice": 213.25,
//                "type": 2,
//                "sku": [
//                    {
//                        "category": 1671,
//                        "num": 5,
//                        "price": 49.9,
//                        "tax": 17,
//                        "oid": 0,
//                        "name": "洁柔（C&S）卷纸 蓝精品3层140g卫生纸*27卷（细腻光滑 整箱销售）",
//                        "taxPrice": 7.25,
//                        "skuId": 1495690,
//                        "nakedPrice": 42.65,
//                        "type": 0
//                    }
//                ],
//                "orderTaxPrice": 36.25
//            },
//            {
//                "pOrder": 71411883073,
//                "orderState": 1,
//                "jdOrderId": 70612405724,
//                "state": 1,
//                "freight": 0,
//                "submitState": 1,
//                "orderPrice": 34.32,
//                "orderNakedPrice": 29.34,
//                "type": 2,
//                "sku": [
//                    {
//                        "category": 7371,
//                        "num": 2,
//                        "price": 8.05,
//                        "tax": 17,
//                        "oid": 0,
//                        "name": "得力(deli)76×76mm\/400页便签纸\/便签本\/便利贴\/百事贴\/易事贴4色 7151",
//                        "taxPrice": 1.17,
//                        "skuId": 1140673,
//                        "nakedPrice": 6.88,
//                        "type": 0
//                    },
//                    {
//                        "category": 2603,
//                        "num": 2,
//                        "price": 9.11,
//                        "tax": 17,
//                        "oid": 0,
//                        "name": "真彩(TRUECOLOR)0.5mm黑色中性笔 12支\/盒009",
//                        "taxPrice": 1.32,
//                        "skuId": 813770,
//                        "nakedPrice": 7.79,
//                        "type": 0
//                    }
//                ],
//                "orderTaxPrice": 4.98
//            }
//        ],

            }


        } else {
            // TODO 不处理,等recheck
        }

    }

    /**
     * 7.7  查询京东订单信息接口
     *
     * @param $jdOrderId
     *
     * @return mixed
     */
    public function selectJdOrder($jdOrderId)
    {
        global $_W, $_GPC;
        $response = $this->jd_sdk->api_order_select_jd_order($jdOrderId);
        $response = json_decode($response, true);

        echo(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $response;
    }


    /**
     * 衔接商城本来的快递查询
     *
     * @param $expresssn
     *
     * @return array
     */
    public function getExpressList($expresssn/*jdOrderId*/)
    {
        global $_W, $_GPC;
        $result = $this->orderTrack($expresssn);


        if ($result['success'] == false) {
            return array();
        } else {

            $list = array();
            foreach ($result['result']['orderTrack'] as $index => $_order_track) {
                $list[$index]['step'] = $_order_track['content'];
                $list[$index]['time'] = $_order_track['msgTime'];
            }
            return array_reverse($list);
        }


    }


    /**
     * 订单跟踪
     *
     * @param $order_id
     *
     * @return mixed
     */
    public function orderTrackByShopOrderId($order_id)
    {
        global $_W, $_GPC;
        $_order_submit_order = $this->_order_submit_orderModel->getOneByColumn(array(
            "order_id" => $order_id,
        ));

        if ($_order_submit_order) {

            $result = $this->orderTrack($_order_submit_order['jd_vop_result_jdOrderId']);


            if ($result['success'] == false) {
                $result['result']['orderTrack']   = array();
                $result['result']['orderTrack'][] = array(
                    "content"  => $result['resultMessage'],
                    "msgTime"  => date('Y-m-d H:i:s', time()),
                    "operator" => "系统",
                );
            }

            return $result['result']['orderTrack'];

//{
//    "success": true,
//    "resultMessage": "",
//    "resultCode": "0000",
//    "result": {
//    "orderTrack": [
//            {
//                "content": "您提交了订单，请等待系统确认",
//                "msgTime": "2017-12-15 16:42:44",
//                "operator": "客户"
//            }
//        ],
//    "jdOrderId": 70439621543
//    },
//    "code": 200
//}


//{
//    "success": false,
//    "resultMessage": "该订单没有配送信息",
//    "resultCode": "3401",
//    "result": null,
//    "code": 200
//}

            // TODO 处理
//            die(json_encode($result , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }

        return false;

    }


    /**
     * 7.8  查询配送信息接口
     *
     * @param $jdOrderId
     *
     * @return mixed
     */
    public function orderTrack($jdOrderId)
    {
        global $_W, $_GPC;
        $response = $this->jd_sdk->api_order_order_track($jdOrderId);
        $response = json_decode($response, true);

//        die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        return $response;
    }


}