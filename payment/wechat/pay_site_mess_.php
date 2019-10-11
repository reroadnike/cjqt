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

//WeUtility::logging('见到我就开心1111', json_encode($params));
//$havePay = pdo_get('mess_order',array('ordernum'=>$params['out_trade_no'],'type'=>1));
//if(!empty($havePay)){
//    echo '<script>location.href="'.$params['forward'].'"</script>';
//    exit;
//}


$jsApiPay = new JsApiPay();

// 统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody(            $params['order_body']);
$input->SetAttach(          "羊城同创公众号支付_面馆");
$input->SetOut_trade_no(    $params['out_trade_no']);
$input->SetTotal_fee(       $params['acount_fen']);
//$input->SetTotal_fee(       1);
$input->SetTime_start(      date("YmdHis"));
$input->SetTime_expire(     date("YmdHis", time() + 600));
$input->SetGoods_tag(       "test");
$input->SetNotify_url(      $params['notify_url']);           // 接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
$input->SetTrade_type(      "JSAPI");                         // $input->SetTrade_type("WAP");
$input->SetOpenid(          $params['openid']);

WeUtility::logging('统一下单-reqest　=> 单号 :' +$params['out_trade_no'], $input->ToXml());

// 此处要与微信通讯了, 记录发起地址
$order = WxPayApi::unifiedOrder($input);

WeUtility::logging('统一下单-response　=> 单号 :' +$params['out_trade_no'], json_encode($order));

$jsApiParameters = $jsApiPay->GetJsApiParameters($order);

//统一下单成功后，本地记录订单信息
//$params['acount'] = 1;
$orderData = array(
    'ordernum'      => $params['out_trade_no'],
    'allprice'      => $params['acount'],
    'address'       => $params['address'],
    'add_user'      => $params['name'],
    'tel'           => $params['tel'],
    'openid'        => $params['openid'],
    'date'          => date('Y-m-d H:i:s', time()),
    'deliver'       => date('Y-m-d'),
    'type'          => 0,
    'state'         => 1,
    'ordertype'     => 1,
    'conducttype'   => 4,
    'paymenttype'   => 2

    // 'paycode'=>$codeaddress,
);

pdo_insert('mess_order',$orderData);

$orderid = pdo_insertid();

pdo_update('mess_order_detailed',array('orderid'=>$orderid),array('out_trade_no'=>$params['out_trade_no']));

WeUtility::logging('统一下单-jsapi　=> 单号 :' +$params['out_trade_no'], $jsApiParameters);
WeUtility::logging('返回面对面选择商品页', $params['forward']);

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
                // WeixinJSBridge.log(res.err_msg);
                // alert(res.err_code);
                // alert(res.err_desc);
                // "get_brand_wcpay_request：ok"
                // alert(res.err_msg);
                if (res.err_msg == "get_brand_wcpay_request:ok") {
                    alert("支付成功")
                     location.href="<?php echo $params['forward'];?>";
                    // $(".confirm-order").hide();
//                    {php echo murl('entry',array('do'=>'apiHeatCompute','m'=>'site_mess'),false)}";

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
