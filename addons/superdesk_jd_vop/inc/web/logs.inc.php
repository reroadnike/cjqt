<?php
/**
 * 京东调用日志
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/18
 * Time: 10:18
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=logs
 */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/logs.class.php');

$logs = new logsModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $logs->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'url'         => $_GPC['url'],// url
            'method'      => $_GPC['method'],// method
            'post_fields' => $_GPC['post_fields'],// post_fields
            'curl_info'   => $_GPC['curl_info'],// curl_info
            'response'    => $_GPC['response'],// response

        );
        $logs->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('logs', array('op' => 'list')), 'success');


    }
    include $this->template('logs_edit');

} elseif ($op == 'list') {


    $select_list_api = array(
        array(
            'key'  => '/api/area/checkArea',
            'name' => '地区-----/api/area/checkArea'
        ),
        array(
            'key'  => '/api/area/getCity',
            'name' => '地区-----/api/area/getCity'
        ),
        array(
            'key'  => '/api/area/getCounty',
            'name' => '地区-----/api/area/getCounty'
        ),
        array(
            'key'  => '/api/area/getTown',
            'name' => '地区-----/api/area/getTown'
        ),

        array(
            'key'  => '/api/order/cancel',
            'name' => '订单----/api/order/cancel'
        ),
        array(
            'key'  => '/api/order/confirmOrder',
            'name' => '订单----/api/order/confirmOrder'
        ),
        array(
            'key'  => '/api/order/getFreight',
            'name' => '订单----/api/order/getFreight'
        ),
        array(
            'key'  => '/api/order/orderTrack',
            'name' => '订单----/api/order/orderTrack'
        ),
        array(
            'key'  => '/api/order/selectJdOrder',
            'name' => '订单----/api/order/selectJdOrder'
        ),
        array(
            'key'  => '/api/order/submitOrder',
            'name' => '订单----/api/order/submitOrder'
        ),



        array(
            'key'  => '/api/price/getBalance',
            'name' => '价格----/api/price/getBalance'
        ),
        array(
            'key'  => '/api/price/getBalanceDetail',
            'name' => '价格----/api/price/getBalanceDetail'
        ),
        array(
            'key'  => '/api/price/getPrice',
            'name' => '价格----/api/price/getPrice'
        ),



        array(
            'key'  => '/api/product/getCategory',
            'name' => '商品----/api/product/getCategory'
        ),
        array(
            'key'  => '/api/product/getDetail',
            'name' => '商品----/api/product/getDetail'
        ),
        array(
            'key'  => '/api/product/getSimilarSku',
            'name' => '商品----/api/product/getSimilarSku'
        ),
        array(
            'key'  => '/api/product/getSkuByPage',
            'name' => '商品----/api/product/getSkuByPage'
        ),
        array(
            'key'  => '/api/product/getSku',
            'name' => '商品----/api/product/getSku'
        ),// TODO
        array(
            'key'  => '/api/product/skuImage',
            'name' => '商品----/api/product/skuImage'
        ),
        array(
            'key'  => '/api/product/skuState',
            'name' => '商品----/api/product/skuState'
        ),


        array(
            'key'  => '/api/stock/getNewStockById',
            'name' => '库存----/api/stock/getNewStockById'
        ),
        array(
            'key'  => '/api/stock/getStockById',
            'name' => '库存----/api/stock/getStockById'
        ),


        array(
            'key'  => '/api/search/search',
            'name' => '搜索----/api/search/search'
        ),


        array(
            'key'  => '/api/message/get',
            'name' => '消息----/api/message/get'
        ),
        array(
            'key'  => '/api/message/del',
            'name' => '消息----/api/message/del'
        ),


        array(
            'key'  => '/oauth2/accessToken',
            'name' => '授权-----/oauth2/accessToken'
        ),
        array(
            'key'  => '/oauth2/refreshToken',
            'name' => '授权-----/oauth2/refreshToken'
        )

    );



    $search = $_GPC['search'];



    $search['api'] = isset($search['api']) ? $search['api'] : '/api/order/submitOrder';

    $search['success'] = isset($search['success']) ? $search['success'] : 0;


    socket_log(json_encode($search));
    if ($search == null) {
        $search['start'] = strtotime("-1 year", time());
        $search['end']   = strtotime("+1 hours", time());
    }
    else {
        $search['start'] = strtotime($search['start'] . ' 00:00:00');
        $search['end']   = strtotime($search['end'] . ' 23:59:59');
    }

    socket_log(json_encode($search));



//    $where = array(
//        'api'     => $search['api'],
//        'success' => $search['success']
//    );
//    $where['start']   = date('Y-m-d 00:00:00', $search['start']);
//    $where['end']     = date('Y-m-d 23:59:59', $search['end']);

//    $where['start']   = $search['start'];
//    $where['end']     = $search['end'];

//    socket_log(json_encode($where, JSON_UNESCAPED_UNICODE));

    $page      = $_GPC['page'];
    $page_size = 30;

    $result    = $logs->queryAll($search, $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('logs_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $logs->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $logs->delete($id);

    message('删除成功！', referer(), 'success');
}
