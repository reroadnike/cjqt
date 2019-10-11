<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=special_address */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/special_address.class.php');
$special_address = new special_addressModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $special_address->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'name' => $_GPC['name'],// 特殊配送地址
    'url' => $_GPC['url'],// 
    'ctime' => $_GPC['ctime'],// 
    'status' => $_GPC['status'],// 1:使用 0：失效
    'province_code' => $_GPC['province_code'],// 省code
    'city_code' => $_GPC['city_code'],// 市code
    'country_code' => $_GPC['country_code'],// 区/县id
    'street_code' => $_GPC['street_code'],// 街道id
    'community_code' => $_GPC['community_code'],// 社区code
    'province_name' => $_GPC['province_name'],// 省
    'city_name' => $_GPC['city_name'],// 市
    'country_name' => $_GPC['country_name'],// 区/县
    'street_name' => $_GPC['street_name'],// 街道
    'community_name' => $_GPC['community_name'],// 社区
    'communityId' => $_GPC['communityId'],// 社区id

        );
        $special_address->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('special_address', array('op' => 'list')), 'success');


    }
    include $this->template('special_address_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $special_address->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('special_address', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $special_address->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('special_address_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $special_address->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $special_address->delete($id);

    message('删除成功！', referer(), 'success');
}

