<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=t_base_district */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/t_base_district.class.php');
$t_base_district = new t_base_districtModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $t_base_district->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'ID' => $_GPC['ID'],// 
    'NAME' => $_GPC['NAME'],// 
    'ENG_NAME' => $_GPC['ENG_NAME'],// 
    'BRIEF_SPELL' => $_GPC['BRIEF_SPELL'],// 
    'DISTRICT_LEVEL' => $_GPC['DISTRICT_LEVEL'],// 
    'PARENT_ID' => $_GPC['PARENT_ID'],// 
    'SEQ_NO' => $_GPC['SEQ_NO'],// 
    'CODE' => $_GPC['CODE'],// 
    'AREA_CODE' => $_GPC['AREA_CODE'],// 

        );
        $t_base_district->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('t_base_district', array('op' => 'list')), 'success');


    }
    include $this->template('t_base_district_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $t_base_district->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('t_base_district', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $t_base_district->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('t_base_district_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $t_base_district->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $t_base_district->delete($id);

    message('删除成功！', referer(), 'success');
}

