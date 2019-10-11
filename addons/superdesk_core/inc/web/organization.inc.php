<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_core&do=organization */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/organization.class.php');
$organization = new organizationModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $organization->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'ID' => $_GPC['ID'],
            'name' => $_GPC['name'],
            'code' => $_GPC['code'],
            'type' => $_GPC['type'],
            'telephone' => $_GPC['telephone'],
            'provinceCode' => $_GPC['provinceCode'],
            'provinceName' => $_GPC['provinceName'],
            'cityCode' => $_GPC['cityCode'],
            'cityName' => $_GPC['cityName'],
            'address' => $_GPC['address'],
            'lng' => $_GPC['lng'],
            'lat' => $_GPC['lat'],
            'status' => $_GPC['status'],
            'applicantName' => $_GPC['applicantName'],
            'applicantPhone' => $_GPC['applicantPhone'],
            'reviewRemark' => $_GPC['reviewRemark'],
            'applicantIdentity' => $_GPC['applicantIdentity'],
            'wxUserId' => $_GPC['wxUserId'],
            'createTime' => $_GPC['createTime'],
            'creator' => $_GPC['creator'],
            'modifyTime' => $_GPC['modifyTime'],
            'modifier' => $_GPC['modifier'],
            'isEnabled' => $_GPC['isEnabled'],
            'createtime_' => $_GPC['createtime_'],
            'enabled' => $_GPC['enabled'],

        );
        $organization->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('organization', array('op' => 'list')), 'success');


    }
    include $this->template('organization_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $organization->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('organization', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 15;

    $result = $organization->queryAll(array(), $page, $page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('organization_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $organization->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $organization->delete($id);

    message('删除成功！', referer(), 'success');
}

