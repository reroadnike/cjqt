<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/CategoryService.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/service/plugin/MerchService.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/service/TbuserService.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');

class Export_SuperdeskShopV2Page extends WebPage
{
    private $_categoryService;
    private $_plugin_merchService;
    private $_tbuserService;//
    private $_virtualarchitectureService;

    public function __construct($_init = true)
    {
        parent::__construct($_init);

        $this->_categoryService     = new CategoryService();
        $this->_plugin_merchService = new MerchService();


        $this->_tbuserService              = new TbuserService();
        $this->_virtualarchitectureService = new VirtualarchitectureService();

    }

    protected function field_index($columns, $field)
    {
        $index = -1;
        foreach ($columns as $i => $v) {
            if ($v['field'] == $field) {
                $index = $i;
                break;
            }
        }
        return $index;
    }

    protected function defaultColumns()
    {
        return array(

            array('title' => '商品ID', 'field' => 'id', 'width' => 24),//

            array('title' => '商户名称', 'field' => 'merch_name', 'width' => 24),//

            array('title' => '商品标题', 'field' => 'title', 'width' => 24),//
            array('title' => '商品编码', 'field' => 'goodssn', 'width' => 24),//
            array('title' => '商品条码', 'field' => 'productsn', 'width' => 24),//
            array('title' => '商品规格', 'field' => 'option_title', 'width' => 24),//
            array('title' => '商城出售价', 'field' => 'marketprice', 'width' => 24),//
            array('title' => '京东协议价(成本价)', 'field' => 'costprice', 'width' => 24),//
            array('title' => '京东SKU', 'field' => 'jd_vop_sku', 'width' => 24),//
            array('title' => '商品单位', 'field' => 'unit', 'width' => 12),//

            array('title' => '一级分类', 'field' => 'pcate_name', 'width' => 24),//
            array('title' => '二级分类', 'field' => 'ccate_name', 'width' => 24),//
            array('title' => '三级分类', 'field' => 'tcate_name', 'width' => 24),//

            array('title' => '一级分类ID', 'field' => 'pcate', 'width' => 24),//
            array('title' => '二级分类ID', 'field' => 'ccate', 'width' => 24),//
            array('title' => '三级分类ID', 'field' => 'tcate', 'width' => 24),//


            array('title' => '库存', 'field' => 'total', 'width' => 24),//
            array('title' => '销量', 'field' => 'salesreal', 'width' => 24),//
            array('title' => '状态', 'field' => 'status', 'width' => 24),//   2019年3月26日 17:56:05 zjh 宇迪要求增加

            array('title' => '规格标题', 'field' => 'option_title', 'width' => 24),//   2019年3月28日 17:09:59 zjh 宇迪要求增加
//            array('title' => '规格成本价', 'field' => 'option_costprice', 'width' => 24),//   2019年3月28日 17:09:59 zjh 宇迪要求增加
            array('title' => '规格原价', 'field' => 'option_productprice', 'width' => 24),//   2019年3月28日 17:09:59 zjh 宇迪要求增加
//            array('title' => '规格现价', 'field' => 'option_marketprice', 'width' => 24),//   2019年3月28日 17:09:59 zjh 宇迪要求增加

            array('title' => '创建时间', 'field' => 'createtime', 'width' => 24),//   2019年7月17日 14:02:12 zjh 桑淇要求增加


        );

    }


