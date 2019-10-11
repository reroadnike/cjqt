

## 已更新到服务器


修正值
update ims_superdesk_shop_category set level = level +1 ;

这个修正主要是关于京东导入时值偏差的问题

/data/wwwroot/default/superdesk/addons/superdesk_shopv2/model/goods/goods.class.php

private function toggleSaveOrUpdateByJdVopApiProductGetDetail(&$data, $jd_vop_category)
    {

        global $_GPC, $_W;


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