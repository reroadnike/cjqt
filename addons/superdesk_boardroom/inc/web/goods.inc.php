<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:40 AM
 */

global $_GPC, $_W;
load()->func('tpl');

$sql = 'SELECT * FROM ' . tablename('superdesk_boardroom_s_category') . ' WHERE `uniacid` = :uniacid ORDER BY `parentid`, `displayorder` DESC';
$category = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']), 'id');
if (!empty($category)) {
    $parent = $children = array();
    foreach ($category as $cid => $cate) {
        if (!empty($cate['parentid'])) {
            $children[$cate['parentid']][] = $cate;
        } else {
            $parent[$cate['id']] = $cate;
        }
    }
}

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $item = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE id = :id", array(':id' => $id));
        if (empty($item)) {
            message('抱歉，商品不存在或是已经删除！', '', 'error');
        }
        $allspecs = pdo_fetchall("SELECT * FROM " . TABLENAME('superdesk_boardroom_s_spec') . " WHERE goodsid=:id ORDER BY displayorder ASC", array(":id" => $id));
        foreach ($allspecs as &$s) {
            $s['items'] = pdo_fetchall("select * from " . tablename('superdesk_boardroom_s_spec_item') . " where specid=:specid order by displayorder asc", array(":specid" => $s['id']));
        }
        unset($s);
        $params = pdo_fetchall("select * from " . tablename('superdesk_boardroom_s_goods_param') . " where goodsid=:id order by displayorder asc", array(':id' => $id));
        $piclist1 = unserialize($item['thumb_url']);
        $piclist = array();
        if (is_array($piclist1)) {
            foreach ($piclist1 as $p) {
                $piclist[] = is_array($p) ? $p['attachment'] : $p;
            }
        }
        //处理规格项
        $html = "";
        $options = pdo_fetchall("select * from " . tablename('superdesk_boardroom_s_goods_option') . " where goodsid=:id order by id asc", array(':id' => $id));
        //排序好的specs
        $specs = array();
        //找出数据库存储的排列顺序
        if (count($options) > 0) {
            $specitemids = explode("_", $options[0]['specs']);
            foreach ($specitemids as $itemid) {
                foreach ($allspecs as $ss) {
                    $items = $ss['items'];
                    foreach ($items as $it) {
                        if ($it['id'] == $itemid) {
                            $specs[] = $ss;
                            break;
                        }
                    }
                }
            }
            $html = '';
            $html .= '<table class="table table-bordered table-condensed">';
            $html .= '<thead>';
            $html .= '<tr class="active">';
            $len = count($specs);
            $newlen = 1; //多少种组合
            $h = array(); //显示表格二维数组
            $rowspans = array(); //每个列的rowspan
            for ($i = 0; $i < $len; $i++) {
                //表头
                $html .= "<th style='width:80px;'>" . $specs[$i]['title'] . "</th>";
                //计算多种组合
                $itemlen = count($specs[$i]['items']);
                if ($itemlen <= 0) {
                    $itemlen = 1;
                }
                $newlen *= $itemlen;
                //初始化 二维数组
                $h = array();
                for ($j = 0; $j < $newlen; $j++) {
                    $h[$i][$j] = array();
                }
                //计算rowspan
                $l = count($specs[$i]['items']);
                $rowspans[$i] = 1;
                for ($j = $i + 1; $j < $len; $j++) {
                    $rowspans[$i] *= count($specs[$j]['items']);
                }
            }
            $html .= '<th class="info" style="width:130px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">库存</div><div class="input-group"><input type="text" class="form-control option_stock_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_stock\');"></a></span></div></div></th>';
            $html .= '<th class="success" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">销售价格</div><div class="input-group"><input type="text" class="form-control option_marketprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_marketprice\');"></a></span></div></div></th>';
            $html .= '<th class="warning" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">市场价格</div><div class="input-group"><input type="text" class="form-control option_productprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_productprice\');"></a></span></div></div></th>';
            $html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">成本价格</div><div class="input-group"><input type="text" class="form-control option_costprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_costprice\');"></a></span></div></div></th>';
            $html .= '<th class="info" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">重量（克）</div><div class="input-group"><input type="text" class="form-control option_weight_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_weight\');"></a></span></div></div></th>';
            $html .= '</tr></thead>';
            for ($m = 0; $m < $len; $m++) {
                $k = 0;
                $kid = 0;
                $n = 0;
                for ($j = 0; $j < $newlen; $j++) {
                    $rowspan = $rowspans[$m];
                    if ($j % $rowspan == 0) {
                        $h[$m][$j] = array("html" => "<td rowspan='" . $rowspan . "'>" . $specs[$m]['items'][$kid]['title'] . "</td>", "id" => $specs[$m]['items'][$kid]['id']);
                    } else {
                        $h[$m][$j] = array("html" => "", "id" => $specs[$m]['items'][$kid]['id']);
                    }
                    $n++;
                    if ($n == $rowspan) {
                        $kid++;
                        if ($kid > count($specs[$m]['items']) - 1) {
                            $kid = 0;
                        }
                        $n = 0;
                    }
                }
            }
            $hh = "";
            for ($i = 0; $i < $newlen; $i++) {
                $hh .= "<tr>";
                $ids = array();
                for ($j = 0; $j < $len; $j++) {
                    $hh .= $h[$j][$i]['html'];
                    $ids[] = $h[$j][$i]['id'];
                }
                $ids = implode("_", $ids);
                $val = array("id" => "", "title" => "", "stock" => "", "costprice" => "", "productprice" => "", "marketprice" => "", "weight" => "");
                foreach ($options as $o) {
                    if ($ids === $o['specs']) {
                        $val = array(
                            "id" => $o['id'],
                            "title" => $o['title'],
                            "stock" => $o['stock'],
                            "costprice" => $o['costprice'],
                            "productprice" => $o['productprice'],
                            "marketprice" => $o['marketprice'],
                            "weight" => $o['weight']
                        );
                        break;
                    }
                }
                $hh .= '<td class="info">';
                $hh .= '<input name="option_stock_' . $ids . '[]"  type="text" class="form-control option_stock option_stock_' . $ids . '" value="' . $val['stock'] . '"/></td>';
                $hh .= '<input name="option_id_' . $ids . '[]"  type="hidden" class="form-control option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
                $hh .= '<input name="option_ids[]"  type="hidden" class="form-control option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
                $hh .= '<input name="option_title_' . $ids . '[]"  type="hidden" class="form-control option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
                $hh .= '</td>';
                $hh .= '<td class="success"><input name="option_marketprice_' . $ids . '[]" type="text" class="form-control option_marketprice option_marketprice_' . $ids . '" value="' . $val['marketprice'] . '"/></td>';
                $hh .= '<td class="warning"><input name="option_productprice_' . $ids . '[]" type="text" class="form-control option_productprice option_productprice_' . $ids . '" " value="' . $val['productprice'] . '"/></td>';
                $hh .= '<td class="danger"><input name="option_costprice_' . $ids . '[]" type="text" class="form-control option_costprice option_costprice_' . $ids . '" " value="' . $val['costprice'] . '"/></td>';
                $hh .= '<td class="info"><input name="option_weight_' . $ids . '[]" type="text" class="form-control option_weight option_weight_' . $ids . '" " value="' . $val['weight'] . '"/></td>';
                $hh .= '</tr>';
            }
            $html .= $hh;
            $html .= "</table>";
        }
    }
    if (empty($category)) {
        message('抱歉，请您先添加商品分类！', $this->createWebUrl('category', array('op' => 'post')), 'error');
    }
    if (checksubmit('submit')) {
        if (empty($_GPC['goodsname'])) {
            message('请输入商品名称！');
        }
        if (empty($_GPC['category']['parentid'])) {
            message('请选择商品分类！');
        }
        if (empty($_GPC['thumbs'])) {
            $_GPC['thumbs'] = array();
        }
        $data = array(
            'uniacid' => intval($_W['uniacid']),
            'displayorder' => intval($_GPC['displayorder']),
            'title' => $_GPC['goodsname'],
            'pcate' => intval($_GPC['category']['parentid']),
            'ccate' => intval($_GPC['category']['childid']),
            'thumb' => $_GPC['thumb'],
            'type' => intval($_GPC['type']),
            'isrecommand' => intval($_GPC['isrecommand']),
            'ishot' => intval($_GPC['ishot']),
            'isnew' => intval($_GPC['isnew']),
            'isdiscount' => intval($_GPC['isdiscount']),
            'istime' => intval($_GPC['istime']),
            'timestart' => strtotime($_GPC['timestart']),
            'timeend' => strtotime($_GPC['timeend']),
            'description' => $_GPC['description'],
            'content' => htmlspecialchars_decode($_GPC['content']),
            'goodssn' => $_GPC['goodssn'],
            'unit' => $_GPC['unit'],
            'createtime' => TIMESTAMP,
            'total' => intval($_GPC['total']),
            'totalcnf' => intval($_GPC['totalcnf']),
            'marketprice' => $_GPC['marketprice'],
            'weight' => $_GPC['weight'],
            'costprice' => $_GPC['costprice'],
            'originalprice' => $_GPC['originalprice'],
            'productprice' => $_GPC['productprice'],
            'productsn' => $_GPC['productsn'],
            'credit' => sprintf('%.2f', $_GPC['credit']),
            'maxbuy' => intval($_GPC['maxbuy']),
            'usermaxbuy' => intval($_GPC['usermaxbuy']),
            'hasoption' => intval($_GPC['hasoption']),
            'sales' => intval($_GPC['sales']),
            'status' => intval($_GPC['status']),
        );
        if ($data['total'] === -1) {
            $data['total'] = 0;
            $data['totalcnf'] = 2;
        }

        if (is_array($_GPC['thumbs'])) {
            $data['thumb_url'] = serialize($_GPC['thumbs']);
        }
        if (empty($id)) {
            pdo_insert('superdesk_boardroom_s_goods', $data);
            $id = pdo_insertid();
        } else {
            unset($data['createtime']);
            pdo_update('superdesk_boardroom_s_goods', $data, array('id' => $id));
        }
        $totalstocks = 0;
        //处理自定义参数
        $param_ids = $_POST['param_id'];
        $param_titles = $_POST['param_title'];
        $param_values = $_POST['param_value'];
        $param_displayorders = $_POST['param_displayorder'];
        $len = count($param_ids);
        $paramids = array();
        for ($k = 0; $k < $len; $k++) {
            $param_id = "";
            $get_param_id = $param_ids[$k];
            $a = array(
                "title" => $param_titles[$k],
                "value" => $param_values[$k],
                "displayorder" => $k,
                "goodsid" => $id,
            );
            if (!is_numeric($get_param_id)) {
                pdo_insert("superdesk_boardroom_s_goods_param", $a);
                $param_id = pdo_insertid();
            } else {
                pdo_update("superdesk_boardroom_s_goods_param", $a, array('id' => $get_param_id));
                $param_id = $get_param_id;
            }
            $paramids[] = $param_id;
        }
        if (count($paramids) > 0) {
            pdo_query("delete from " . tablename('superdesk_boardroom_s_goods_param') . " where goodsid=$id and id not in ( " . implode(',', $paramids) . ")");
        } else {
            pdo_query("delete from " . tablename('superdesk_boardroom_s_goods_param') . " where goodsid=$id");
        }
//				if ($totalstocks > 0) {
//					pdo_update("superdesk_boardroom_s_goods", array("total" => $totalstocks), array("id" => $id));
//				}
        //处理商品规格
        $files = $_FILES;
        $spec_ids = $_POST['spec_id'];
        $spec_titles = $_POST['spec_title'];
        $specids = array();
        $len = count($spec_ids);
        $specids = array();
        $spec_items = array();
        for ($k = 0; $k < $len; $k++) {
            $spec_id = "";
            $get_spec_id = $spec_ids[$k];
            $a = array(
                "uniacid" => $_W['uniacid'],
                "goodsid" => $id,
                "displayorder" => $k,
                "title" => $spec_titles[$get_spec_id]
            );
            if (is_numeric($get_spec_id)) {
                pdo_update("superdesk_boardroom_s_spec", $a, array("id" => $get_spec_id));
                $spec_id = $get_spec_id;
            } else {
                pdo_insert("superdesk_boardroom_s_spec", $a);
                $spec_id = pdo_insertid();
            }
            //子项
            $spec_item_ids = $_POST["spec_item_id_" . $get_spec_id];
            $spec_item_titles = $_POST["spec_item_title_" . $get_spec_id];
            $spec_item_shows = $_POST["spec_item_show_" . $get_spec_id];
            $spec_item_thumbs = $_POST["spec_item_thumb_" . $get_spec_id];
            $spec_item_oldthumbs = $_POST["spec_item_oldthumb_" . $get_spec_id];
            $itemlen = count($spec_item_ids);
            $itemids = array();
            for ($n = 0; $n < $itemlen; $n++) {
                $item_id = "";
                $get_item_id = $spec_item_ids[$n];
                $d = array(
                    "uniacid" => $_W['uniacid'],
                    "specid" => $spec_id,
                    "displayorder" => $n,
                    "title" => $spec_item_titles[$n],
                    "show" => $spec_item_shows[$n],
                    "thumb" => $spec_item_thumbs[$n]
                );
                $f = "spec_item_thumb_" . $get_item_id;
                if (is_numeric($get_item_id)) {
                    pdo_update("superdesk_boardroom_s_spec_item", $d, array("id" => $get_item_id));
                    $item_id = $get_item_id;
                } else {
                    pdo_insert("superdesk_boardroom_s_spec_item", $d);
                    $item_id = pdo_insertid();
                }
                $itemids[] = $item_id;
                //临时记录，用于保存规格项
                $d['get_id'] = $get_item_id;
                $d['id'] = $item_id;
                $spec_items[] = $d;
            }
            //删除其他的
            if (count($itemids) > 0) {
                pdo_query("delete from " . tablename('superdesk_boardroom_s_spec_item') . " where uniacid={$_W['uniacid']} and specid=$spec_id and id not in (" . implode(",", $itemids) . ")");
            } else {
                pdo_query("delete from " . tablename('superdesk_boardroom_s_spec_item') . " where uniacid={$_W['uniacid']} and specid=$spec_id");
            }
            //更新规格项id
            pdo_update("superdesk_boardroom_s_spec", array("content" => serialize($itemids)), array("id" => $spec_id));
            $specids[] = $spec_id;
        }
        //删除其他的
        if (count($specids) > 0) {
            pdo_query("delete from " . tablename('superdesk_boardroom_s_spec') . " where uniacid={$_W['uniacid']} and goodsid=$id and id not in (" . implode(",", $specids) . ")");
        } else {
            pdo_query("delete from " . tablename('superdesk_boardroom_s_spec') . " where uniacid={$_W['uniacid']} and goodsid=$id");
        }
        //保存规格
        $option_idss = $_POST['option_ids'];
        $option_productprices = $_POST['option_productprice'];
        $option_marketprices = $_POST['option_marketprice'];
        $option_costprices = $_POST['option_costprice'];
        $option_stocks = $_POST['option_stock'];
        $option_weights = $_POST['option_weight'];
        $len = count($option_idss);
        $optionids = array();
        for ($k = 0; $k < $len; $k++) {
            $option_id = "";
            $ids = $option_idss[$k];
            $idsarr = explode("_", $ids);
            $get_option_id = $_GPC['option_id_' . $ids][0];
            $newids = array();
            foreach ($idsarr as $key => $ida) {
                foreach ($spec_items as $it) {
                    if ($it['get_id'] == $ida) {
                        $newids[] = $it['id'];
                        break;
                    }
                }
            }
            $newids = implode("_", $newids);
            $a = array(
                "title" => $_GPC['option_title_' . $ids][0],
                "productprice" => $_GPC['option_productprice_' . $ids][0],
                "costprice" => $_GPC['option_costprice_' . $ids][0],
                "marketprice" => $_GPC['option_marketprice_' . $ids][0],
                "stock" => $_GPC['option_stock_' . $ids][0],
                "weight" => $_GPC['option_weight_' . $ids][0],
                "goodsid" => $id,
                "specs" => $newids
            );
            if (!empty($data['hasoption'])) {
                $totalstocks += $a['stock'];
            }
            if (empty($get_option_id)) {
                pdo_insert("superdesk_boardroom_s_goods_option", $a);
                $option_id = pdo_insertid();
            } else {
                pdo_update("superdesk_boardroom_s_goods_option", $a, array('id' => $get_option_id));
                $option_id = $get_option_id;
            }
            $optionids[] = $option_id;
        }
        if (count($optionids) > 0) {
            pdo_query("delete from " . tablename('superdesk_boardroom_s_goods_option') . " where goodsid=$id and id not in ( " . implode(',', $optionids) . ")");
        } else {
            pdo_query("delete from " . tablename('superdesk_boardroom_s_goods_option') . " where goodsid=$id");
        }
        //总库存
        if (($totalstocks > 0) && ($data['totalcnf'] != 2)) {
            pdo_update("superdesk_boardroom_s_goods", array("total" => $totalstocks), array("id" => $id));
        }
        message('商品更新成功！', $this->createWebUrl('goods', array('op' => 'display', 'id' => $id)), 'success');
    }
} elseif ($operation == 'display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 15;
    $condition = ' WHERE `uniacid` = :uniacid AND `deleted` = :deleted';
    $params = array(':uniacid' => $_W['uniacid'], ':deleted' => '0');
    if (!empty($_GPC['keyword'])) {
        $condition .= ' AND `title` LIKE :title';
        $params[':title'] = '%' . trim($_GPC['keyword']) . '%';
    }
    if (!empty($_GPC['category']['childid'])) {
        $condition .= ' AND `ccate` = :ccate';
        $params[':ccate'] = intval($_GPC['category']['childid']);
    }
    if (!empty($_GPC['category']['parentid'])) {
        $condition .= ' AND `pcate` = :pcate';
        $params[':pcate'] = intval($_GPC['category']['parentid']);
    }
    if (isset($_GPC['status'])) {
        $condition .= ' AND `status` = :status';
        $params[':status'] = intval($_GPC['status']);
    }

    $sql = 'SELECT COUNT(*) FROM ' . tablename('superdesk_boardroom_s_goods') . $condition;
    $total = pdo_fetchcolumn($sql, $params);
    if (!empty($total)) {
        $sql = 'SELECT * FROM ' . tablename('superdesk_boardroom_s_goods') . $condition . ' ORDER BY `status` DESC, `displayorder` DESC,
						`id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        $list = pdo_fetchall($sql, $params);
        $pager = pagination($total, $pindex, $psize);
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $row = pdo_fetch("SELECT id, thumb FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE id = :id", array(':id' => $id));
    if (empty($row)) {
        message('抱歉，商品不存在或是已经被删除！');
    }
//			if (!empty($row['thumb'])) {
//				file_delete($row['thumb']);
//			}
//			pdo_delete('superdesk_boardroom_s_goods', array('id' => $id));
    //修改成不直接删除，而设置deleted=1
    pdo_update("superdesk_boardroom_s_goods", array("deleted" => 1), array('id' => $id));
    message('删除成功！', referer(), 'success');
} elseif ($operation == 'productdelete') {
    $id = intval($_GPC['id']);
    pdo_delete('superdesk_boardroom_s_product', array('id' => $id));
    message('删除成功！', '', 'success');
}

include $this->template('goods');