    /**
     * @url https://bmt.superdesk.cn/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=goods.export&export=1
     */
    public function main()
    {
        global $_W;
        global $_GPC;
        global $_S;

//        message('系统维护');
//        exit();

        $dflag = intval($_GPC['dflag']);

        $shop_set       = $_S['shop'];
        $templates       = ((isset($shop_set['goodstemplates']) ? $shop_set['goodstemplates'] : array()));
        $columns         = ((isset($shop_set['goodscolumns']) ? $shop_set['goodscolumns'] : array()));

//        $columns = array();

//        if (empty($columns)) {
//            if ($dflag == 0) {
//                $columns = $default_columns;
//            }
//        }


        $default_columns = $this->defaultColumns();
        foreach ($default_columns as &$dc) {
            $dc['select'] = false;
            foreach ($columns as $c) {
                if ($dc['field'] == $c['field']) {
                    $dc['select'] = true;
                    break;
                }
            }
        }
        unset($dc);


        if ($_GPC['export'] == 1) {

            plog('goods.export', '导出商品');

//            pcate =670 and deleted = 0 and status =1 and merchid = 8

            //   2019年3月26日 17:56:05 zjh 宇迪要求增加
            $condition =
                ' and g.uniacid = :uniacid ';

//            $condition .= ' and g.merchid = 8 ';//SUPERDESK_SHOPV2_JD_VOP_MERCHID

            $params = array(
                ':uniacid' => $_W['uniacid']
            );

            if (!empty($_GPC['cate'])) {
                $_GPC['cate'] = intval($_GPC['cate']);
                $condition    .= ' AND FIND_IN_SET(' . $_GPC['cate'] . ',g.cates)<>0 ';
            }

            if (!empty($_GPC['merchid'])) {
                $_GPC['merchid']    = intval($_GPC['merchid']);
                $condition          .= ' AND g.merchid = :merchid ';
                $params[':merchid'] = $_GPC['merchid'];
            }

            //   2019年3月26日 17:56:05 zjh 宇迪要求增加
            if ($_GPC['status'] == '1') {// 出售中
                $condition .= ' AND g.`status` > 0 and g.`checked`=0 and g.`total`>0 and g.`deleted`=0';
            } else if ($_GPC['status'] == '2') {// 已售罄
                $condition .= ' AND g.`status` > 0 and g.`total` <= 0 and g.`deleted`=0';
            } else if ($_GPC['status'] == '3') {// 仓库中
                $condition .= ' AND (g.`status` = 0 or g.`checked`=1) and g.`deleted`=0';
            } else if ($_GPC['status'] == '4') {// 回收站
                $condition .= ' AND g.`status` > 0 and g.`deleted`=1';
            } else if ($_GPC['status'] == '5') {// 待审核
                $condition .= ' AND g.`checked`=1 and g.`deleted`=0';
            }

            //   2019年3月26日 17:56:05 zjh 宇迪要求增加
            if(!empty($_GPC['isJd'])){
                if($_GPC['isJd'] == 1){
                    $condition          .= ' AND g.merchid = :merchid ';
                    $params[':merchid'] = SUPERDESK_SHOPV2_JD_VOP_MERCHID;
                }else if($_GPC['isJd'] == 2){
                    $condition          .= ' AND g.merchid != :merchid ';
                    $params[':merchid'] = SUPERDESK_SHOPV2_JD_VOP_MERCHID;
                }
            }

//            $condition .= ' and g.pcate = 670 ';


            $sql =
                ' select ' .
                '       g.id,g.title,g.goodssn,g.productsn, g.unit, g.total, g.salesreal, g.status, g.deleted, g.checked, g.createtime, ' .
                '       g.marketprice,' . // 商品现价
                '       g.costprice,' .   // 商品成本
                '       g.jd_vop_sku,' .
                '       g.pcate,g.ccate,g.tcate,' .
                '       c1.name as pcate_name,' .
                '       c2.name as ccate_name,' .
                '       c3.name as tcate_name, ' .
                '       merch.merchname as merch_name, ' .
                '       goption.title as option_title,goption.costprice as option_costprice,goption.productprice as option_productprice,goption.marketprice as option_marketprice ' .
                ' from ' . tablename('superdesk_shop_goods') . ' g' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' left join ' . tablename('superdesk_shop_category') . ' c1 on c1.id =g.pcate ' .
                ' left join ' . tablename('superdesk_shop_category') . ' c2 on c2.id =g.ccate ' .
                ' left join ' . tablename('superdesk_shop_category') . ' c3 on c3.id =g.tcate ' .
                ' left join ' . tablename('superdesk_shop_merch_user') . ' merch on merch.id =g.merchid ' .
                ' left join ' . tablename('superdesk_shop_goods_option') . ' goption on goption.goodsid =g.id ' .
                ' where 1 ' .
                $condition . ' ' .
                ' ORDER BY g.createtime DESC ';
//                ' Limit 1 ';

            $list = pdo_fetchall($sql, $params);

            // 修正数据显示
//            foreach ($list as &$value) {
//                $value['merch_name'] = '京东自营';
//            }
//            unset($value);

            $export_data = array();

            foreach ($list as $index => $row) {
                $goods = $row;

                if ($goods['status'] > 0 && $goods['checked'] == 0 && $goods['total'] > 0 && $goods['deleted'] == 0 ) {// 出售中
                    $goods['status'] = '出售中';
                } else if ($goods['checked'] == 1 && $goods['deleted'] == 0) {// 待审核
                    $goods['status'] = '待审核';
                } else if (($goods['status'] == 0 || $goods['checked'] == 1) && $goods['deleted'] == 0) {// 仓库中
                    $goods['status'] = '仓库中';
                } else if ($goods['status'] > 0 && $goods['deleted'] == 1) {// 回收站
                    $goods['status'] = '回收站';
                } else if ($goods['status'] > 0 && $goods['total'] <= 0 && $goods['deleted'] == 0) {// 已售罄
                    $goods['status'] = '已售罄';
                }

                $goods['costprice'] = $goods['option_costprice'] > 0 ? $goods['option_costprice'] : $goods['costprice'];
                $goods['marketprice'] = $goods['option_marketprice'] > 0 ? $goods['option_marketprice'] : $goods['marketprice'];

                $goods['createtime'] = date('Y-m-d H:i:s', $goods['createtime']);

                $export_data['row_'.$index] = $goods;
            }

            m('excel')->export(
                $export_data,
                array(
                    'title'   => '商品数据-截-' . date('Y-m-d_H_i', time()),
                    'columns' => $columns
                ),
                false
            );
        }

//        if (empty($starttime) || empty($endtime)) {
//            $starttime = strtotime('-1 month');
//            $endtime   = time();
//        }

        $categorys = m('shop')->getFullCategory(true);
        $category  = array();

        foreach ($categorys as $cate) {
            $category[$cate['id']] = $cate;
        }

        $merchs = pdo_fetchall(
            ' SELECT id,merchname FROM ' . tablename('superdesk_shop_merch_user') .
            ' WHERE uniacid=:uniacid ',array(':uniacid'=>$_W['uniacid'])
        );

        include $this->template();
    }

