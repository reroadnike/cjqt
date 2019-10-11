<?php
/**
 *
 * For test
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 4/4/18
 * Time: 12:41 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2_elasticsearch&do=init_superdesk_shop_goods
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/search.class.php');
$_searchModel = new searchModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

$isExport = !empty($_GPC['out_put']) ? true : false;

$page      = $_GPC['page'] ? $_GPC['page'] : 1;
$page_size = $isExport ? 999999 : 100;

$params = array();

if (!empty($_GPC['search_time']) && !empty($_GPC['createtime'])) {
    $params['createtime'] = $_GPC['createtime'];
}

if (isset($_GPC['has_keyword']) && $_GPC['has_keyword'] !== '') {
    $params['has_keyword'] = $_GPC['has_keyword'];
	
    $params['keyword'] = $_GPC['has_keyword'];
}

//var_dump($params);

$result = $_searchModel->queryAllLeftJoin($params, $page, $page_size);
//print_r($result);exit();

foreach($result['data'] as $k => &$v){
    $v['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
}
unset($v);

if ($op == 'list') {

    if ($isExport) {

        $columns = array(
            array('title' => '搜索关键字', 'field' => 'keyword', 'width' => 24),
            array('title' => '用户', 'field' => 'realname', 'width' => 15),
            //array('title' => '最低价格', 'field' => 'minprice', 'width' => 12),
            //array('title' => '最高价格', 'field' => 'maxprice', 'width' => 12),
            array('title' => '筛选', 'field' => 'filters', 'width' => 24),
            array('title' => '分类名称', 'field' => 'category_name', 'width' => 12),
            array('title' => '排序', 'field' => 'order_by', 'width' => 12),
            array('title' => '创建时间', 'field' => 'createtime', 'width' => 15)
        );

        m('excel')->export(
            $result['data'],
            array(
                'title'   => '订单数据-' . date('Y-m-d-H-i', time()),
                'columns' => $columns
            )
        );

        return ;
    }

    $pager = pagination($result['total'], $result['page'], $result['page_size']);

    include $this->template('member_search_log');

}
