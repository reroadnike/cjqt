<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 6/19/17
 * Time: 11:28 AM
 */
class goodsModel
{

    public $table_name = "superdesk_shop_goods";

    public $table_column_all = "id,uniacid,pcate,ccate,tcate,type,status,displayorder,title,
    thumb,unit,description,content,goodssn,productsn,productprice,marketprice,costprice,originalprice,
    total,totalcnf,sales,salesreal,spec,createtime,weight,credit,maxbuy,usermaxbuy,hasoption,dispatch,thumb_url,
    isnew,ishot,isdiscount,isrecommand,issendfree,istime,iscomment,timestart,timeend,viewcount,deleted,hascommission,
    commission1_rate,commission1_pay,commission2_rate,commission2_pay,commission3_rate,commission3_pay,
    score,taobaoid,taotaoid,taobaourl,updatetime,share_title,share_icon,cash,commission_thumb,isnodiscount,
    showlevels,buylevels,showgroups,buygroups,isverify,storeids,noticeopenid,noticetype,needfollow,followtip,followurl,
    deduct,virtual,ccates,discounts,nocommission,hidecommission,pcates,cates,tcates,artid,detail_logo,
    detail_shopname,detail_btntext1,detail_btnurl1,detail_btntext2,detail_btnurl2,detail_totaltitle,
    deduct2,ednum,edmoney,edareas,diyformtype,diyformid,diymode,dispatchtype,dispatchid,dispatchprice,manydeduct,
    shorttitle,isdiscount_title,isdiscount_time,isdiscount_discounts,commission,shopid,allcates,minbuy,minprice,
    invoice,repair,seven,money,maxprice,province,city,buyshow,buycontent,virtualsend,virtualsendcontent,verifytype,
    diyfields,diysaveid,diysave,quality,groupstype,showtotal,subtitle,sharebtn,merchid,checked,thumb_first,catesinit3,
    showtotaladd,merchsale,keywords,catch_id,catch_url,catch_source,minpriceupdated,labelname,autoreceive,cannotrefund,
    bargain,buyagain,buyagain_islong,buyagain_condition,buyagain_sale,buyagain_commission,diypage,
    jd_vop_sku,jd_vop_page_num";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid']    = $_W['uniacid'];
        $params['createtime'] = strtotime('now');

