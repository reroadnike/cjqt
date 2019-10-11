




bug_京东商品导入时分类问题记录
已修正程序与数据字段值

TODO 还要做个程序将全部上架京东商品加入同步队列

goods_id

132683

sku
5789185

jd_category
1320;1583;1594


cates 1594,5021
pcate 1320
ccate 1583
tcate 1594
pcates
ccates
tcates 1594,5021


select * from ims_superdesk_jd_vop_product_detail  where sku = 5789185 limit 0 ,1;

select * from ims_superdesk_shop_goods where jd_vop_sku = 5789185;

select * from ims_superdesk_shop_category where id = 1320 or id = 1583 or id = 1594;

update ims_superdesk_shop_category set level = level +1 ;



修正值
update ims_superdesk_shop_category set level = level +1 ;

这个修正主要是关于京东分类同步时值偏差,导致商品分类偏差

/data/wwwroot/default/superdesk/addons/superdesk_shopv2/model/goods/goods.class.php

private function toggleSaveOrUpdateByJdVopApiProductGetDetail(&$data, $jd_vop_category)
    {

        global $_GPC, $_W;

//        include_once(MODULE_ROOT . '/../superdesk_shopv2/model/category.class.php');
//        $_categoryModel = new categoryModel();
//        $_goods_cate_id = $_categoryModel->getIdByColumnJdVopPageNum($page_num);

        $jd_vop_category = explode(";", $jd_vop_category);

        $cates   = array();
        $cates[] = $jd_vop_category[2];

        $cateset = m('common')->getSysset('shop');

        $pcates = array();
        $ccates = array();
        $tcates = array();
        $fcates = array();

        $pcateid = 0;
        $ccateid = 0;
        $tcateid = 0;

        $pcateid = $jd_vop_category[0];
        $ccateid = $jd_vop_category[1];
        $tcateid = $jd_vop_category[2];


        // TODO 查看后台页面传什么过来 可以选多个分类
//    var_dump($_GPC['cates']);
//    array(5) {
//            [0]=> string(2) "38"
//            [1]=> string(2) "39"
//            [2]=> string(2) "41"
//            [3]=> string(2) "42"
//            [4]=> string(2) "10"
//    }

        if (is_array($cates)) {

            foreach ($cates as $key => $cid) {

                $c = pdo_fetch(
                    ' select level ' .
                    ' from ' . tablename('superdesk_shop_category') .
                    ' where id=:id ' .
                    '       and uniacid=:uniacid limit 1',
                    array(
                        ':id'      => $cid,
                        ':uniacid' => $_W['uniacid']
                    )
                );

                if ($c['level'] == 1) {

                    $pcates[] = $cid;

                } else if ($c['level'] == 2) {

                    $ccates[] = $cid;

                } else if ($c['level'] == 3) {

                    $tcates[] = $cid;

                }

                if ($key == 0) {
                    if ($c['level'] == 1) {

                        $pcateid = $cid;

                    } else if ($c['level'] == 2) {

                        $crow = pdo_fetch(
                            ' select parentid ' .
                            ' from ' . tablename('superdesk_shop_category') .
                            ' where id=:id ' .
                            '       and uniacid=:uniacid limit 1',
                            array(
                                ':id'      => $cid,
                                ':uniacid' => $_W['uniacid']
                            )
                        );

                        $pcateid = $crow['parentid'];
                        $ccateid = $cid;

                    } else if ($c['level'] == 3) {

                        $tcateid = $cid;
                        $tcate   = pdo_fetch(
                            ' select id,parentid ' .
                            ' from ' . tablename('superdesk_shop_category') .
                            ' where id=:id and uniacid=:uniacid limit 1',
                            array(
                                ':id'      => $cid,
                                ':uniacid' => $_W['uniacid']
                            )
                        );

                        $ccateid = $tcate['parentid'];
                        $ccate   = pdo_fetch(
                            ' select id,parentid ' .
                            ' from ' . tablename('superdesk_shop_category') .
                            ' where id=:id and uniacid=:uniacid limit 1',
                            array(
                                ':id'      => $ccateid,
                                ':uniacid' => $_W['uniacid']
                            )
                        );

                        $pcateid = $ccate['parentid'];
                    }
                }
            }
        }

        $data['pcate'] = $pcateid;
        $data['ccate'] = $ccateid;
        $data['tcate'] = $tcateid;

        $data['cates'] = implode(',', $cates);

        $data['pcates'] = implode(',', $pcates);
        $data['ccates'] = implode(',', $ccates);
        $data['tcates'] = implode(',', $tcates);

//        echo "<br>";
//        echo "===================================== debug ==================================================";
//        echo "<br>";
//        echo "" . " : " . " page_num " . " : ". $page_num . " goods_cate_id " . " : " . $_goods_cate_id . "    ". json_encode($data);
//        echo "<br>";
//        echo "===================================== debug ==================================================";
//        echo "<br>";
    }