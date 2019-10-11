<?php
/**
 * Created by phpstorm.
 * User: LZP
 * Date: 2019/10/9
 * Time: 16:50
 * 商品报表
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Goods_SuperdeskShopV2Page extends WebPage {

    public function main() {
        global $_W;
        global $_GPC;
        $params = array(':uniacid' => $_W['uniacid']);

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        //获取商户列表
        $merch = pdo_fetchall('SELECT id,merchname FROM '.tablename('superdesk_shop_merch_user').' WHERE status !=2');

        $condition = '';
        $wherecondition = '';

        $condition .= ' LEFT JOIN ' . tablename('superdesk_shop_merch_user') . ' m ON m.`id`= g.`merchid` ' ;

        $condition .= ' LEFT JOIN ' . tablename('superdesk_shop_goods') . ' go ON go.`id`=g.`goodsid` ' ;


        if($_GPC['merch']) {
            $wherecondition .= ' AND g.`merchid` = ' . $_GPC['merch'];
        }

        //时间搜索
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }


        if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {

            $starttime = strtotime($_GPC['time']['start']);
            $endtime   = strtotime($_GPC['time']['end']);

            $wherecondition .=
                ' AND g.`createtime` >= :starttime ' .
                ' AND g.`createtime` <= :endtime ';

            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }

        if (empty($_GPC['export'])) {
            $goods = pdo_fetchall('SELECT g.*,m.uniacid,m.merchname,go.title,go.thumb FROM ' . tablename('superdesk_shop_comments_report_goods') . ' g ' . $condition . ' WHERE ' . ' m.uniacid = :uniacid ' . $wherecondition . ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
        } else {
            $goods = pdo_fetchall('SELECT g.*,m.uniacid,m.merchname,go.title,go.thumb FROM ' . tablename('superdesk_shop_comments_report_goods') . ' g ' . $condition . ' WHERE ' . ' m.uniacid = :uniacid ' . $wherecondition , $params);

        }

        //导出excel
        if ($_GPC['export'] == '1') {
            plog('order.goods', '导出商品报表数据');

            foreach ($goods as &$v) {
                $v['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
            }

            m('excel')->export($goods,array(
                'title' => '商品报表数据-' . date('Y-m-d-H-i', time()),
                'columns' => array(
                    array('title' => '商户名', 'field' => 'merchname', 'width' => 12),
                    array('title' => '商品名', 'field' => 'title', 'width' => 12),
                    array('title' => '质量评分', 'field' => 'com_level', 'width' => 12),
                    array('title' => '备注', 'field' => 'remark', 'width' => 24),
                    array('title' => '统计时间', 'field' => 'createtime', 'width' => 24),
                )
            ));
        }

        $total = pdo_fetchcolumn('SELECT count(1) FROM ' . tablename('superdesk_shop_comments_report_goods') . ' g ' . $condition .  ' WHERE ' . ' m.uniacid = :uniacid '  . $wherecondition ,$params);

        $pager = pagination($total, $pindex, $psize);

        include $this->template();
    }
}