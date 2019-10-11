<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Dispatch_SuperdeskShopV2Model
{
    /**
     * 计算运费模板运费
     *
     * @param     $param         件数或者是净重
     * @param     $d             运费模板表单条数据．
     *                           　　Array
     *                           (
     *                           [id] => 10
     *                           [uniacid] => 17
     *                           [dispatchname] => 测试按件
     *                           [dispatchtype] => 0
     *                           [displayorder] => 0
     *                           [firstprice] => 0.00
     *                           [secondprice] => 0.00
     *                           [firstweight] => 1000
     *                           [secondweight] => 1000
     *                           [express] =>
     *                           [areas] => a:0:{}
     *                           [carriers] =>
     *                           [enabled] => 1
     *                           [calculatetype] => 1
     *                           [firstnum] => 1
     *                           [secondnum] => 1
     *                           [firstnumprice] => 100.00
     *                           [secondnumprice] => 55.00
     *                           [isdefault] => 1
     *                           [shopid] => 0
     *                           [merchid] => 54
     *                           [nodispatchareas] =>
     *                           )
     * @param int $calculatetype 类型 0按重量 1按件
     *
     * @return float|int
     */
    public function getDispatchPrice($param, $d, $calculatetype = -1)
    {
        //模板数据为空直接返回0
        if (empty($d)) {
            return 0;
        }
        $price = 0;

        //传入的计算类型为-1时从模板数据中拿
        if ($calculatetype == -1) {
            $calculatetype = $d['calculatetype'];
        }

        if ($calculatetype == 1) {  //按件计算

            //当首件数大于等于商品件数时,金额为首件金额
            if ($param <= $d['firstnum']) {
                $price = floatval($d['firstnumprice']);
            } else {
                $price         = floatval($d['firstnumprice']); //首件金额
                $secondweight  = $param - floatval($d['firstnum']); //商品总数 - 首件数 = 商品续件数
                $dsecondweight = ((floatval($d['secondnum']) <= 0 ? 1 : floatval($d['secondnum'])));    //模板续件数
                $secondprice   = 0;

                if (($secondweight % $dsecondweight) == 0) {    //商品续件 % 模板续件  即商品续件是否模板续件的倍数
                    $secondprice = ($secondweight / $dsecondweight) * floatval($d['secondnumprice']);   //商品续件/模板续件得出倍数*模板续件金额
                } else {
                    $secondprice = ceil($secondweight / $dsecondweight) * floatval($d['secondnumprice']);  //跟上面一样.只是这里向上取整
                }

                $price += $secondprice;     //首件金额 + 续件金额
            }
        } else if ($param <= $d['firstweight']) {   //否则假如 首重大于等于商品净重

            if (0 < $param) {   //假如净重大于0,金额为首重金额
                $price = floatval($d['firstprice']);
            } else {    //否则金额为0  之前那些没有设净重的商品应该就是到了这个位置..
                $price = 0;
            }

        } else {    //否则.. 按逻辑而言 不是按件计算,商品净重比首重大 那应该是只剩下有续重一种情况.

            $price         = floatval($d['firstprice']);    //首重金额
            $secondweight  = $param - floatval($d['firstweight']);  //商品净重 - 模板首重
            $dsecondweight = ((floatval($d['secondweight']) <= 0 ? 1 : floatval($d['secondweight'])));  //模板续重
            $secondprice   = 0;

            if (($secondweight % $dsecondweight) == 0) {    //商品续重 % 模板续重  即商品续重是否模板续重的倍数
                $secondprice = ($secondweight / $dsecondweight) * floatval($d['secondprice']);  //商品续件/模板续件得出倍数*模板续件金额
            } else {
                $secondprice = ceil($secondweight / $dsecondweight) * floatval($d['secondprice']); //跟上面一样.只是这里向上取整
            }
            $price += $secondprice;     //首件金额 + 续件金额
        }
        return $price;
    }

    /**
     * @param $areas    数据库中某条运费模板的areas
     * @param $city     所要运送的地区..城市id
     * @param $param    商品总数或商品总净重
     * @param $d        数据库中某条运费模板完整数据
     *
     * @return float|int
     */
    public function getCityDispatchPrice($areas, $city, $param, $d)
    {
        if (is_array($areas) && (0 < count($areas))) {

            foreach ($areas as $area) {
                $citys = explode(';', $area['citys']);
                //return $this->getDispatchPrice($param, $area, $d['calculatetype']);
                if (in_array($city, $citys)) {
                    return $this->getDispatchPrice($param, $area, $d['calculatetype']);
                }

            }
        }
        return $this->getDispatchPrice($param, $d);
    }

    /**
     * 获取默认运费模板
     *
     * @param int $merchid
     *
     * @return mixed
     */
    public function getDefaultDispatch($merchid = 0)
    {
        global $_W;

        $sql    =
            ' select * ' .
            ' from ' . tablename('superdesk_shop_dispatch') .
            ' where isdefault=1 ' .
            '       and uniacid=:uniacid ' .
            '       and merchid=:merchid ' .
            '       and enabled=1 Limit 1';
        $params = array(
            ':uniacid' => $_W['uniacid'],
            ':merchid' => $merchid
        );

        $data = pdo_fetch($sql, $params);

        return $data;
    }

    /**
     * 获取商户第一条运费模板
     *
     * @param int $merchid
     *
     * @return mixed
     */
    public function getNewDispatch($merchid = 0)
    {
        global $_W;

        $sql    =
            'select * ' .
            ' from ' . tablename('superdesk_shop_dispatch') .
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and merchid=:merchid ' .
            '       and enabled=1 ' .
            ' order by id desc ' .
            ' Limit 1';
        $params = array(
            ':uniacid' => $_W['uniacid'],
            ':merchid' => $merchid
        );
        $data   = pdo_fetch($sql, $params);
        return $data;
    }

    /**
     * 根据id获取运费模板
     *
     * @param $id
     *
     * @return mixed
     */
    public function getOneDispatch($id)
    {
        global $_W;

        $params = array(':uniacid' => $_W['uniacid']);

        if ($id == 0) {

            $sql =
                'select * ' .
                ' from ' . tablename('superdesk_shop_dispatch') .
                ' where ' .
                '       isdefault=1 ' .
                '       and uniacid=:uniacid ' .
                '       and enabled=1 ' .
                ' Limit 1';
        } else {

            $sql =
                'select * ' .
                ' from ' . tablename('superdesk_shop_dispatch') .
                ' where ' .
                '       id=:id ' .
                '       and uniacid=:uniacid ' .
                '       and enabled=1 ' .
                ' Limit 1';

            $params[':id'] = $id;
        }
        $data = pdo_fetch($sql, $params);
        return $data;
    }

    /**
     * 获取所有不配送地域
     *
     * @param array $areas
     *
     * @return array
     */
    public function getAllNoDispatchAreas($areas = array())
    {
        global $_W;

        // 后台->设置->交易->交易设置
        $tradeset                    = m('common')->getSysset('trade');
        $tradeset['nodispatchareas'] = iunserializer($tradeset['nodispatchareas']);

        $set_citys = array();

        $dispatch_citys = array();


        // 后台->设置->交易->交易设置->商城不配送区域->不配送区域
        if (!empty($tradeset['nodispatchareas'])) {
            $set_citys = explode(';', trim($tradeset['nodispatchareas'], ';'));
        }

        if (!empty($areas)) {
            $areas = iunserializer($areas);
            if (!empty($areas)) {
                $dispatch_citys = explode(';', trim($areas, ';'));
            }
        }
        $citys = array();

        if (!empty($set_citys)) {
            $citys = $set_citys;
        }

        if (!empty($dispatch_citys)) {
            $citys = array_merge($citys, $dispatch_citys);
            $citys = array_unique($citys);
        }
        return $citys;
    }

    /**
     * 根据商品获取不配送地域
     *
     * @param $goods
     *
     * @return array|string
     */
    public function getNoDispatchAreas($goods)
    {
        global $_W;

        if (($goods['type'] == 2) || ($goods['type'] == 3)) {
            return '';
        }

        if ($goods['dispatchtype'] == 1) {
            $nodispatchareas = $this->getAllNoDispatchAreas();
        } else {

            if (empty($goods['dispatchid'])) {
                $dispatch = m('dispatch')->getDefaultDispatch($goods['merchid']);
            } else {
                $dispatch = m('dispatch')->getOneDispatch($goods['dispatchid']);
            }

            if (empty($dispatch)) {
                $dispatch = m('dispatch')->getNewDispatch($goods['merchid']);
            }
            $nodispatchareas = $this->getAllNoDispatchAreas($dispatch['nodispatchareas']);
        }
        return $nodispatchareas;
    }
}