<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 16-4-12
 * Time: 下午6:41
 */

define('IN_MOBILE', true);


require_once 'wxpay/WxPay.Data.php';
require_once 'wxpay/WxPay.Api.php';
require_once 'wxpay/WxPay.JsApiPay.php';
require_once 'wxpay/log.php';

require '../../framework/bootstrap.inc.php';
//require '../../app/common/bootstrap.app.inc.php';


$sl = $_GPC['ps'];
$params = @json_decode(base64_decode($sl), true);

$havePay = pdo_get('apartment_order',array('ordernum'=>$params['out_trade_no'],'type'=>1));
if(!empty($havePay)){
    echo '<script>location.href="'.$params['forward'].'"</script>';
    exit;
}

//$params['acount_fen'] = 1;
$jsApiPay = new JsApiPay();

// 统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody(            $params['order_body']);
$input->SetAttach(          $params['order_attach']);
$input->SetOut_trade_no(    $params['out_trade_no']);
$input->SetTotal_fee(       $params['acount_fen']);
$input->SetTime_start(      date("YmdHis"));
$input->SetTime_expire(     date("YmdHis", time() + 600));
$input->SetGoods_tag(       "test");
$input->SetNotify_url(      $params['notify_url']);           // 接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
$input->SetTrade_type(      "JSAPI");                         // $input->SetTrade_type("WAP");
$input->SetOpenid(          $params['openid']);



// 此处要与微信通讯了, 记录发起地址
$order = WxPayApi::unifiedOrder($input);


$jsApiParameters = $jsApiPay->GetJsApiParameters($order);

//统一下单成功后，本地记录订单信息
$time = time();
$orderData = array(
    'ordernum'      =>$params['out_trade_no'],
    'date'          =>date('Y-m-d H:i:s',$time),
    'username'      =>$params['customerName'],
    'tel'           =>$params['customerTel'],
    'remark'        =>$params['customerRemark'],
    'house'         =>$params['customerHouse'],
    'costtype'      =>$params['customerType'],
    'openid'        =>$params['openid'],
    'uniacid'       =>$params['uniacid'],
    'money'         =>$params['acount'],
    'updatetime'    =>$time,
    'createtime'    =>$time,
);

pdo_insert('apartment_order',$orderData);

WeUtility::logging('支付字符串', $jsApiParameters);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>加载中...</title>
<!--    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>-->
</head>
<body>
<script>


    //////微信支付调用//////////////////////调用微信JS api 支付
    function jsApiCall() {
        WeixinJSBridge.invoke(
            　'getBrandWCPayRequest'
            ,　<?php echo $jsApiParameters;?>
            , function (res) {
                if (res.err_msg == "get_brand_wcpay_request:ok") {
                    alert("成功了")
                     location.href="<?php echo $params['forward'];?>";
                } else {
                    alert("支付失败!")
                    location.href="<?php echo $params['forward'];?>"
                }
            }
        );
    }

    function callpay() {

        if (typeof WeixinJSBridge == "undefined") {
            if (document.addEventListener) {
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            } else if (document.attachEvent) {
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        } else {
            jsApiCall();
        }
    }

    callpay();
</script>
</body>
</html>
