<?php

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/sdk/kdniao_sdk.php');

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/22/18
 * Time: 7:09 PM
 */
class KdniaoService
{

    private $_redis;
    private $kdniao_sdk;

    function __construct()
    {
        $this->_redis = new RedisUtil();

        $this->kdniao_sdk = new KdniaoSDK();

    }

    public function getExpressListAsThird($ShipperCode = 'YTO', $LogisticCode = '12345678')
    {
        global $_W,$_GPC;

        //由于传的ShipperCode是express表中的express而不是code,所以需要查一次表
        $code        = pdo_fetchcolumn(' select code from ' . tablename('superdesk_shop_express') . ' where express=:express', array(':express' => $ShipperCode));
        $ShipperCode = $code;

        $key = 'superdesk_shopv2_' . 'express_kdniao_' . $_W['uniacid'] . ':' . $LogisticCode;

//        print_r($this->_redis->isExists($key));die;
        if ($this->_redis->isExists($key) && $_GPC['test'] != 1) {
            $result = $this->_redis->get($key);
            $result = json_decode($result, true);

            $now = time();

            if ($now - $result['times'] <= 60 * 30) {
                return $result['data'];
            }
        }

        $result = $this->kdniao_sdk->getOrderTracesByJson($ShipperCode, $LogisticCode);

        if($_GPC['test'] == 1){
            var_dump($ShipperCode);
            var_dump($LogisticCode);
            var_dump($result);
            die;
        }

//        print_r($result);
//        print_r((int)$result['Success'] === 1 ? 100 : 200);die;

        if ((int)$result['Success'] === 1) {

            $express_data = $result['Traces'];

            $filter_express_data = array();

            foreach ($express_data as $k => $v) {
                $filter_express_data[] = array(
                    'step' => $v['AcceptStation'],
                    'time' => $v['AcceptTime']
                );
            }

            $redisData = array(
                'times' => time(),
                'data'  => $filter_express_data,
                'origin' => $result
            );

            $this->_redis->set($key, json_encode($redisData));

            return $redisData['data'];
        }

        return array('error' => $result['Reason']);

    }
}