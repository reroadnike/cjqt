<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/18
 * Time: 10:18
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=area
 */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/area.class.php');
$area = new areaModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $area->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'code' => $_GPC['code'],// code
    'parent_code' => $_GPC['parent_code'],// parent_code
    'level' => $_GPC['level'],// level
    'text' => $_GPC['text'],// text
    'state' => $_GPC['state'],// state
    'remark' => $_GPC['remark'],// 标注

        );
        $area->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('area', array('op' => 'list')), 'success');


    }
    include $this->template('area_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $area->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('area', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $area->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('area_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $area->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $area->delete($id);

    message('删除成功！', referer(), 'success');
}

