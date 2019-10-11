<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_address */

global $_GPC, $_W;

$active='sll_address';

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_address.class.php');
$sll_address = new sll_addressModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_address->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'address_id'      => $_GPC['address_id'],//
            'address_name'    => $_GPC['address_name'],// 地址
            'fansid'          => $_GPC['fansid'],// 用户id
            'phone'           => $_GPC['phone'],// 联系电话
            'user_name'       => $_GPC['user_name'],// 收件人
            'create_time'     => $_GPC['create_time'],// 创建时间
            'token'           => $_GPC['token'],//
            'address_state'   => $_GPC['address_state'],// 0：使用：1停止
            'address_default' => $_GPC['address_default'],// 1:默认地址,0:非默认地址
            'province'        => $_GPC['province'],// 省
            'city'            => $_GPC['city'],// 市
            'country'         => $_GPC['country'],// 区/县
            'pkCode'          => $_GPC['pkCode'],// 主键编码
            'street'          => $_GPC['street'],// 街道
            'citycode'        => $_GPC['citycode'],//
            'community_id'    => $_GPC['community_id'],//
            'community_name'  => $_GPC['community_name'],//
            'community_code'  => $_GPC['community_code'],//
            'provinceCode'    => $_GPC['provinceCode'],//
            'countryCode'     => $_GPC['countryCode'],//
            'streetCode'      => $_GPC['streetCode'],//

        );
        $sll_address->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_address', array('op' => 'list')), 'success');


    }
    include $this->template('sll_address_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where  = array('id' => $id);

            $sll_address->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_address', array('op' => 'list')), 'success');
    }

    $page      = $_GPC['page'];
    $page_size = 20;

    $result    = $sll_address->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_address_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_address->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_address->delete($id);

    message('删除成功！', referer(), 'success');
}

