<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=zc_waterstoreandenterprise_relation */

global $_GPC, $_W;
$active='zc_waterstoreandenterprise_relation';

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_waterstoreandenterprise_relation.class.php');
$zc_waterstoreandenterprise_relation = new zc_waterstoreandenterprise_relationModel();


include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
$_virtualarchitectureService = new VirtualarchitectureService();

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/plugin/MerchService.class.php');
$_plugin_merchService = new MerchService();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $zc_waterstoreandenterprise_relation->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'w_e_id'     => $_GPC['w_e_id'],//
            'w_id'       => $_GPC['w_id'],// 供销商id
            'e_id'       => $_GPC['e_id'],// 企业id
            'w_e_ctime'  => $_GPC['w_e_ctime'],// 创建时间
            'w_e_status' => $_GPC['w_e_status'],// 状态（预留字段）

        );
        $zc_waterstoreandenterprise_relation->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('zc_waterstoreandenterprise_relation', array('op' => 'list')), 'success');


    }
    include $this->template('zc_waterstoreandenterprise_relation_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where  = array('id' => $id);

            $zc_waterstoreandenterprise_relation->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('zc_waterstoreandenterprise_relation', array('op' => 'list')), 'success');
    }

    $page      = $_GPC['page'];
    $page_size = 2000;

    $result    = $zc_waterstoreandenterprise_relation->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];


    foreach ($list as $index => $item){

        $list[$index]['enterprise_id'] = $_virtualarchitectureService->getCacheEnterprise2virtualarchitecture($item['e_id']);
        $list[$index]['merchid']       = $_plugin_merchService->getCacheStore2Merch($item['w_id']);

    }

    $pager = pagination($total, $page, $page_size);

    include $this->template('zc_waterstoreandenterprise_relation_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $zc_waterstoreandenterprise_relation->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $zc_waterstoreandenterprise_relation->delete($id);

    message('删除成功！', referer(), 'success');
}

