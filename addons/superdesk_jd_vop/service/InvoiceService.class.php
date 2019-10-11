<?php


//namespace sdk\jd_vop\service;
//use sdk\jd_vop\service;
//        include(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/21/17
 * Time: 8:33 PM
 */
class InvoiceService
{

    private $jd_sdk;
    private $_redis;

    function __construct()
    {
//        $this->_redis     = new RedisUtil();
//        $this->_areaModel = new areaModel();
        $this->jd_sdk     = new JDVIPOpenPlatformSDK();
        $this->jd_sdk->debug = false;
        $this->jd_sdk->init_access_token();
    }

    /**
     *
     * 11.1  申请开票接口.v1
     *
     */
    public function submit($params = array())
    {

        $defaultParams['supplierOrder']                   = '';                   // 必须 | String | 子订单号,批量以,分割
        $defaultParams['markId']                           = '';                   // 必须 | String | 第三方申请单号:申请发票的唯一id表示(该标记下可以对应多张发票信息)

        $defaultParams['settlementId']                    = '';                   // 必须 | String | 结算单号(一个结算单号可对应多个第三方申请单号)
        $defaultParams['settlementNum']                   = '';                   // 非必须 | Integer | 结算单子订单总数
        $defaultParams['settlementNakedPrice']           = '';                   // 非必须 | BigDecimal | 结算单不含税总金额
        $defaultParams['settlementTaxPrice']             = '';                   // 非必须 | BigDecimal | 结算单总税额

        $defaultParams['invoiceType']                     = 1;                   // 必须 | Integer | 发票类型(1:普通,2:增值税)
        $defaultParams['invoiceOrg']                      = '';                   // 必须 | Integer | 开票机构ID(联系京东业务确定)
        $defaultParams['bizInvoiceContent']              = 1;                   // 必须 | Integer | 开票内容(1:明细,2:办公用品,3:电脑配件,4:耗材,5:食品,6:礼品,7:IT数码,8:通讯器材,9:体育休闲,10:饰品,11:家用电器,12:汽车用品,13:化妆品,14:玩具,15:箱包皮具,16:服装,17:劳保用品,18:图书,19:资料,20:教材,21:音像)
        $defaultParams['invoiceDate']                     = '';                   // 必须 | String | 期望开票时间
        $defaultParams['title']                            = '';                   // 必须 | String | 发票抬头(参考使用)

        $defaultParams['billToParty']                     = '';                   // 非必须 | String | 收票单位(填写开票省份)
        $defaultParams['enterpriseTaxpayer']             = '';                   // 非必须(增票必须) | String | 纳税人识别号
        $defaultParams['billToer']                        = '';                   // 非必须 | String | 收票人(无)
        $defaultParams['billToContact']                  = '';                   // 非必须 | String | 收票人联系方式(无)
        $defaultParams['billToProvince']                 = '';                   // 非必须 | Integer | 收票人地址(省)(无)
        $defaultParams['billToCity']                      = '';                  // 非必须 | Integer | 收票人地址(市)(无)
        $defaultParams['billToCounty']                   = '';                  // 非必须 | Integer | 收票人地址(区)(无)
        $defaultParams['billToTown']                      = 0;                  // 非必须(有四级地址则必传,否则传0) | Integer | 收票人地址(街道)
        $defaultParams['billToAddress']                  = '';                 // 非必须 | String | 收票人地址(详细地址)(无)

        $defaultParams['repaymentDate']                  = '';                 // 非必须(开票方式为预借时必传) | String | 预计还款时间(2013-11-8)

        $defaultParams['invoiceNum']                     = '';                 // 必须 | Integer | 当前批次子订单总数
        $defaultParams['invoiceNakedPrice']             = '';                 // 非必须 | BigDecimal | 当前批次不含税总金额(单位:元)
        $defaultParams['invoiceTaxPrice']                = '';                // 非必须 | BigDecimal | 当前批次总税额(参考用)
        $defaultParams['invoicePrice']                   = '';                // 必须 | BigDecimal | 当前批次含税总金额

        $defaultParams['currentBatch']                   = '';                // 必须 | Integer | 当前批次号
        $defaultParams['totalBatch']                     = '';                // 必须 | Integer | 总批次数
        $defaultParams['totalBatchInvoiceNakedAmount'] = '';               // 非必须 | BigDecimal | 总批次开发票金额(不含税)
        $defaultParams['totalBatchInvoiceTaxAmount']   = '';               // 非必须 | BigDecimal | 总批次开发票税额
        $defaultParams['totalBatchInvoiceAmount']      = '';               // 必须 | BigDecimal | 总批次开发票价税合计

        $defaultParams['billingType']                    = 1;               // 非必须 | Integer | 开票类型(1:集中开票,2:分别开票)(不传默认为集中开票)
        $defaultParams['isMerge']                        = 1;               // 非必须 | Integer | 合并开票(1:合并SKU,空和其他:分别开票)(不传默认为合并开票)
        $defaultParams['poNo']                           = '';              // 非必须 | String | 采购单号,长度范围[1-26]

        $params = array_merge($defaultParams,$params);

        $response = $this->jd_sdk->api_invoice_submit($params);


        $response = json_decode($response , true);

        return $response;
    }

    /**
     *
     * 11.2  查询发票信息接口
     *
     */
    public function select($markId /* 第三方申请单号：申请发票的唯一 id 标识 */)
    {
        $queryExts = ''; //queryExts 逗号间隔的参数，ignoreApplyState 忽略申请单状态过滤。

        $response = $this->jd_sdk->api_invoice_select($markId, $queryExts);

        $response = json_decode($response , true);

        return $response;
    }

    /**
     *
     * 11.3  查询发票明细接口
     *
     */
    public function query_invoice_item($invoiceId /* 发票号 */, $invoiceCode /* 发票代码 */)
    {

        $response = $this->jd_sdk->api_invoice_query_invoice_item($invoiceId, $invoiceCode);

        $response = json_decode($response , true);

        return $response;
    }

    /**
     *
     * 11.4  查询发票运单号
     *
     */
    public function waybill($markId /* 第三方申请单号：申请发票的唯一 id 标识 */)
    {

        $response = $this->jd_sdk->api_invoice_waybill($markId);

        $response = json_decode($response , true);

        return $response;
    }

    /**
     *
     * 11.6  获取电子发票信息.v1
     *
     */
    public function get_invoice_list($jdOrderId /* 订单号 */, $ivcType = 1 /* 发票类型 1 普票，2 增票，3 电子发票 */)
    {
        $queryExts = ''; //扩展参数：英文逗号间隔输入 prefixZero：增票发票号前面补齐零 electronicVAT：增票电子化，（返回独立的对象）

        $response = $this->jd_sdk->api_invoice_get_invoice_list($jdOrderId, $ivcType, $queryExts);

        $response = json_decode($response , true);

        return $response;
    }

    /**
     *
     * 11.7  订单号查询第三方申请单号.v1
     *
     */
    public function query_thr_apply_no($jdOrderId /* 订单号 */)
    {
        $response = $this->jd_sdk->api_invoice_query_thr_apply_no($jdOrderId);

        $response = json_decode($response , true);

        return $response;
    }

    /**
     *
     * 11.8  订单号查询发票物流信息.v1
     *
     */
    public function query_delivery_no($jdOrderId /* 订单号 */)
    {
        $response = $this->jd_sdk->api_invoice_query_delivery_no ($jdOrderId);

        $response = json_decode($response , true);

        return $response;
    }


}