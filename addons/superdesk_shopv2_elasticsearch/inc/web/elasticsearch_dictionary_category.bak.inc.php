<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/05/17
 * Time: 18:53
 * http://192.168.1.124/smart_office_building/web/index.php?c=site&a=entry&m=superdesk_shopv2_elasticsearch&do=elasticsearch_dictionary_category
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/elasticsearch_dictionary_category.class.php');



$_elasticsearch_dictionary_categoryModel = new elasticsearch_dictionary_categoryModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $_elasticsearch_dictionary_categoryModel->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'parentid' => $_GPC['parentid'],// 上级分类ID,0为第一级
    'name' => $_GPC['name'],// 分类名称
    'description' => $_GPC['description'],// 分类介绍
    'displayorder' => $_GPC['displayorder'],// 排序
    'enabled' => $_GPC['enabled'],// 是否开启
    'level' => $_GPC['level'],// 分类是在几级

        );
        $_elasticsearch_dictionary_categoryModel->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('elasticsearch_dictionary_category', array('op' => 'list')), 'success');


    }
    include $this->template('elasticsearch_dictionary_category_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $_elasticsearch_dictionary_categoryModel->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('elasticsearch_dictionary_category', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $_elasticsearch_dictionary_categoryModel->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('elasticsearch_dictionary_category_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $_elasticsearch_dictionary_categoryModel->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $_elasticsearch_dictionary_categoryModel->delete($id);

    message('删除成功！', referer(), 'success');
}

