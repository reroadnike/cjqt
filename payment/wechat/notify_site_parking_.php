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

    //查询订单
    public function Queryorder($transaction_id)
    {
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

//        WeUtility::logging('重写回调处理函数', json_encode($data));

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


        $where = array(
            'ordernum'      => $data['out_trade_no']
        );


        pdo_update('parking_order',array('type'=>1,'updatetime'=>time()),$where);

        return true;
    }
}



$notify         = new SiteMessPayNotifyCallBack();
$replyNotify    = $notify->Handle(false);

echo $replyNotify;

?>