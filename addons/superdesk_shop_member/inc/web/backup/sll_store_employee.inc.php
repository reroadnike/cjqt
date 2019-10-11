<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_store_employee */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_store_employee.class.php');
$sll_store_employee = new sll_store_employeeModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_store_employee->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'employee_id' => $_GPC['employee_id'],// 
    'employee_name' => $_GPC['employee_name'],// 姓名
    'phone' => $_GPC['phone'],// 电话
    'employee_cardid' => $_GPC['employee_cardid'],// 身份证
    'cardid_imageUrl1' => $_GPC['cardid_imageUrl1'],// 身份证图片
    'cardd_imageUrl2' => $_GPC['cardd_imageUrl2'],// 身份证图片
    'store_imageUrl1' => $_GPC['store_imageUrl1'],// 店面图片
    'store_imageUrl2' => $_GPC['store_imageUrl2'],// 店面图片
    'store_imageUrl3' => $_GPC['store_imageUrl3'],// 店面图片
    'employee_imageUrl' => $_GPC['employee_imageUrl'],// 员工图片
    'store_id' => $_GPC['store_id'],// 
    'employee_type' => $_GPC['employee_type'],// 1;店主2:员工3:送水工
    'stated' => $_GPC['stated'],// 0:在职1;离职
    'employee_cardimage' => $_GPC['employee_cardimage'],// 

        );
        $sll_store_employee->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_store_employee', array('op' => 'list')), 'success');


    }
    include $this->template('sll_store_employee_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_store_employee->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_store_employee', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_store_employee->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_store_employee_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_store_employee->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_store_employee->delete($id);

    message('删除成功！', referer(), 'success');
}

