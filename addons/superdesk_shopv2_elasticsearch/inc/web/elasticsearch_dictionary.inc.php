<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/05/17
 * Time: 18:53
 * http://192.168.1.124/smart_office_building/web/index.php?c=site&a=entry&m=superdesk_shopv2_elasticsearch&do=elasticsearch_dictionary
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/elasticsearch_dictionary.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/elasticsearch_dictionary_category.class.php');



$_elasticsearch_dictionaryModel = new elasticsearch_dictionaryModel();
$_elasticsearch_dictionary_categoryModel = new elasticsearch_dictionary_categoryModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $_elasticsearch_dictionaryModel->getOne($_GPC['id']);
    $cates = explode(',', $item['cates']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'word' => $_GPC['word'],// 词
            'pcate' => $_GPC['pcate'],// 一级分类ID
            'ccate' => $_GPC['ccate'],// 二级分类ID
            'tcate' => $_GPC['tcate'],// 三级分类ID
            'cates' => $_GPC['cates'],// 多重分类数据集
            'pcates' => $_GPC['pcates'],// 一级多重分类
            'ccates' => $_GPC['ccates'],// 二级多重分类
            'tcates' => $_GPC['tcates'],// 三级多重分类
            'enabled' => $_GPC['enabled'],// 是否开启

        );

        if (is_array($_GPC['cates'])) {

            $cates = $_GPC['cates'];

            foreach ($cates as $key => $cid) {

                $c = pdo_fetch(
                    ' select level ' .
                    ' from ' . tablename('elasticsearch_dictionary_category') .
                    ' where id=:id and uniacid=:uniacid limit 1',
                    array(
                        ':id'      => $cid,
                        ':uniacid' => $_W['uniacid']
                    )
                );

                if ($c['level'] == 1) {
                    $pcates[] = $cid;
                } else if ($c['level'] == 2) {
                    $ccates[] = $cid;
                } else if ($c['level'] == 3) {
                    $tcates[] = $cid;
                }
                if ($key == 0) {
                    if ($c['level'] == 1) {

                        $pcateid = $cid;

                    } else if ($c['level'] == 2) {

                        $crow = pdo_fetch(
                            ' select parentid ' .
                            ' from ' . tablename('elasticsearch_dictionary_category') .
                            ' where id=:id and uniacid=:uniacid limit 1',
                            array(
                                ':id'      => $cid,
                                ':uniacid' => $_W['uniacid']
                            )
                        );

                        $pcateid = $crow['parentid'];
                        $ccateid = $cid;

                    } else if ($c['level'] == 3) {

                        $tcateid = $cid;
                        $tcate   = pdo_fetch(
                            ' select id,parentid ' .
                            ' from ' . tablename('elasticsearch_dictionary_category') .
                            ' where id=:id ' .
                            '       and uniacid=:uniacid ' .
                            ' limit 1',
                            array(
                                ':id'      => $cid,
                                ':uniacid' => $_W['uniacid']
                            )
                        );

                        $ccateid = $tcate['parentid'];
                        $ccate   = pdo_fetch(
                            ' select id,parentid ' .
                            ' from ' . tablename('elasticsearch_dictionary_category') .
                            ' where id=:id ' .
                            '       and uniacid=:uniacid ' .
                            ' limit 1',
                            array(
                                ':id'      => $ccateid,
                                ':uniacid' => $_W['uniacid']
                            )
                        );

                        $pcateid = $ccate['parentid'];
                    }
                }
            }
        }

        $params['pcate'] = $pcateid;
        $params['ccate'] = $ccateid;
        $params['tcate'] = $tcateid;

        $params['cates'] = $cates ? implode(',', $cates) : '';

        $params['pcates'] = $pcates ? implode(',', $pcates) : '';
        $params['ccates'] = $ccates ? implode(',', $ccates) : '';
        $params['tcates'] = $tcates ? implode(',', $tcates) : '';

        $_elasticsearch_dictionaryModel->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('elasticsearch_dictionary', array('op' => 'list')), 'success');


    }

    $category = $_elasticsearch_dictionary_categoryModel->getFullCategory(true,true);

    include $this->template('elasticsearch_dictionary_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $_elasticsearch_dictionaryModel->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('elasticsearch_dictionary', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $_elasticsearch_dictionaryModel->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $category = $_elasticsearch_dictionary_categoryModel->getFullCategory(false,true);

    foreach($list as &$v){
        $cates_name = array();
        $ccates_name = array();
        $pcates_name = array();
        $tcates_name = array();
        $cates = $v['cates'] ? explode(',',$v['cates']) : array();
        $ccates = $v['ccates'] ? explode(',',$v['ccates']) : array();
        $pcates = $v['pcates'] ? explode(',',$v['pcates']) : array();
        $tcates = $v['tcates'] ? explode(',',$v['tcates']) : array();
        foreach($category as $cv){
            if($v['ccate'] == $cv['id']){
                $v['ccate'] = $cv['name'];
            }
            if($v['pcate'] == $cv['id']){
                $v['pcate'] = $cv['name'];
            }
            if($v['tcate'] == $cv['id']){
                $v['tcate'] = $cv['name'];
            }
            if(!empty($v['cates'])){
                if(in_array($cv['id'],$cates)){
                    $cates_name[] = $cv['name'];
                }
            }
            if(!empty($v['ccates'])){
                if(in_array($cv['id'],$ccates)){
                    $ccates_name[] = $cv['name'];
                }
            }
            if(!empty($v['pcates'])){
                if(in_array($cv['id'],$pcates)){
                    $pcates_name[] = $cv['name'];
                }
            }
            if(!empty($v['tcates'])){
                if(in_array($cv['id'],$tcates)){
                    $tcates_name[] = $cv['name'];
                }
            }
        }

        $v['cates'] = implode(',',$cates_name);
        $v['ccates'] = implode(',',$ccates_name);
        $v['pcates'] = implode(',',$pcates_name);
        $v['tcates'] = implode(',',$tcates_name);
        $v['createtime'] = $v['createtime'] >0 ? date('Y-m-d', $v['createtime']): '';
    }

    $pager = pagination($total, $page, $page_size);

    include $this->template('elasticsearch_dictionary_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $_elasticsearch_dictionaryModel->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $_elasticsearch_dictionaryModel->delete($id);

    message('删除成功！', referer(), 'success');

} elseif ($op == 'excelTemp') {
    $columns = array();
    $columns[] = array('title' => '词', 'field' => '', 'width' => 64);
    m('excel')->temp('批量导入热词模板', $columns);

} elseif ($op == 'excelImport') {

    $rows = m('excel')->import('excelfile');
    $num = count($rows);
    if($num > 0){
        foreach ($rows as $rownum => $col ) {
            $word = trim($col[0]);

            if (empty($word)) {
                continue;
            }

            $params = array(
                'word'    => $word,// 词
                'pcate'   => 0,// 一级分类ID
                'ccate'   => 0,// 二级分类ID
                'tcate'   => 0,// 三级分类ID
                'cates'   => 0,// 多重分类数据集
                'pcates'  => 0,// 一级多重分类
                'ccates'  => 0,// 二级多重分类
                'tcates'  => 0,// 三级多重分类
                'enabled' => 1,// 是否开启
            );

            $_elasticsearch_dictionaryModel->saveOrUpdateByColumn($params, array('word' => $word));
        }
    }

    message('成功！', $this->createWebUrl('elasticsearch_dictionary', array('op' => 'list')), 'success');
} elseif ($op == 'logMoreInsertDictionary') {

    $words = $_GPC['words'];

    $num = count($words);
    if($num > 0){
        $words = array_unique($words);

        foreach ($words as $word ) {

            if (empty($word)) {
                continue;
            }

            $params = array(
                'word'    => $word,// 词
                'pcate'   => 0,// 一级分类ID
                'ccate'   => 0,// 二级分类ID
                'tcate'   => 0,// 三级分类ID
                'cates'   => 0,// 多重分类数据集
                'pcates'  => 0,// 一级多重分类
                'ccates'  => 0,// 二级多重分类
                'tcates'  => 0,// 三级多重分类
                'enabled' => 1,// 是否开启
            );

            $_elasticsearch_dictionaryModel->saveOrUpdateByColumn($params, array('word' => $word));
        }
    }

    show_json(1);
} elseif ($op == 'logInsertDictionary') {

    $word = $_GPC['word'];

    if (!empty($word)) {
        $params = array(
            'word'    => $word,// 词
            'pcate'   => 0,// 一级分类ID
            'ccate'   => 0,// 二级分类ID
            'tcate'   => 0,// 三级分类ID
            'cates'   => 0,// 多重分类数据集
            'pcates'  => 0,// 一级多重分类
            'ccates'  => 0,// 二级多重分类
            'tcates'  => 0,// 三级多重分类
            'enabled' => 1,// 是否开启
        );

        $_elasticsearch_dictionaryModel->saveOrUpdateByColumn($params, array('word' => $word));
    }

    message('成功！', $this->createWebUrl('member_search_log', array('op' => 'list')), 'success');
}

