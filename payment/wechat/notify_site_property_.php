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
        WeUtility::logging('查询订单', json_encode($transaction_id));
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


        $where = array(
            'ordernum'      => $data['out_trade_no']
        );


        // 获取订单信息并循环出缴费数组
        $payArr = array();
        $results = pdo_get('property_order',$where);
//        WeUtility::logging('返回缴费数组json', json_encode($results));

        $payArr['Syswin'][] = array(
                                    'RevID'=>$results['payid'],				    //应收款ID
                                    'RevMoney'=>$results['RevMoney'],		        //欠收本金
                                    'LFMoney'=>$results['LFMoney'],	   		    //欠收滞纳
                                    'TradingID'=>'16021412071500010064',	//收款方式ID
                                    'Trading'=>'微信缴费',					//收款方式
                                    'Filldate'=>date('Y-m-d',time()),   	//收款日期
                                    'RBank'=>'15101711200200010067',        //银行ID
                                    'RAccount'=>'710763871597',   			//银行账户
                                    'OrgID'=>$results['projectid'],               //项目ID
        );
        $payArr = json_encode($payArr);
//        WeUtility::logging('返回缴费状态json', $payArr);
//        此处开始与收费系统通讯
        libxml_disable_entity_loader(false);
        ini_set('soap.wsdl_cache_enabled', "0");
        $client                 = new SoapClient('http://121.15.154.15:8885/NetApp/CstService.asmx?wsdl');
        $payInfosArr            = array('strToken'=>"SSSySWIN*(SK_WH()",'p0'=>'UserRev_PayFeeByBank','p1'=>$payArr,'p2'=>'','p3'=>'','p4'=>'','p5'=>'','p6'=>'','p7'=>'');
        $result=$client->GetETSService($payInfosArr);
        $returnInfos            =$result->GetETSServiceResult;
        $returnInfos_decode     =json_decode($returnInfos,true);
//
//        //记录通讯后返回信息日志
        WeUtility::logging('返回缴费状态json', $returnInfos);


        //更改数据库订单状态
        if(isset($returnInfos_decode['UserRev_PayFeeByBank'][0]['State'])&&($returnInfos_decode['UserRev_PayFeeByBank'][0]['State']==1)){
            //返回1就成功了
            pdo_update('property_order',array('type'=>1),$where);
        }else{
            pdo_update('property_order',array('type'=>3),$where);
        }
        return true;
    }
}



$notify         = new SiteMessPayNotifyCallBack();
$replyNotify    = $notify->Handle(false);

echo $replyNotify;

//WeUtility::logging('wechat_pay-unifiedOrder-notify-reply-xml', $replyNotify);
?>