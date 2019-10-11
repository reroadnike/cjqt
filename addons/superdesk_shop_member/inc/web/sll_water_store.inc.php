<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_water_store */

global $_GPC, $_W;
$active='sll_water_store';

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_water_store.class.php');
$_sll_water_storeModel = new sll_water_storeModel();

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_goods.class.php');
$_sll_goodsModel = new sll_goodsModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $_sll_water_storeModel->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'store_id'         => $_GPC['store_id'],//
            'beginStoreTime'   => $_GPC['beginStoreTime'],// 上班时间:
            'endTime'          => $_GPC['endTime'],// 结束时间(到期时间)
            'store_name'       => $_GPC['store_name'],// 店名
            'store_address'    => $_GPC['store_address'],// 水店地址
            'store_code'       => $_GPC['store_code'],// 水店编号
            'ctime'            => $_GPC['ctime'],// 创建时间
            'status'           => $_GPC['status'],// 0:正常1:到期2:暂停400:删除
            'userid'           => $_GPC['userid'],// 外键/市级ID
            'store_city'       => $_GPC['store_city'],// 所属城市
            'store_account'    => $_GPC['store_account'],// 水店账号
            'phone'            => $_GPC['phone'],// 电话
            'store_user'       => $_GPC['store_user'],// 店主
            'bank_card_number' => $_GPC['bank_card_number'],// 银行账号
            'bank_name'        => $_GPC['bank_name'],// 开户行
            'bank_account'     => $_GPC['bank_account'],// 开户人
            'phone_type'       => $_GPC['phone_type'],// 联系方式
            'store_province'   => $_GPC['store_province'],// 所属省
            'endStoreTime'     => $_GPC['endStoreTime'],// 下班时间
            'store_pwd'        => $_GPC['store_pwd'],// 密码
            'store_imageUrl1'  => $_GPC['store_imageUrl1'],// 门店图片
            'store_imageUrl2'  => $_GPC['store_imageUrl2'],// 门店图片
            'store_imageUrl3'  => $_GPC['store_imageUrl3'],// 门店图片
            'store_codeUrl1'   => $_GPC['store_codeUrl1'],// 二维码url
            'telmunber'        => $_GPC['telmunber'],// 固定电话
            'store_district'   => $_GPC['store_district'],// 所属区县
            'store_street'     => $_GPC['store_street'],// 所属街道
            'store_addre_id'   => $_GPC['store_addre_id'],// 地址外键
            'store_json'       => $_GPC['store_json'],// 数据格式

        );
        $_sll_water_storeModel->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_water_store', array('op' => 'list')), 'success');


    }
    include $this->template('sll_water_store_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where  = array('id' => $id);

            $_sll_water_storeModel->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_water_store', array('op' => 'list')), 'success');
    }

    $page      = $_GPC['page'];
    $page_size = 40;

    $result    = $_sll_water_storeModel->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $count_goods = 0;

    // 总店 查过 是没有的
//    $zero_store = array(
//        'total' =>$_sll_goodsModel->countByOriginEq1AndUserId(0)
//    );
//
//    $count_goods = $count_goods + $zero_store['total'];




    foreach ($list as $index => $__store){

        $list[$index]['total'] = $_sll_goodsModel->countByOriginEq1AndUserId($__store['store_id']);
        $count_goods = $count_goods + $list[$index]['total'];
    }

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_water_store_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $_sll_water_storeModel->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $_sll_water_storeModel->delete($id);

    message('删除成功！', referer(), 'success');
}

