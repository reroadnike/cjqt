<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 10/26/18
 * Time: 12:40 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=test_for_pm_id_1655
 */

//https://pm.avic-s.com/project/11/task/1655

//------------------------------------------------------------------
//发件人：陈康俊 <chenkangjun@avic-s.com>
//发送时间：2018年10月16日(星期二) 10:36
//收件人：陈文礼 <chenwenli@avic-s.com>; 曾浩 <zenghao@avic-s.com>
//抄　送：王雁峰 <wangyanfeng@avic-s.com>; 舒谦 <shuqian@avic-s.com>; 桑琪 <sangqi@avic-s.com>
//主　题：福利商城-京东供应链定价规则调整
//
//各位领导
//    见邮好。
//    因公司自建福利商城京东供应链体系里，
//    （1）商品售价规则为：协议价*112%
//    （2）商品选品规则为：协议价在8%以上折扣率以上商品上架，反之下架。
//    根据以上商品定价选品规则，导致了福利商城选品有所制约，限制了上新的品类，造成了客户不满意。据福利商城为期半年的运营统计，基础运营成本约为7.7%（市场渠道费用约为6.3%，异常订单售后成本0.2%，活动折扣成本1.2%，未计算人力成本及资金占用成本）。综上所述，为了提高商品供应链覆盖、丰富选品、提高客户满意度，特此申请调整京东供应链定价及上架规则。
//    建议调整规则：
//    （1）商品折扣率为15%以上商品，商品定价=协议价*115% 即15个点毛利率
//    （2）商品折扣率为8%-15%以内商品，商品定价=协议价*112% 即12个点毛利率
//    （2）商品折扣率为8%以下商品，商品定价=协议价*108% 即8个点毛利率
//请各位领导批复审阅


//$jdPrice = $sku_price['jdPrice'];// jdvop_京东价
//$price   = $sku_price['price'];// jdvop_协议价


$jdPrice = 1279.00;// jdvop_京东价
$price   = 1249.00;// jdvop_协议价


$discount_rate =  (floatval($jdPrice) - floatval($price)) / floatval($jdPrice)  *100;
$discount_rate = round($discount_rate,0);

echo $discount_rate;

//
//if ()

//1039840
//
//1330.60 1419.00 1124.32
//
//1270.00 1279.00 1249.00