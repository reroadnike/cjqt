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


echo "d";

$_elasticsearch_dictionary_categoryModel = new elasticsearch_dictionary_categoryModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

        $params = array(
            'name' => $_GPC['name'],// 分类名称
            'description' => $_GPC['description'],// 分类介绍
            'displayorder' => $_GPC['displayorder'],// 排序
            'enabled' => $_GPC['enabled'],// 是否开启
        );

        if(empty($id)){
            $parentid = empty($_GPC['parentid']) ? 0 : $_GPC['parentid'];
            $params['parentid'] = $parentid;
            if($parentid == 0){
                $params['level'] = 1;
            }else{
                $parent = $_elasticsearch_dictionary_categoryModel->getOne($parentid);
                $params['level'] = $parent['level']+1;
            }
        }

        $_elasticsearch_dictionary_categoryModel->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('elasticsearch_dictionary_category', array('op' => 'list')), 'success');


    }

    $item = $_elasticsearch_dictionary_categoryModel->getOne($_GPC['id']);

    include $this->template('elasticsearch_dictionary_category_edit');

} elseif ($op == 'list') {

    if($_W['ispost']){
        if (!empty($_GPC['datas'])) {
            $datas = json_decode(html_entity_decode($_GPC['datas']), true);

            if (!is_array($datas)) {
                show_json(0, '分类保存失败，请重试!');
            }

            $displayorder = count($datas);

            foreach ($datas as $row) {
                pdo_update('elasticsearch_dictionary_category', array('parentid' => 0, 'displayorder' => $displayorder, 'level' => 1),
                    array('id' => $row['id'])
                );
                if ($row['children'] && is_array($row['children'])) {
                    $displayorder_child = count($row['children']);

                    foreach ($row['children'] as $child) {
                        $cateids[] = $child['id'];
                        pdo_query('update ' . tablename('elasticsearch_dictionary_category') . ' set  parentid=:parentid,displayorder=:displayorder,level=2 where id=:id',
                            array(
                                ':displayorder' => $displayorder_child,
                                ':parentid' => $row['id'],
                                ':id' => $child['id']
                            )
                        );

                        --$displayorder_child;

                        if ($child['children'] && is_array($child['children'])) {

                            $displayorder_third = count($child['children']);

                            foreach ($child['children'] as $third) {
                                pdo_query('update ' . tablename('elasticsearch_dictionary_category') . ' set  parentid=:parentid,displayorder=:displayorder,level=3 where id=:id',
                                    array(
                                        ':displayorder' => $displayorder_third,
                                        ':parentid' => $child['id'],
                                        ':id' => $third['id']
                                    )
                                );

                                --$displayorder_third;
                            }
                        }

                    }
                }


                --$displayorder;
            }
        }
    }
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


    $category = $_elasticsearch_dictionary_categoryModel->queryAllNotLimit(array());

    $children = array();
    foreach ($category as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($category[$index]);
        }

    }

    include $this->template('elasticsearch_dictionary_category_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $_elasticsearch_dictionary_categoryModel->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $_elasticsearch_dictionary_categoryModel->delete($id);

    message('删除成功！', referer(), 'success');
} elseif ($op == 'enabled') {
    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $_elasticsearch_dictionary_categoryModel->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $_elasticsearch_dictionary_categoryModel->update(array('enabled'=>$_GPC['enabled']),$id);

    show_json(1);
}