        $ret = pdo_insert($this->table_name, $params);
        if (!empty($ret)) {
            $id = pdo_insertid();
        }

    }

    /**
     * @param $params
     * @param $id
     *
     * @return bool
     */
    public function update($params, $id)
    {
        global $_GPC, $_W;

        $params['updatetime'] = strtotime('now');
        $ret                  = pdo_update($this->table_name, $params, array('id' => $id));

        return $ret;
    }

    /**
     * @param       $params
     * @param array $column
     *
     * @return bool
     */
    public function updateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $params['updatetime'] = strtotime('now');

        $ret = pdo_update($this->table_name, $params, $column);

        return $ret;

    }


    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        global $_GPC, $_W;
        if (empty($id)) {
            return false;
        }
        pdo_delete($this->table_name, array('id' => $id));
    }

    public function deleteByOldShopGoodsId($old_shop_goods_id)
    {
        global $_GPC, $_W;
        if (empty($old_shop_goods_id)) {
            return false;
        }
        pdo_delete($this->table_name, array('old_shop_goods_id' => $old_shop_goods_id));


    }

    /**
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        if (empty($id)) {
            $params['uniacid']    = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
            $ret                  = pdo_update($this->table_name, $params, array('id' => $id));
        }

    }

    /**
     * @param       $params
     * @param array $column
     */
    public function saveOrUpdateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $params['uniacid']    = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $params['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $params, $column);

        }

    }


    public function getGoodsIdBySkuId($skuId)
    {

        global $_GPC, $_W;

        if (empty($skuId)) {
            return 0;
        }

        $result = pdo_get(
            $this->table_name,
            array(
                'jd_vop_sku' => $skuId
            )
        );

        if ($result) {
            return $result['id'];
        } else {
            return 0;
        }
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function getOne($id)
    {
        global $_GPC, $_W;

        if (empty($id)) {
            return null;
        }

        $result = pdo_get($this->table_name, array('id' => $id));

        return $result;

    }

    public function getById($uniacid, $goodsid)
    {
        $sql  =
            'SELECT ' .
            ' id as goodsid,title,type, weight,total,issendfree,isnodiscount,' .
            ' thumb,' .


            ' marketprice,' .
            ' costprice,' .


            ' cash,isverify,verifytype,' .
            ' goodssn,productsn,sales,istime,timestart,timeend,' .
            ' usermaxbuy,minbuy,maxbuy,unit,buylevels,buygroups,deleted,' .
            ' status,deduct,manydeduct,`virtual`,' .
            ' discounts,deduct2,ednum,edmoney,edareas,' .
            ' diyformid,diyformtype,diymode,' .
            ' dispatchtype,dispatchid,dispatchprice,' .
            ' isdiscount,isdiscount_time,isdiscount_discounts,virtualsend,' .
            ' merchid,merchsale,' .
            ' buyagain,buyagain_islong,buyagain_condition,buyagain_sale,' .
            ' bargain,' .
            ' jd_vop_sku,jd_vop_page_num, ' .
            ' cates, tcate ' .
            ' FROM ' . tablename($this->table_name) .
            ' where id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1';
        $data = pdo_fetch(
            $sql,
            array(
                ':uniacid' => $uniacid,
                ':id'      => $goodsid
            )
        );
        return $data;
    }

    public function getByIdForShopCart($goods_id, $uniacid)
    {
        $sql =
            ' SELECT ' .
            ' id as goodsid,type,title,weight,issendfree,isnodiscount, ' .
            ' thumb,marketprice,storeids,isverify,deduct,' .
            ' manydeduct,`virtual`,maxbuy,usermaxbuy,discounts,total as stock,deduct2,showlevels,' .
            ' ednum,edmoney,edareas,' .
            ' diyformtype,diyformid,diymode,dispatchtype,dispatchid,dispatchprice,cates,minbuy, ' .
            ' isdiscount,isdiscount_time,isdiscount_discounts, ' .
            ' virtualsend,invoice,needfollow,followtip,followurl,merchid,checked,merchsale, ' .
            ' buyagain,buyagain_islong,buyagain_condition, buyagain_sale' .
            ' jd_vop_sku,jd_vop_page_num,tcate ' .
            ' FROM ' . tablename('superdesk_shop_goods') .
            ' where id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1';


        $data = pdo_fetch($sql, array(
            ':uniacid' => $uniacid,
            ':id'      => $goods_id
        ));

        return $data;
    }

    public function getBySKuIdForShopCart($uniacid, $jd_vop_sku)
    {
        $sql =
            ' SELECT ' .
            ' id as goodsid,type,title,weight,issendfree,isnodiscount, ' .
            ' thumb,marketprice,storeids,isverify,deduct,' .
            ' manydeduct,`virtual`,maxbuy,usermaxbuy,discounts,total as stock,deduct2,showlevels,' .
            ' ednum,edmoney,edareas,' .
            ' diyformtype,diyformid,diymode,dispatchtype,dispatchid,dispatchprice,cates,minbuy, ' .
            ' isdiscount,isdiscount_time,isdiscount_discounts, ' .
            ' virtualsend,invoice,needfollow,followtip,followurl,merchid,checked,merchsale, ' .
            ' buyagain,buyagain_islong,buyagain_condition, buyagain_sale' .
            ' jd_vop_sku,jd_vop_page_num ' .
            ' FROM ' . tablename('superdesk_shop_goods') .
            ' where jd_vop_sku=:jd_vop_sku ' .
            '       and uniacid=:uniacid ' .
            ' limit 1';


        $data = pdo_fetchall($sql, array(
            ':uniacid'    => $uniacid,
            ':jd_vop_sku' => $jd_vop_sku
        ));

        return $data;
    }

    /**
     * @param array $column
     *
     * @return bool
     */
    public function getOneByColumn($column = array())
    {
        global $_GPC, $_W;

        $result = pdo_get($this->table_name, $column);

        return $result;

    }

    /**
     * @param [] {"goodsid":"865","total":"1","optionid":"0","marketprice":"11.00","merchid":"0","cates":"39","discounttype":2,"isdiscountprice":0,"discountprice":0,"isdiscountunitprice":0,"discountunitprice":0}
     *
     * @return array
     */
    public function transformGoodIds2SkuIds(&$goods_list = array())
    {

        $skuNums = array();

        $skuArr = array();

        foreach ($goods_list as $index => &$goods) {

            $__goods = $this->getOne($goods['goodsid']);

            $goods['skuId']     = $__goods['jd_vop_sku'];
            $goods['costprice'] = $__goods['costprice'];

            $goods['stock_msg'] = "";
            $goods['price_msg'] = "";
            $goods['state_msg'] = "";

            if ($__goods['jd_vop_sku'] != 0) {

                $skuNums[] = array(
                    "skuId" => $goods['skuId'],
                    "num"   => $goods['total'],
                );

                $skuArr[] = $goods['skuId'];

            }
        }

        $data            = array();
        $data['skuNums'] = $skuNums;
        $data['skuArr']  = $skuArr;
        return $data;
    }

    /**
     * @param array $where
     * @param int   $page
     * @param int   $page_size
     *
     * @return array
     */
    public function queryAll($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql .= " WHERE uniacid = :uniacid";
        $params    = array(
            ':uniacid' => $_W['uniacid'],
        );

        $total = pdo_fetchcolumn(
            " SELECT COUNT(*) " .
            " FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall(
            " SELECT * " .
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY id ASC " .
            " LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function queryForJdVopApiPriceUpdate($where = array(), $page = 1, $page_size = 100)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql .= " WHERE uniacid = :uniacid";
        $params    = array(
            ':uniacid' => $_W['uniacid'],
        );

        $where_sql .= " AND jd_vop_sku > 0 ";
        $where_sql .= " AND deleted = 0 ";
        $where_sql .= " AND status = 1 "; // 0 下架 1 上架 2 增品

        $where_sql                        .= " AND synctime_jd_vop_price < :synctime_jd_vop_price ";
        $params[':synctime_jd_vop_price'] = time() - (86400);// 24小时 = 86400秒 7天后过期 有效期

        $total = pdo_fetchcolumn(
            " SELECT COUNT(jd_vop_sku) " .
            " FROM " . tablename($this->table_name) .
            $where_sql,
            $params
        );
        $list  = pdo_fetchall(
            " SELECT jd_vop_sku as skuId " .
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY synctime_jd_vop_price ASC " .
            " LIMIT " . ($page - 1) * $page_size . ',' . $page_size,
            $params
        );

//        echo "SELECT COUNT(jd_vop_sku) FROM " . tablename($this->table_name) . $where_sql;
//        echo '<br/>';
//        echo json_encode($params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function updateByJdVopApiPriceUpdate($params, $sku)
    {

        global $_GPC, $_W;

        unset($params['skuId']);

        $column    = array(
            "jd_vop_sku" => $sku
        );
        $_is_exist = $this->getOneByColumn($column);

        if ($_is_exist) {

            /*
             * 通过jdvop更新了sku 的 price 后台看是没问题，但在微信页面上还是显示 0
             * 此出处在/data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/web/goods/post.php
             */
            $params['minprice']              = $params['marketprice'];
            $params['maxprice']              = $params['marketprice'];
            $params['updatetime']            = time();
            $params['synctime_jd_vop_price'] = time();

            $ret = pdo_update($this->table_name, $params, $column);

//            echo 'update price' . json_encode($params,JSON_UNESCAPED_UNICODE);
//            echo PHP_EOL;
        }

    }

    public function saveOrUpdateByJdVopSkuImage($result)
    {

        foreach ($result as $sku => $sku_image) {
//            {
//                "id": 32157969,
//                "skuId": 3794327,
//                "path": "jfs\/t14053\/5\/5953969\/209811\/e801ffa9\/5a017dddN8e1abd34.jpg",
//                "created": "2017-12-08 19:55:23",
//                "modified": "2017-12-12 17:29:08",
//                "yn": 1,
//                "isPrimary": 1,
//                "orderSort": null,
//                "position": null,
//                "type": 0,
//                "features": null
//            }

            $thumb_url = array();
            foreach ($sku_image as $_img_item) {
                if ($_img_item['isPrimary'] == 0) {


                    $thumb_url[] = "https://img13.360buyimg.com/n12/" . $_img_item['path'];
                }
            }

            $params              = array();
            $params['thumb_url'] = iserializer($thumb_url);
            $ret                 = pdo_update($this->table_name, $params, array('jd_vop_sku' => $sku));


        }

    }

    public function saveOrUpdateByJdVopApiProductGetDetail($params, $sku, $specify_id = 0)
    {
        global $_GPC, $_W;

        if (empty($sku)) {
            return;
        }


        $merchid    = SUPERDESK_SHOPV2_JD_VOP_MERCHID;
        $merch_name = '京东企业购';
//        $overwrite = 99;

//        SUPERDESK_SHOPV2_JD_VOP_MERCHID

//        $debug_echo               = array();
//        $debug_echo['sku']       = $sku;
//        //$debug_echo['page_num']  = $page_num;
//        $debug_echo['overwrite'] = $overwrite;
//        $debug_echo['merchid'] = SUPERDESK_SHOPV2_JD_VOP_MERCHID;
//        show_json(1,$debug_echo);

        /** 未比对
         * 'category' => $params['category'],
         * 'productArea' => $params['productArea'],
         * 'wareQD' => $params['wareQD'],
         * 'param' => $params['param'],
         * 'brandName' => $params['brandName'],
         * 'introduction' => $params['introduction'],
         */


//        echo json_encode($params);
//        echo "<br> ↓ <br>";

        $column    = array(
            "jd_vop_sku" => $sku
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();

            $this->toggleSaveOrUpdateByJdVopApiProductGetDetail($insert_data, $params['category']);

            if ($specify_id != 0) {
                $insert_data['id'] = $specify_id;
            }

            $insert_data['merchid'] = $merchid;// v2 商户ID | YES | int(11) | 0
            $insert_data['shopid']  = '0';// v2 商户ID | YES | int(11) | 0

//            $insert_data['pcate'] = '0';// 一级分类ID | YES | int(11) | 0
//            $insert_data['ccate'] = '0';// 二级分类ID | YES | int(11) | 0
//            $insert_data['tcate'] = '0';// 三级分类ID | YES | int(11) | 0
//
//            $insert_data['pcates'] = '';// 一级多重分类 | YES | text | 以,号分
//            $insert_data['ccates'] = '';// 二级多重分类 | YES | text | 以,号分
//            $insert_data['tcates'] = '';// 三级多重分类 | YES | text | 以,号分
//
//            $insert_data['cates'] = '';// 多重分类数据集 | YES | text | 以,号分

            $insert_data['jd_vop_page_num'] = $params['page_num'];//

            $insert_data['type']         = '1';// 类型 1 实体物品 2 虚拟物品 3 虚拟物品(卡密) 4 批发 10 话费流量充值 20 充值卡 | YES | tinyint(1) | 1
            $insert_data['status']       = $params['state'];// 状态 0 下架 1 上架 2 赠品上架 | YES | tinyint(1) | 1
            $insert_data['displayorder'] = '0';// 显示顺序 | YES | int(11) | 0

            $insert_data['title']      = $params['name'];// 商品名称 | YES | varchar(100) |
            $insert_data['subtitle']   = '';// v2 子标题 | YES | varchar(255) |
            $insert_data['shorttitle'] = '';// 短名称 打印需要 | YES | varchar(255) |


            /**
             * imagePath 为商品的主图片地址。需要在前面添加 http://img13.360buyimg.com/n0/
             * 其中n0(最大图)、n1(350*350px)、n2(160*160px)、n3(130*130px)、n4(100*100px)
             */

            $insert_data['thumb']       = "https://img13.360buyimg.com/n12/" . $params['imagePath'];// 商品图 | YES | varchar(255) |
            $insert_data['thumb_first'] = '0';// v2 详情页面显示首图 0 不显示 1 显示 注：首图为列表页使用，尺寸较小 | YES | tinyint(3) | 0
            $insert_data['thumb_url']   = 'a:0:{}';// 缩略图地址 | YES | text |


//            dispatchtype
//                = 0 选择运费模板
//                = 1 统一邮费
//            dispatchprice 手功设定
//            为了兼容京东，在导入时设定统一邮费与设定邮费为0
            $insert_data['dispatchtype']  = '1';// 配送类型 0 运费模板 1 统一邮费 | YES | tinyint(1) | 0
            $insert_data['dispatchid']    = '0';// 配送ID | YES | int(11) | 0
            $insert_data['dispatchprice'] = '0.00';// 统一邮费 | YES | decimal(10,2) | 0.00

            $insert_data['unit']        = $params['saleUnit'];// 商品单位 | YES | varchar(5) |
            $insert_data['description'] = '';// 分享描述 | YES | varchar(1000) |
            $insert_data['content']     = $params['appintroduce'];// 商品详情 | YES | text |
            $insert_data['goodssn']     = $params['sku'];// 商品编号 | YES | varchar(50) |
            $insert_data['productsn']   = $params['upc'];// 商品条码 | YES | varchar(50) |


            $insert_data['productprice'] = '0.00';// 商品原价 | YES | decimal(10,2) | 0.00
            $insert_data['marketprice']  = '0.00';// 商品现价 | YES | decimal(10,2) | 0.00
            $insert_data['costprice']    = '0.00';// 商品成本 | YES | decimal(10,2) | 0.00


            $insert_data['originalprice'] = '0.00';// 原价 好象已经废弃 | YES | decimal(10,2) | 0.00

            $insert_data['maxbuy']     = '0';// 单次最多购买量 | YES | int(11) | 0
            $insert_data['usermaxbuy'] = '0';// 用户最多购买量 | YES | int(11) | 0


            $insert_data['minbuy'] = '0';// v2 用户单次必须购买数量 | YES | int(11) | 0

            $insert_data['minprice'] = '0.00';// v2 多规格中最小价格，无规格时显示销售价 | YES | decimal(10,2) | 0.00
            $insert_data['maxprice'] = '0.00';// v2 多规格中最大价格，无规格时显示销售价 | YES | decimal(10,2) | 0.00


            $insert_data['total']     = '9999';// 商品库存 | YES | int(10) | 0
            $insert_data['totalcnf']  = '2';// 减库存方式 0 拍下减库存 1 付款减库存 2 永不减库存 | YES | int(11) | 0
            $insert_data['sales']     = '0';// 已出售数 | YES | int(11) | 0
            $insert_data['salesreal'] = '0';// 实际售出数 | YES | int(11) | 0
            $insert_data['spec']      = '';// 商品规格设置 | YES | varchar(5000) |
            $insert_data['weight']    = floatval($params['weight']) * 1000;// 重量(单位g) | YES | decimal(10,2) | 0.00
            $insert_data['credit']    = '';// 购买赠送积分，如果带%号，则为按成交价比例计算 | YES | varchar(255) |

            $insert_data['hasoption'] = '0';// 启用商品规则 0 不启用 1 启用 | YES | int(11) | 0
            $insert_data['dispatch']  = '0';// 配送 | YES | int(11) | 0

            $insert_data['isnew']         = '0';// 新上 | YES | tinyint(1) | 0
            $insert_data['ishot']         = '0';// 热卖 | YES | tinyint(1) | 0
            $insert_data['isdiscount']    = '0';// 促销 | YES | tinyint(1) | 0
            $insert_data['isrecommand']   = '0';// 推荐 | YES | tinyint(1) | 0
            $insert_data['issendfree']    = '0';// 包邮 | YES | tinyint(1) | 0 商品属性 issendfree = 1 为包邮
            $insert_data['istime']        = '0';// 限时卖 | YES | tinyint(1) | 0
            $insert_data['iscomment']     = '0';// 允许评价 | YES | tinyint(1) | 0
            $insert_data['timestart']     = '0';// 限卖开始时间 | YES | int(11) | 0
            $insert_data['timeend']       = '0';// 限卖结束时间 | YES | int(11) | 0
            $insert_data['viewcount']     = '0';// 查看次数 | YES | int(11) | 0
            $insert_data['deleted']       = '0';// 是否删除 | YES | tinyint(3) | 0
            $insert_data['hascommission'] = '0';// 是否有分销 | YES | tinyint(3) | 0

            $insert_data['commission1_rate'] = '0.00';// 一级分销比率 | YES | decimal(10,2) | 0.00
            $insert_data['commission1_pay']  = '0.00';// 一级分销固定佣金 | YES | decimal(10,2) | 0.00
            $insert_data['commission2_rate'] = '0.00';// 二级分销比率 | YES | decimal(10,2) | 0.00
            $insert_data['commission2_pay']  = '0.00';// 二级分销固定佣金 | YES | decimal(10,2) | 0.00
            $insert_data['commission3_rate'] = '0.00';// 三级分销比率 | YES | decimal(10,2) | 0.00
            $insert_data['commission3_pay']  = '0.00';// 三级分销固定佣金 | YES | decimal(10,2) | 0.00

            $insert_data['score'] = '0.00';// 得分 好象已经废弃 | YES | decimal(10,2) | 0.00

            $insert_data['taobaoid']  = '';// 淘宝ID 淘宝助手 | YES | varchar(255) |
            $insert_data['taotaoid']  = '';// 淘宝ID 淘宝助手 | YES | varchar(255) |
            $insert_data['taobaourl'] = '';// 淘宝网址 淘宝助手 | YES | varchar(255) |

            $insert_data['share_title']      = '';// 分享标题 | YES | varchar(255) |
            $insert_data['share_icon']       = '';// 分享图标 | YES | varchar(255) |
            $insert_data['cash']             = '2';// 企业月结 1 不支持 2 支持 | YES | tinyint(3) | 0
            $insert_data['commission_thumb'] = '';// 海报图片 | YES | varchar(255) |
            $insert_data['isnodiscount']     = '1';// 不参与会员折扣 | YES | tinyint(3) | 0
            $insert_data['showlevels']       = '';// 浏览权限 | YES | text |
            $insert_data['buylevels']        = '';// 购买权限 | YES | text |
            $insert_data['showgroups']       = '';// 会员组浏览权限 | YES | text |
            $insert_data['buygroups']        = '';// 会员组购买权限 | YES | text |
            $insert_data['isverify']         = '0';// 支持线下核销 Null 0 1 不支持 2 支持 | YES | tinyint(3) | 0
            $insert_data['storeids']         = '';// 支持门店ID | YES | text |
            $insert_data['noticeopenid']     = '';// 商家通知 | YES | varchar(255) |
            $insert_data['noticetype']       = '';// 提醒类型 | YES | text |
            $insert_data['needfollow']       = '0';// 需要关注 | YES | tinyint(3) | 0

            $insert_data['followtip'] = '';// 关注事项 | YES | varchar(255) |
            $insert_data['followurl'] = '';// 关注地址 | YES | varchar(255) |

            $insert_data['virtual'] = '0';// 虚拟商品模板ID 0 多规格虚拟商品 | YES | int(11) | 0

            $insert_data['discounts']      = '';// 折扣 | YES | text |
            $insert_data['nocommission']   = '0';// 不执行分销 | YES | tinyint(3) | 0
            $insert_data['hidecommission'] = '0';// 隐藏分销按钮 | YES | tinyint(3) | 0


            $insert_data['artid'] = '0';// 营销文章ID | YES | int(11) | 0

            $insert_data['detail_logo']       = '';// 店铺LOGO | YES | varchar(255) |
            $insert_data['detail_shopname']   = '';// 店铺名称 | YES | varchar(255) |
            $insert_data['detail_btntext1']   = '';// 按钮1名称 | YES | varchar(255) |
            $insert_data['detail_btnurl1']    = '';// 按钮1链接 默认"查看所有商品"及"默认的全部商品连接" | YES | varchar(255) |
            $insert_data['detail_btntext2']   = '';// 按钮2名称 | YES | varchar(255) |
            $insert_data['detail_btnurl2']    = '';// 按钮2链接 默认"进店逛逛"及"默认的小店或商城连接" | YES | varchar(255) |
            $insert_data['detail_totaltitle'] = '';// 全部宝贝x个 | YES | varchar(255) |

            $insert_data['deduct']     = '0.00';//  | YES | decimal(10,2) | 0.00
            $insert_data['deduct2']    = '0.00';// 余额抵扣 0 支持全额抵扣 -1 不支持余额抵扣 >0 最多抵扣 元 | YES | decimal(10,2) | 0.00
            $insert_data['manydeduct'] = '0';// 多件累计抵扣积分 | YES | tinyint(1) | 0

            $insert_data['ednum']   = '0';// 单品满件包邮 0 : 不支持满件包邮 | YES | int(11) | 0
            $insert_data['edmoney'] = '0.00';// 单品满额包邮 0 : 不支持满额包邮 | YES | decimal(10,2) | 0.00
            $insert_data['edareas'] = '';// 不参加满包邮的地区 ，0 : 不支持满件包邮 | YES | text |

            $insert_data['diyformtype'] = '0';// 自定义表单类型 0 关闭 1 附加形式 2 替换模式 | YES | tinyint(1) | 0
            $insert_data['diyformid']   = '0';// 自定义表单ID | YES | int(11) | 0
            $insert_data['diymode']     = '0';// 自定义表单模式 0 系统默认 1 点击立即购买时填写 2 确认订单时填写 | YES | tinyint(1) | 0


            $insert_data['isdiscount_title']     = '';// v2 促销标题 | YES | varchar(255) |
            $insert_data['isdiscount_time']      = '0';// v2 促销结束时间 | YES | int(11) | 0
            $insert_data['isdiscount_discounts'] = '';// v2 促销价格 数字为价格 百分数 为折扣 | YES | text |
            $insert_data['commission']           = '';// v2 分销 | YES | text |

            $insert_data['allcates'] = '';// v2 好象无用 | YES | text |


            $insert_data['invoice'] = '1';// v2 提供发票 | YES | tinyint(3) | 0

            $insert_data['repair'] = '0';// v2 保修 | YES | tinyint(3) | 0
            $insert_data['seven']  = '0';// v2 7天无理由退换 | YES | tinyint(3) | 0
            $insert_data['money']  = '';// v2 余额返现 | YES | varchar(255) |

            $insert_data['province']           = '';// v2 商品所在省 如为空则显示商城所在 | YES | varchar(255) |
            $insert_data['city']               = '';// v2 商品所在城市 如为空则显示商城所在 | YES | varchar(255) |
            $insert_data['buyshow']            = '1';// v2 购买后可见 0 关闭 1 开启 | YES | tinyint(1) | 0
            $insert_data['buycontent']         = $params['shouhou'];// v2 购买可见开启后的内容 | YES | text |
            $insert_data['virtualsend']        = '0';// v2 自动发货 0 否 1 是 ，注：自动发货后，订单自动完成 | YES | tinyint(1) | 0
            $insert_data['virtualsendcontent'] = '';// v2 自动发货内容 | YES | text |
            $insert_data['verifytype']         = '0';// v2 核销类型 0 按订单核销 1 按次核销 2 按消费码核销 | YES | tinyint(1) | 0
            $insert_data['diyfields']          = '';// v2 | YES | text |
            $insert_data['diysaveid']          = '0';// v2 | YES | int(11) | 0
            $insert_data['diysave']            = '0';// v2 | YES | tinyint(1) | 0
            $insert_data['quality']            = '0';// v2 正品保证 | YES | tinyint(3) | 0
            $insert_data['groupstype']         = '0';// v2 是否支持拼团 0 否 1 支持 | NO | tinyint(1) unsigned | 0
            $insert_data['showtotal']          = '0';// v2 显示库存 0 不显示 1 显示 | NO | tinyint(1) unsigned | 0
            $insert_data['sharebtn']           = '0';// v2 分享按钮链接方式 0 弹出关注提示层 1 跳转至商品海报 | NO | tinyint(1) | 0


            $insert_data['checked'] = '0';// v2 审核状态 | YES | tinyint(3) | 0

            $insert_data['catesinit3']   = '';// v2 是否初始化分类信息 标识字段无用 | YES | text |
            $insert_data['showtotaladd'] = '0';// v2 是否调整过显示库存 标识字段无用 | YES | tinyint(1) | 0
            $insert_data['merchsale']    = '0';// v2 手机端使用的价格 0 当前设置促销价格 1 商户设置促销价格 | YES | tinyint(1) | 0
            $insert_data['keywords']     = '';// v2 关键词 | YES | varchar(255) |

            $insert_data['catch_id']     = '';// v2 抓取产品信息ID | YES | varchar(255) |
            $insert_data['catch_url']    = '';// v2 抓取产品地址 | YES | varchar(255) |
            $insert_data['catch_source'] = '';// v2 抓取产品来源 | YES | varchar(255) |

            $insert_data['minpriceupdated'] = '0';// v2 是否更新过价格 标识字段无用 | YES | tinyint(1) | 0
            $insert_data['labelname']       = '';// v2 商品标签 | YES | text |
            $insert_data['autoreceive']     = '0';// v2 自动收货 0 系统设置 -1 不自动收货 >0 天数 | YES | int(11) | 0
            $insert_data['cannotrefund']    = '0';// v2 不允许退货 | YES | tinyint(3) | 0

            $insert_data['bargain'] = '0';// v2 砍价 | YES | int(11) | 0

            $insert_data['buyagain']            = '0.00';// v2 重复购买折扣 | YES | decimal(10,2) | 0.00
            $insert_data['buyagain_islong']     = '0';// v2 购买一次后,以后都使用这个折扣 0 否 1 是 | YES | tinyint(1) | 0
            $insert_data['buyagain_condition']  = '0';// v2 使用条件 0 付款后 1 完成后 | YES | tinyint(1) | 0
            $insert_data['buyagain_sale']       = '0';// v2 可否使用优惠 （重复购买时,是否与其他优惠共享!其他优惠享受后,再使用这个折扣） | YES | tinyint(1) | 0
            $insert_data['buyagain_commission'] = '';// v2 复购分销 | YES | text |

            $insert_data['diypage'] = '';// v2 自定义页面ID | YES | int(11) |


            $insert_data['jd_vop_sku'] = $params['sku'];//  | NO | int(11) | 0


            $insert_data['uniacid']    = $_W['uniacid'];
            $insert_data['createtime'] = strtotime('now');
            $insert_data['updatetime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $update_data = array();

            $this->toggleSaveOrUpdateByJdVopApiProductGetDetail($update_data, $params['category']);

            if ($specify_id != 0) {
                $update_data['id'] = $specify_id;
            }

            $update_data['jd_vop_page_num'] = $params['page_num'];//

            $update_data['merchid'] = $merchid;// v2 商户ID | YES | int(11) | 0
            $update_data['shopid']  = '0'; // v2 商户ID | YES | int(11) | 0

            $update_data['status'] = $params['state'];// 状态 0 下架 1 上架 2 赠品上架 | YES | tinyint(1) | 1
            $update_data['title']  = $params['name'];// 商品名称 | YES | varchar(100) |

            /**
             * imagePath 为商品的主图片地址。需要在前面添加 http://img13.360buyimg.com/n0/
             * 其中n0(最大图)、n1(350*350px)、n2(160*160px)、n3(130*130px)、n4(100*100px)
             */

            $update_data['thumb']       = "https://img13.360buyimg.com/n12/" . $params['imagePath'];// 商品图 | YES | varchar(255) |
            $update_data['thumb_first'] = '0';// v2 详情页面显示首图 0 不显示 1 显示 注：首图为列表页使用，尺寸较小 | YES | tinyint(3) | 0
            $update_data['thumb_url']   = 'a:0:{}';// 缩略图地址 | YES | text |

            // dispatchtype
//                = 0 选择运费模板
//                = 1 统一邮费
//            dispatchprice 手功设定
//            为了兼容京东，在导入时设定统一邮费与设定邮费为0
            $update_data['dispatchtype']  = '1';// 配送类型 0 运费模板 1 统一邮费 | YES | tinyint(1) | 0
            $update_data['dispatchid']    = '0';// 配送ID | YES | int(11) | 0
            $update_data['dispatchprice'] = '0.00';// 统一邮费 | YES | decimal(10,2) | 0.00

            $update_data['unit']       = $params['saleUnit'];// 商品单位 | YES | varchar(5) |
            $update_data['content']    = $params['appintroduce'];// 商品详情 | YES | text |
            $update_data['goodssn']    = $params['sku'];// 商品编号 | YES | varchar(50) |
            $update_data['productsn']  = $params['upc'];// 商品条码 | YES | varchar(50) |
            $update_data['weight']     = floatval($params['weight']) * 1000;// 重量(单位g) | YES | decimal(10,2) | 0.00
            $update_data['buycontent'] = $params['shouhou'];// v2 购买可见开启后的内容 | YES | text |

            $update_data['totalcnf']     = '2';// 减库存方式 0 拍下减库存 1 付款减库存 2 永不减库存 | YES | int(11) | 0
            $update_data['isnodiscount'] = '1';// 不参与会员折扣 | YES | tinyint(3) | 0      no 0 yes
            $update_data['updatetime']   = strtotime('now');

            $ret = pdo_update($this->table_name, $update_data, $column);

        }

    }

    public function saveOrUpdateByImportMerchGoodsFromOldShop($params)
    {
        global $_GPC, $_W;

//        $params
//            {
//                "goods_id": "51",
//                "name": "DELL燃7000 R1605S14.0英寸微边框笔记本电脑",
//                "referprice": "299.00",
//                "status": "400",
//                "pic": "http:\/\/www.avic-s.com\/memberShop-admin\/images\/pic\/1488868351749.jpg",
//                "introductions": "i5-7200U 8GB 256GB SSD HD620 Win10",
//                "model": "a",
//                "type": "0",
//                "classify_id": "45",
//                "stock": "10",
//                "unit_id": "13",
//                "user_id": "143",
//                "oldprice": "399.00"
//            }


        $column    = array(
            "old_shop_goods_id" => $params['goods_id']
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();

            $this->toggleSaveOrUpdateByJdVopApiProductGetDetail($insert_data, $params['category']);

            $insert_data['merchid'] = $params['merchid'];// v2 商户ID | YES | int(11) | 0
            $insert_data['shopid']  = '0';// v2 商户ID | YES | int(11) | 0

            $insert_data['jd_vop_page_num'] = '';//

            // TODO 可能有问题
            $insert_data['type']         = '1';// 类型 1 实体物品 2 虚拟物品 3 虚拟物品(卡密) 4 批发 10 话费流量充值 20 充值卡 | YES | tinyint(1) | 1
            $insert_data['status']       = $params['status'];// 状态 0 下架 1 上架 2 赠品上架 | YES | tinyint(1) | 1
            $insert_data['displayorder'] = '0';// 显示顺序 | YES | int(11) | 0

            $insert_data['title']      = $params['name'];// 商品名称 | YES | varchar(100) |
            $insert_data['subtitle']   = $params['introductions'];// v2 子标题 | YES | varchar(255) |
            $insert_data['shorttitle'] = '';// 短名称 打印需要 | YES | varchar(255) |

            $insert_data['thumb']       = $params['pic'];// 商品图 | YES | varchar(255) |
            $insert_data['thumb_first'] = '0';// v2 详情页面显示首图 0 不显示 1 显示 注：首图为列表页使用，尺寸较小 | YES | tinyint(3) | 0
            $insert_data['thumb_url']   = 'a:0:{}';// 缩略图地址 | YES | text |


//            dispatchtype
//                = 0 选择运费模板
//                = 1 统一邮费
//            dispatchprice 手功设定
//            为了兼容京东，在导入时设定统一邮费与设定邮费为0
            $insert_data['dispatchtype']  = '1';// 配送类型 0 运费模板 1 统一邮费 | YES | tinyint(1) | 0
            $insert_data['dispatchid']    = '0';// 配送ID | YES | int(11) | 0
            $insert_data['dispatchprice'] = '0.00';// 统一邮费 | YES | decimal(10,2) | 0.00

            $insert_data['unit']        = $params['unit_id'];// 商品单位 | YES | varchar(5) |
            $insert_data['description'] = '';// 分享描述 | YES | varchar(1000) |
            $insert_data['content']     = $params['content'];// 商品详情 | YES | text |
            $insert_data['goodssn']     = '';// 商品编号 | YES | varchar(50) |
            $insert_data['productsn']   = '';// 商品条码 | YES | varchar(50) |


            $insert_data['productprice'] = $params['oldprice'];// 商品原价 | YES | decimal(10,2) | 0.00
            $insert_data['marketprice']  = $params['referprice'];// 商品现价 | YES | decimal(10,2) | 0.00
            $insert_data['costprice']    = '0.00';// 商品成本 | YES | decimal(10,2) | 0.00


            $insert_data['originalprice'] = '0.00';// 原价 好象已经废弃 | YES | decimal(10,2) | 0.00

            $insert_data['maxbuy']     = '0';// 单次最多购买量 | YES | int(11) | 0
            $insert_data['usermaxbuy'] = '0';// 用户最多购买量 | YES | int(11) | 0


            $insert_data['minbuy'] = '0';// v2 用户单次必须购买数量 | YES | int(11) | 0

            $insert_data['minprice'] = $params['referprice'];// v2 多规格中最小价格，无规格时显示销售价 | YES | decimal(10,2) | 0.00
            $insert_data['maxprice'] = $params['referprice'];// v2 多规格中最大价格，无规格时显示销售价 | YES | decimal(10,2) | 0.00


            $insert_data['total']     = $params['stock'];// 商品库存 | YES | int(10) | 0
            $insert_data['totalcnf']  = '1';// 减库存方式 0 拍下减库存 1 付款减库存 2 永不减库存 | YES | int(11) | 0
            $insert_data['sales']     = '0';// 已出售数 | YES | int(11) | 0
            $insert_data['salesreal'] = '0';// 实际售出数 | YES | int(11) | 0
            $insert_data['spec']      = '';// 商品规格设置 | YES | varchar(5000) |
            $insert_data['weight']    = floatval($params['weight']) * 1000;// 重量(单位g) | YES | decimal(10,2) | 0.00
            $insert_data['credit']    = '';// 购买赠送积分，如果带%号，则为按成交价比例计算 | YES | varchar(255) |

            $insert_data['hasoption'] = '0';// 启用商品规则 0 不启用 1 启用 | YES | int(11) | 0
            $insert_data['dispatch']  = '0';// 配送 | YES | int(11) | 0

            $insert_data['isnew']         = '0';// 新上 | YES | tinyint(1) | 0
            $insert_data['ishot']         = '0';// 热卖 | YES | tinyint(1) | 0
            $insert_data['isdiscount']    = '0';// 促销 | YES | tinyint(1) | 0
            $insert_data['isrecommand']   = '0';// 推荐 | YES | tinyint(1) | 0
            $insert_data['issendfree']    = '0';// 包邮 | YES | tinyint(1) | 0 商品属性 issendfree = 1 为包邮
            $insert_data['istime']        = '0';// 限时卖 | YES | tinyint(1) | 0
            $insert_data['iscomment']     = '0';// 允许评价 | YES | tinyint(1) | 0
            $insert_data['timestart']     = '0';// 限卖开始时间 | YES | int(11) | 0
            $insert_data['timeend']       = '0';// 限卖结束时间 | YES | int(11) | 0
            $insert_data['viewcount']     = '0';// 查看次数 | YES | int(11) | 0
            $insert_data['deleted']       = '0';// 是否删除 | YES | tinyint(3) | 0
            $insert_data['hascommission'] = '0';// 是否有分销 | YES | tinyint(3) | 0

            $insert_data['commission1_rate'] = '0.00';// 一级分销比率 | YES | decimal(10,2) | 0.00
            $insert_data['commission1_pay']  = '0.00';// 一级分销固定佣金 | YES | decimal(10,2) | 0.00
            $insert_data['commission2_rate'] = '0.00';// 二级分销比率 | YES | decimal(10,2) | 0.00
            $insert_data['commission2_pay']  = '0.00';// 二级分销固定佣金 | YES | decimal(10,2) | 0.00
            $insert_data['commission3_rate'] = '0.00';// 三级分销比率 | YES | decimal(10,2) | 0.00
            $insert_data['commission3_pay']  = '0.00';// 三级分销固定佣金 | YES | decimal(10,2) | 0.00

            $insert_data['score'] = '0.00';// 得分 好象已经废弃 | YES | decimal(10,2) | 0.00

            $insert_data['taobaoid']  = '';// 淘宝ID 淘宝助手 | YES | varchar(255) |
            $insert_data['taotaoid']  = '';// 淘宝ID 淘宝助手 | YES | varchar(255) |
            $insert_data['taobaourl'] = '';// 淘宝网址 淘宝助手 | YES | varchar(255) |

            $insert_data['share_title']      = '';// 分享标题 | YES | varchar(255) |
            $insert_data['share_icon']       = '';// 分享图标 | YES | varchar(255) |
            $insert_data['cash']             = '2';// 企业月结 1 不支持 2 支持 | YES | tinyint(3) | 0
            $insert_data['commission_thumb'] = '';// 海报图片 | YES | varchar(255) |
            $insert_data['isnodiscount']     = '1';// 不参与会员折扣 | YES | tinyint(3) | 0
            $insert_data['showlevels']       = '';// 浏览权限 | YES | text |
            $insert_data['buylevels']        = '';// 购买权限 | YES | text |
            $insert_data['showgroups']       = '';// 会员组浏览权限 | YES | text |
            $insert_data['buygroups']        = '';// 会员组购买权限 | YES | text |
            $insert_data['isverify']         = '0';// 支持线下核销 Null 0 1 不支持 2 支持 | YES | tinyint(3) | 0
            $insert_data['storeids']         = '';// 支持门店ID | YES | text |
            $insert_data['noticeopenid']     = '';// 商家通知 | YES | varchar(255) |
            $insert_data['noticetype']       = '';// 提醒类型 | YES | text |
            $insert_data['needfollow']       = '0';// 需要关注 | YES | tinyint(3) | 0

            $insert_data['followtip'] = '';// 关注事项 | YES | varchar(255) |
            $insert_data['followurl'] = '';// 关注地址 | YES | varchar(255) |

            $insert_data['virtual'] = '0';// 虚拟商品模板ID 0 多规格虚拟商品 | YES | int(11) | 0

            $insert_data['discounts']      = '';// 折扣 | YES | text |
            $insert_data['nocommission']   = '0';// 不执行分销 | YES | tinyint(3) | 0
            $insert_data['hidecommission'] = '0';// 隐藏分销按钮 | YES | tinyint(3) | 0


            $insert_data['artid'] = '0';// 营销文章ID | YES | int(11) | 0

            $insert_data['detail_logo']       = '';// 店铺LOGO | YES | varchar(255) |
            $insert_data['detail_shopname']   = '';// 店铺名称 | YES | varchar(255) |
            $insert_data['detail_btntext1']   = '';// 按钮1名称 | YES | varchar(255) |
            $insert_data['detail_btnurl1']    = '';// 按钮1链接 默认"查看所有商品"及"默认的全部商品连接" | YES | varchar(255) |
            $insert_data['detail_btntext2']   = '';// 按钮2名称 | YES | varchar(255) |
            $insert_data['detail_btnurl2']    = '';// 按钮2链接 默认"进店逛逛"及"默认的小店或商城连接" | YES | varchar(255) |
            $insert_data['detail_totaltitle'] = '';// 全部宝贝x个 | YES | varchar(255) |

            $insert_data['deduct']     = '0.00';//  | YES | decimal(10,2) | 0.00
            $insert_data['deduct2']    = '0.00';// 余额抵扣 0 支持全额抵扣 -1 不支持余额抵扣 >0 最多抵扣 元 | YES | decimal(10,2) | 0.00
            $insert_data['manydeduct'] = '0';// 多件累计抵扣积分 | YES | tinyint(1) | 0

            $insert_data['ednum']   = '0';// 单品满件包邮 0 : 不支持满件包邮 | YES | int(11) | 0
            $insert_data['edmoney'] = '0.00';// 单品满额包邮 0 : 不支持满额包邮 | YES | decimal(10,2) | 0.00
            $insert_data['edareas'] = '';// 不参加满包邮的地区 ，0 : 不支持满件包邮 | YES | text |

            $insert_data['diyformtype'] = '0';// 自定义表单类型 0 关闭 1 附加形式 2 替换模式 | YES | tinyint(1) | 0
            $insert_data['diyformid']   = '0';// 自定义表单ID | YES | int(11) | 0
            $insert_data['diymode']     = '0';// 自定义表单模式 0 系统默认 1 点击立即购买时填写 2 确认订单时填写 | YES | tinyint(1) | 0


            $insert_data['isdiscount_title']     = '';// v2 促销标题 | YES | varchar(255) |
            $insert_data['isdiscount_time']      = '0';// v2 促销结束时间 | YES | int(11) | 0
            $insert_data['isdiscount_discounts'] = '';// v2 促销价格 数字为价格 百分数 为折扣 | YES | text |
            $insert_data['commission']           = '';// v2 分销 | YES | text |

            $insert_data['allcates'] = '';// v2 好象无用 | YES | text |


            $insert_data['invoice'] = '1';// v2 提供发票 | YES | tinyint(3) | 0

            $insert_data['repair'] = '0';// v2 保修 | YES | tinyint(3) | 0
            $insert_data['seven']  = '0';// v2 7天无理由退换 | YES | tinyint(3) | 0
            $insert_data['money']  = '';// v2 余额返现 | YES | varchar(255) |

            $insert_data['province']           = '';// v2 商品所在省 如为空则显示商城所在 | YES | varchar(255) |
            $insert_data['city']               = '';// v2 商品所在城市 如为空则显示商城所在 | YES | varchar(255) |
            $insert_data['buyshow']            = '0';// v2 购买后可见 0 关闭 1 开启 | YES | tinyint(1) | 0
            $insert_data['buycontent']         = '';// v2 购买可见开启后的内容 | YES | text |
            $insert_data['virtualsend']        = '0';// v2 自动发货 0 否 1 是 ，注：自动发货后，订单自动完成 | YES | tinyint(1) | 0
            $insert_data['virtualsendcontent'] = '';// v2 自动发货内容 | YES | text |
            $insert_data['verifytype']         = '0';// v2 核销类型 0 按订单核销 1 按次核销 2 按消费码核销 | YES | tinyint(1) | 0
            $insert_data['diyfields']          = '';// v2 | YES | text |
            $insert_data['diysaveid']          = '0';// v2 | YES | int(11) | 0
            $insert_data['diysave']            = '0';// v2 | YES | tinyint(1) | 0
            $insert_data['quality']            = '0';// v2 正品保证 | YES | tinyint(3) | 0
            $insert_data['groupstype']         = '0';// v2 是否支持拼团 0 否 1 支持 | NO | tinyint(1) unsigned | 0
            $insert_data['showtotal']          = '0';// v2 显示库存 0 不显示 1 显示 | NO | tinyint(1) unsigned | 0
            $insert_data['sharebtn']           = '0';// v2 分享按钮链接方式 0 弹出关注提示层 1 跳转至商品海报 | NO | tinyint(1) | 0


            $insert_data['checked'] = '0';// v2 审核状态 | YES | tinyint(3) | 0

            $insert_data['catesinit3']   = '';// v2 是否初始化分类信息 标识字段无用 | YES | text |
            $insert_data['showtotaladd'] = '0';// v2 是否调整过显示库存 标识字段无用 | YES | tinyint(1) | 0
            $insert_data['merchsale']    = '0';// v2 手机端使用的价格 0 当前设置促销价格 1 商户设置促销价格 | YES | tinyint(1) | 0
            $insert_data['keywords']     = '';// v2 关键词 | YES | varchar(255) |

            $insert_data['catch_id']     = '';// v2 抓取产品信息ID | YES | varchar(255) |
            $insert_data['catch_url']    = '';// v2 抓取产品地址 | YES | varchar(255) |
            $insert_data['catch_source'] = '';// v2 抓取产品来源 | YES | varchar(255) |

            $insert_data['minpriceupdated'] = '0';// v2 是否更新过价格 标识字段无用 | YES | tinyint(1) | 0
            $insert_data['labelname']       = '';// v2 商品标签 | YES | text |
            $insert_data['autoreceive']     = '0';// v2 自动收货 0 系统设置 -1 不自动收货 >0 天数 | YES | int(11) | 0
            $insert_data['cannotrefund']    = '0';// v2 不允许退货 | YES | tinyint(3) | 0

            $insert_data['bargain'] = '0';// v2 砍价 | YES | int(11) | 0

            $insert_data['buyagain']            = '0.00';// v2 重复购买折扣 | YES | decimal(10,2) | 0.00
            $insert_data['buyagain_islong']     = '0';// v2 购买一次后,以后都使用这个折扣 0 否 1 是 | YES | tinyint(1) | 0
            $insert_data['buyagain_condition']  = '0';// v2 使用条件 0 付款后 1 完成后 | YES | tinyint(1) | 0
            $insert_data['buyagain_sale']       = '0';// v2 可否使用优惠 （重复购买时,是否与其他优惠共享!其他优惠享受后,再使用这个折扣） | YES | tinyint(1) | 0
            $insert_data['buyagain_commission'] = '';// v2 复购分销 | YES | text |

            $insert_data['diypage'] = '';// v2 自定义页面ID | YES | int(11) |


            $insert_data['old_shop_goods_id'] = $params['goods_id'];//  | NO | int(11) | 0

            $insert_data['uniacid']    = $_W['uniacid'];
            $insert_data['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $update_data = array();

            $this->toggleSaveOrUpdateByJdVopApiProductGetDetail($update_data, $params['category']);

            $update_data['merchid'] = $params['merchid'];// v2 商户ID | YES | int(11) | 0

            $update_data['status']   = $params['status'];// 状态 0 下架 1 上架 2 赠品上架 | YES | tinyint(1) | 1
            $update_data['title']    = $params['name'];// 商品名称 | YES | varchar(100) |
            $update_data['subtitle'] = $params['introductions'];// v2 子标题 | YES | varchar(255) |

            $update_data['minprice'] = $params['referprice'];// v2 多规格中最小价格，无规格时显示销售价 | YES | decimal(10,2) | 0.00
            $update_data['maxprice'] = $params['referprice'];// v2 多规格中最大价格，无规格时显示销售价 | YES | decimal(10,2) | 0.00

            $update_data['thumb']       = $params['pic'];// 商品图 | YES | varchar(255) |
            $update_data['thumb_first'] = '0';// v2 详情页面显示首图 0 不显示 1 显示 注：首图为列表页使用，尺寸较小 | YES | tinyint(3) | 0
            $update_data['thumb_url']   = 'a:0:{}';// 缩略图地址 | YES | text |

            // dispatchtype
//                = 0 选择运费模板
//                = 1 统一邮费
//            dispatchprice 手功设定
//            为了兼容京东，在导入时设定统一邮费与设定邮费为0
            $update_data['dispatchtype']  = '1';// 配送类型 0 运费模板 1 统一邮费 | YES | tinyint(1) | 0
            $update_data['dispatchid']    = '0';// 配送ID | YES | int(11) | 0
            $update_data['dispatchprice'] = '0.00';// 统一邮费 | YES | decimal(10,2) | 0.00

            $update_data['total'] = $params['stock'];// 商品库存 | YES | int(10) | 0

            $update_data['unit']    = $params['unit_id'];// 商品单位 | YES | varchar(5) |
            $update_data['content'] = $params['content'];// 商品详情 | YES | text |

            $update_data['productprice'] = $params['oldprice'];// 商品原价 | YES | decimal(10,2) | 0.00
            $update_data['marketprice']  = $params['referprice'];// 商品现价 | YES | decimal(10,2) | 0.00

            $update_data['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $update_data, $column);

        }

    }

    private function toggleSaveOrUpdateByJdVopApiProductGetDetail(&$data, $jd_vop_category)
    {

        global $_GPC, $_W;

//        include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
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

        /******************************************  增加关联的内部分类  ****************************************************/

        $other_cates = array($pcateid,$ccateid,$tcateid);
        $other_cates = implode(',',$other_cates);
        $self_cates = pdo_fetchall(
            ' select cr.self_category,cr.other_category,c.level ' .
            ' from ' . tablename('superdesk_shop_category_relation') . ' as cr ' .
            ' left join ' . tablename('superdesk_shop_category') . ' as c on c.id = cr.self_category ' .
            ' where cr.other_category in (' . $other_cates . ') '
        );

        foreach($self_cates as $k => $v) {

            if($v['other_category'] == $jd_vop_category[2]){
                $cates[] = $v['self_category'];

                if ($v['level'] == 1) {
                    $pcates[] = $v['self_category'];
                } elseif ($v['level'] == 2) {
                    $ccates[] = $v['self_category'];
                } elseif ($v['level'] == 3) {
                    $tcates[] = $v['self_category'];
                }
            }
        }

        /******************************************  增加关联的内部分类  ****************************************************/

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

    public function getBySkusAndMerchid($skus,$merchid)
    {
        if(empty($skus)){
            return array();
        }

        $sql  =
            ' SELECT id,goodssn ' .
            ' FROM ' . tablename($this->table_name) .
            ' where goodssn in (' . $skus . ') AND merchid = :merchid ';

        $data = pdo_fetchall($sql,array(':merchid' => $merchid));

        return $data;
    }
}