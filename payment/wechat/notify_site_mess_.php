<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 16-4-12
 * Time: 下午5:36
 */

require '../../framework/bootstrap.inc.php';
require 'wxpay/WxPay.Notify.php';
require_once 'wxpay/log.php';

class SiteMessPayNotifyCallBack extends WxPayNotify
{
    function __construct(){
        WeUtility::logging('NEW', 'SiteMessPayNotifyCallBack');
    }
    //查询订单
    public function Queryorder($transaction_id)
    {
//        WeUtility::logging('查询订单', json_encode($result));
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);



        if (array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS"
        ) {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {

        WeUtility::logging('重写回调处理函数', json_encode($data));

        $notfiyOutput = array();

        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }

        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }
        $out_trade_no = $msg;
        $params = array(
            'paymenttype'   => 1,
            'conducttype'   => 4,
            'openid'        => $data['openid']
        );
        $where = array(
            'ordernum'      => $data['out_trade_no']
        );

        pdo_update('mess_order', $params, $where);
//        $lk = mysqli_connect('skycloud.mysql.rds.aliyuncs.com','yt','geiwo120');
//        $ne = mysqli_query($lk,"set names 'utf8'");
//        $da = mysqli_select_db($lk,'yt');
//
//
//        mysqli_query($lk,'UPDATE `mess_order` SET paymenttype = 1,conducttype = 4,openid ="'.$data['openid'].'"  WHERE ordernum = "'.$data['out_trade_no'].'" ');
//
//        mysqli_close($lk);


        return true;
    }
}


WeUtility::logging('微信回调', '开始');

$notify         = new SiteMessPayNotifyCallBack();
$replyNotify    = $notify->Handle(false);

echo $replyNotify;

WeUtility::logging('wechat_pay-unifiedOrder-notify-reply-xml', $replyNotify);


WeUtility::logging('微信回调', '结束');
?>