    public function save()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $columns = $_GPC['columns'];

        if (!is_array($columns)) {
            exit();
        }

        $data     = array(
            'goodstemplates' => $_S['shop']['goodstemplates']
        );
        $tempname = trim($_GPC['tempname']);

        if (!empty($tempname)) {
            $data['goodstemplates'][$tempname] = $columns;
        }

        $data['goodscolumns'] = $columns;

        m('common')->updateSysset(array('shop' => $data));

        if (!empty($tempname)) {
            exit(json_encode(array('templates' => array_keys($data['goodstemplates']), 'tempname' => $tempname)));
        }
        exit(json_encode(array()));
    }

    public function delete()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $data = array(
            'goodstemplates' => $_S['shop']['goodstemplates']
        );

        $tempname = trim($_GPC['tempname']);

        if (!empty($tempname)) {
            unset($data['goodstemplates'][$tempname]);
        }

        m('common')->updateSysset(array('shop' => $data));

        exit(json_encode(array(
            'templates' => array_keys($data['goodstemplates'])
        )));
    }

    public function gettemplate()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $tempname = trim($_GPC['tempname']);

        $default_columns = $this->defaultColumns();

        if (empty($tempname)) {
            $columns = array();
        } else {
            $columns = $_S['shop']['goodstemplates'][$tempname];
        }

        if (!is_array($columns)) {
            $columns = array();
        }

        $others = array();

        $selectcolumns = array();
        foreach ($default_columns as $dc) {

            $hascolumn = false;

            foreach ($columns as $c) {
                if ($dc['field'] == $c['field']) {
                    $hascolumn       = true;
                    $selectcolumns[] = $dc;
                    break;
                }
            }

            if (!$hascolumn) {
                $others[] = $dc;
            }
        }

        $data['goodscolumns'] = $selectcolumns;

        m('common')->updateSysset(array('shop' => $data));

        exit(json_encode(array(
            'columns' => $columns,
            'others'  => $others
        )));
    }

    public function reset()
    {
        global $_W;
        global $_GPC;

        $data['goodscolumns'] = array();

        m('common')->updateSysset(array('shop' => $data));

        show_json(1);
    }
}