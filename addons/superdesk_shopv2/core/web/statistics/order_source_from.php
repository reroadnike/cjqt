<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/2/18
 * Time: 3:35 PM
 *
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=statistics.order_source_from
 */

//SELECT COUNT(source_from) FROM `ims_superdesk_shop_order` WHERE status = 3 GROUP BY source_from




if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Order_source_from_SuperdeskShopV2Page extends WebPage
{


    public function main()
    {
        global $_W;
        global $_GPC;
        $render = array(
            'pc' => '电脑端',
            'wechat' => '微信端'
        );

        $condition_01 = ' and o.uniacid=:uniacid and o.status>=0 and createtime > 1531584000 ';
        $condition_02 = ' and o.uniacid=:uniacid and o.status=3  and createtime > 1531584000 ';

        $params    = array(':uniacid' => $_W['uniacid']);



        $condition_01 .= ' and o.deleted = 0 group by o.source_from';
        $condition_02 .= ' and o.deleted = 0 group by o.source_from';

        $sql_01 =
            ' select o.source_from as name, COUNT(o.source_from) as y ' .
            ' from ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where 1 ' . $condition_01 . ' ';
        $sql_02 =
            ' select o.source_from as name, COUNT(o.source_from) as y ' .
            ' from ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where 1 ' . $condition_02 . ' ';




        $result_01 = pdo_fetchall($sql_01, $params);
        $result_02 = pdo_fetchall($sql_02, $params);

        $total_01 = 0;
        $total_02 = 0;

        foreach ($result_01 as $index => $item){
            $total_01 += intval($item['y']);
        }
        foreach ($result_01 as $index => $item){

            $result_01[$index]['y'] = floatval(number_format(floatval($result_01[$index]['y']/$total_01),2));
            $result_01[$index]['name'] = $render[$result_01[$index]['name']];
        }
        foreach ($result_02 as $index => $item){
            $total_02 += intval($item['y']);
        }
        foreach ($result_02 as $index => $item){
            $result_02[$index]['y'] = floatval(number_format(floatval($result_02[$index]['y']/$total_02),2));
            $result_02[$index]['name'] = $render[$result_02[$index]['name']];
        }




        load()->func('tpl');
        include $this->template('statistics/order_source_from');
    }
}


