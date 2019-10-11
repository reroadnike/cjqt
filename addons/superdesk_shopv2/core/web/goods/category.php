<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Category_SuperdeskShopV2Page extends WebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        if ($_W['ispost']) {

            if (!empty($_GPC['datas'])) {

                $datas = json_decode(html_entity_decode($_GPC['datas']), true);

                if (!is_array($datas)) {
                    show_json(0, '分类保存失败，请重试!');
                }


                $cateids      = array();
                $displayorder = count($datas);

                foreach ($datas as $row) {
                    $cateids[] = $row['id'];
                    pdo_update('superdesk_shop_category', array('parentid' => 0, 'displayorder' => $displayorder, 'level' => 1),
                        array('id' => $row['id'])
                    );
                    if ($row['children'] && is_array($row['children'])) {
                        $displayorder_child = count($row['children']);

                        foreach ($row['children'] as $child) {

                            $cateids[] = $child['id'];

                            pdo_query(
                                'update ' . tablename('superdesk_shop_category') .
                                ' set  parentid=:parentid,displayorder=:displayorder,level=2 where id=:id',
                                array(
                                    ':displayorder' => $displayorder_child,
                                    ':parentid'     => $row['id'],
                                    ':id'           => $child['id']
                                )
                            );

                            --$displayorder_child;

                            if ($child['children'] && is_array($child['children'])) {

                                $displayorder_third = count($child['children']);

                                foreach ($child['children'] as $third) {
                                    $cateids[] = $third['id'];
                                    pdo_query(
                                        'update ' . tablename('superdesk_shop_category') .
                                        ' set  parentid=:parentid,displayorder=:displayorder,level=3 where id=:id',
                                        array(
                                            ':displayorder' => $displayorder_third,
                                            ':parentid'     => $child['id'],
                                            ':id'           => $third['id']
                                        )
                                    );

                                    --$displayorder_third;

                                    if ($third['children'] && is_array($third['children'])) {
                                        $displayorder_fourth = count($third['children']);

                                        foreach ($child['children'] as $fourth) {
                                            $cateids[] = $fourth['id'];
                                            pdo_query(
                                                'update ' . tablename('superdesk_shop_category') .
                                                ' set  parentid=:parentid,displayorder=:displayorder,level=3 where id=:id',
                                                array(
                                                    ':displayorder' => $displayorder_third,
                                                    ':parentid'     => $third['id'],
                                                    ':id'           => $fourth['id']
                                                )
                                            );

                                            --$displayorder_fourth;
                                        }
                                    }

                                }
                            }

                        }
                    }


                    --$displayorder;
                }

                if (!empty($cateids)) {
                    pdo_query(
                        'delete from ' . tablename('superdesk_shop_category') .
                        ' where id not in (' . implode(',', $cateids) . ') and uniacid=:uniacid',
                        array(
                            ':uniacid' => $_W['uniacid']
                        )
                    );
                }


                plog('shop.category.edit', '批量修改分类的层级及排序');
                m('shop')->getCategory(true);
                m('shop')->getAllCategory(true);
                show_json(1);
            }

        }


        $children = array();
        $category = pdo_fetchall(
            'SELECT * FROM ' . tablename('superdesk_shop_category') .
            ' WHERE uniacid = \'' . $_W['uniacid'] . '\' ORDER BY parentid ASC, displayorder DESC');

        foreach ($category as $index => $row) {
            if (!empty($row['parentid'])) {
                $children[$row['parentid']][] = $row;
                unset($category[$index]);
            }

        }

        include $this->template();
    }

    public function add()
    {
        $this->post();
    }

    public function edit()
    {
        $this->post();
    }

    protected function post()
    {
        global $_W;
        global $_GPC;

        $parentid = intval($_GPC['parentid']);
        $id       = intval($_GPC['id']);

        if (!empty($id)) {

            $item = pdo_fetch(
                'SELECT * FROM ' . tablename('superdesk_shop_category') .
                ' WHERE id = \'' . $id . '\' limit 1'
            );

            $parentid = $item['parentid'];
        } else {
            $item = array('displayorder' => 0);
        }

        if (!empty($parentid)) {
            $parent = pdo_fetch(
                'SELECT id, parentid, name FROM ' . tablename('superdesk_shop_category') .
                ' WHERE id = \'' . $parentid . '\' limit 1'
            );

            if (empty($parent)) {
                $this->message('抱歉，上级分类不存在或是已经被删除！', webUrl('category/add'), 'error');
            }


            if (!empty($parent['parentid'])) {
                $parent1 = pdo_fetch(
                    'SELECT id, name FROM ' . tablename('superdesk_shop_category') .
                    ' WHERE id = \'' . $parent['parentid'] . '\' limit 1'
                );
            }

        }


        if (empty($parent)) {
            $level = 1;
        } else if (empty($parent['parentid'])) {
            $level = 2;
        } else {
            $level = 3;
        }

        if (!empty($item)) {
            $item['url'] = mobileUrl('goods', array('cate' => $item['id']), 1);
        }


        if ($_W['ispost']) {
            $data = array(
                'uniacid'      => $_W['uniacid'],
                'name'         => trim($_GPC['catename']),
                'enabled'      => intval($_GPC['enabled']),
                'displayorder' => intval($_GPC['displayorder']),
                'isrecommand'  => intval($_GPC['isrecommand']),
                'ishome'       => intval($_GPC['ishome']),
                'description'  => $_GPC['description'],
                'parentid'     => intval($parentid),
                'thumb'        => save_media($_GPC['thumb']),
                'advimg'       => save_media($_GPC['advimg']),
                'advurl'       => trim($_GPC['advurl']),
                'level'        => $level
            );

            if (!empty($id)) {

                unset($data['parentid']);

                pdo_update('superdesk_shop_category', $data, array('id' => $id));

                load()->func('file');

                file_delete($_GPC['thumb_old']);

                plog('shop.category.edit', '修改分类 ID: ' . $id);

            } else {
                $data['cateType'] = 2;

                pdo_insert('superdesk_shop_category', $data);

                $id = pdo_insertid();

                plog('shop.category.add', '添加分类 ID: ' . $id);
            }

            m('shop')->getCategory(true);
            m('shop')->getAllCategory(true);
            m('shop')->getRefreshFloorCategory();
            show_json(1, array('url' => webUrl('goods/category')));
        }


        include $this->template();
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $item = pdo_fetch(
            'SELECT id, name, parentid FROM ' . tablename('superdesk_shop_category') .
            ' WHERE id = \'' . $id . '\'');

        if (empty($item)) {
            $this->message('抱歉，分类不存在或是已经被删除！', webUrl('goods/category', array('op' => 'display')), 'error');
        }


        pdo_delete('superdesk_shop_category', array('id' => $id, 'parentid' => $id), 'OR');

        plog('shop.category.delete', '删除分类 ID: ' . $id . ' 分类名称: ' . $item['name']);

        m('shop')->getCategory(true);
        m('shop')->getRefreshFloorCategory();

        show_json(1, array('url' => referer()));
    }

    public function enabled()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }


        $items = pdo_fetchall(
            'SELECT id,name FROM ' . tablename('superdesk_shop_category') .
            ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']
        );

        foreach ($items as $item) {

            pdo_update(
                'superdesk_shop_category',
                array(
                    'enabled' => intval($_GPC['enabled']
                    )
                ),
                array(
                    'id' => $item['id']
                )
            );

            plog('shop.dispatch.edit', (('修改分类状态<br/>ID: ' . $item['id'] . '<br/>分类名称: ' . $item['name'] . '<br/>状态: ' . $_GPC['enabled']) == 1 ? '显示' : '隐藏'));
        }

        m('shop')->getCategory(true);
        m('shop')->getRefreshFloorCategory();

        show_json(1, array('url' => referer()));
    }

    public function query()
    {
        global $_W;
        global $_GPC;
        $kwd                = trim($_GPC['keyword']);
        $params             = array();
        $params[':uniacid'] = $_W['uniacid'];
        $condition          = ' and enabled=1 and uniacid=:uniacid';

        if (!empty($kwd)) {
            $condition          .= ' AND `name` LIKE :keyword';
            $params[':keyword'] = '%' . $kwd . '%';
        }


        $ds = pdo_fetchall('SELECT * FROM ' . tablename('superdesk_shop_category') . ' WHERE 1 ' . $condition . ' order by displayorder desc,id desc', $params);
        $ds = set_medias($ds, array('thumb', 'advimg'));

        if ($_GPC['suggest']) {
            exit(json_encode(array('value' => $ds)));
        }


        include $this->template();
    }

    public function relation()
    {
        global $_W,$_GPC;

        if($_W['isajax']){
//            selfLevel: selfLevel,
//            otherLevel: otherLevel,
//            selfFirstCateId: selfFirstCateId,
//            selfSecondCateId: selfSecondCateId,
//            selfThirdCateId: selfThirdCateId,
//            otherFirstCateId: otherFirstCateId,
//            otherSecondCateId: otherSecondCateId,
//            otherThirdCateId: otherThirdCateId

            $time = time();

            $otherLevel  = $_GPC['otherLevel'];
            $selfLevel  = $_GPC['selfLevel'];

            $selectOtherCate = $_GPC['selectOtherCateId'];
            $selectSelfCate = $_GPC['selectSelfCateId'];
            $selectColumnName = '';
            $inputColumnName = '';

            if($otherLevel == 1){
                $selectColumnName = 'pcate';
            }elseif($otherLevel == 2){
                $selectColumnName = 'ccate';
            }elseif($otherLevel == 3){
                $selectColumnName = 'tcate';
            }

            if($selfLevel == 1){
                $inputColumnName = 'pcates';
            }elseif($selfLevel == 2){
                $inputColumnName = 'ccates';
            }elseif($selfLevel == 3){
                $inputColumnName = 'tcates';
            }

            //查找关联表.看是否存在
            $category_relation = pdo_fetch(
                ' SELECT * FROM ' . tablename('superdesk_shop_category_relation') .
                ' WHERE other_category=:other_category ',
                array(
                    ':other_category' => $selectOtherCate
                )
            );

            $oldSelfCategory = 0;
            if(empty($category_relation)){
                //添加到关联表
                pdo_insert('superdesk_shop_category_relation',array(
                    'self_category'  => $selectSelfCate,
                    'other_category' => $selectOtherCate,
                    'createtime'      => $time
                ));
            }else{
                $oldSelfCategory = $category_relation['self_category'];
                pdo_update('superdesk_shop_category_relation',
                    array(
                        'self_category' => $selectSelfCate
                    ),
                    array(
                        'id' => $category_relation['id']
                    )
                );
            }

            //获取有对应分类的商品
            $goodsList = pdo_fetchall(
                ' SELECT id,cates,' . $inputColumnName . ' FROM ' . tablename('superdesk_shop_goods') .
                ' WHERE ' . $selectColumnName . '=:selectOtherCate ',
                array(
//                    ':selectColumnName' => $selectColumnName,
                    ':selectOtherCate' => $selectOtherCate
                )
            );

            //遍历更新
            foreach($goodsList as $k => $v){

                //商品当前的对应级分类集合
                $oldCate = $v[$inputColumnName];
                $oldCateArray = explode(',',$oldCate);

                //商品当前的总分类集合
                $oldCates = $v['cates'];
                $oldCatesArray = explode(',',$oldCates);

                //是否存在旧的关联关系,存在就删除旧的
                if(!empty($oldSelfCategory)){

                    $cateExistKey = array_search($oldSelfCategory,$oldCateArray);
                    if ($cateExistKey !== false){
                        array_splice($oldCateArray, $cateExistKey, 1);
                    }

                    $catesExistKey = array_search($oldSelfCategory,$oldCatesArray);
                    if ($catesExistKey !== false){
                        array_splice($oldCatesArray, $catesExistKey, 1);
                    }

                }

                //修改商品中对应的那一级分类集合
                $oldCateArray[] = $selectSelfCate;
                $newCate = implode(',',array_filter($oldCateArray));

                //修改商品的总分类集合
                $oldCatesArray[] = $selectSelfCate;
                $newCates = implode(',',array_filter($oldCatesArray));

                pdo_update('superdesk_shop_goods',
                    array(
                        $inputColumnName => $newCate,
                        'cates' => $newCates
                    ),
                    array(
                        'id' => $v['id']
                    )
                );
            }

            show_json(1,$goodsList);
        }

        $children = array();
        $category = pdo_fetchall(
            'SELECT * FROM ' . tablename('superdesk_shop_category') .
            ' WHERE uniacid = \'' . $_W['uniacid'] . '\' ORDER BY parentid ASC, displayorder DESC');

        $category_relations = pdo_fetchall(
            ' SELECT * FROM ' . tablename('superdesk_shop_category_relation')
        );

//        var_dump($category);die;

        $category_relation_other = array_column($category_relations,NULL,'other_category');

//        print_r($category_relation_other);die;

        $categoryIdKey = array_column($category,NULL,'id');

        foreach ($category as $index => &$row) {
            if(isset($category_relation_other[$row['id']])){
                $row['relation'] = $category_relation_other[$row['id']]['self_category'];
            }

            if (!empty($row['parentid'])) {
                $children[$row['parentid']][] = $row;
                unset($category[$index]);
            }

        }
        unset($row);

//        var_dump($category);die;

        include $this->template();
    }

    public function yanxuan_relation()
    {
        global $_W,$_GPC;

        if($_W['isajax']){
//            selfLevel: selfLevel,
//            otherLevel: otherLevel,
//            selfFirstCateId: selfFirstCateId,
//            selfSecondCateId: selfSecondCateId,
//            selfThirdCateId: selfThirdCateId,
//            otherFirstCateId: otherFirstCateId,
//            otherSecondCateId: otherSecondCateId,
//            otherThirdCateId: otherThirdCateId

            $time = time();

            $selfLevel  = $_GPC['selfLevel'];
            $otherLevel  = $_GPC['otherLevel'];

            $selectOtherCate = $_GPC['selectOtherCateId'];
            $selectSelfCate = $_GPC['selectSelfCateId'];
            $selectColumnName = '';
            $inputColumnName = '';

            if($otherLevel == 1){
                $selectColumnName = 'pcate';
            }elseif($otherLevel == 2){
                $selectColumnName = 'ccate';
            }elseif($otherLevel == 3){
                $selectColumnName = 'tcate';
            }

            if($selfLevel == 1){
                $inputColumnName = 'pcates';
            }elseif($selfLevel == 2){
                $inputColumnName = 'ccates';
            }elseif($selfLevel == 3){
                $inputColumnName = 'tcates';
            }

            //查找关联表.看是否存在
            $category_relation = pdo_fetch(
                ' SELECT * FROM ' . tablename('superdesk_shop_category_relation') .
                ' WHERE other_category=:other_category ',
                array(
                    ':other_category' => $selectOtherCate
                )
            );

            $oldSelfCategory = 0;
            if(empty($category_relation)){
                //添加到关联表
                pdo_insert('superdesk_shop_category_relation',array(
                    'self_category'  => $selectSelfCate,
                    'other_category' => $selectOtherCate,
                    'createtime'      => $time
                ));
            }else{
                $oldSelfCategory = $category_relation['self_category'];
                pdo_update('superdesk_shop_category_relation',
                    array(
                        'self_category' => $selectSelfCate
                    ),
                    array(
                        'id' => $category_relation['id']
                    )
                );
            }

            //获取有对应分类的商品
            $goodsList = pdo_fetchall(
                ' SELECT id,cates,' . $inputColumnName . ' FROM ' . tablename('superdesk_shop_goods') .
                ' WHERE ' . $selectColumnName . '=:selectOtherCate ',
                array(
//                    ':selectColumnName' => $selectColumnName,
                    ':selectOtherCate' => $selectOtherCate
                )
            );

            //遍历更新
            foreach($goodsList as $k => $v){

                //商品当前的对应级分类集合
                $oldCate = $v[$inputColumnName];
                $oldCateArray = explode(',',$oldCate);

                //商品当前的总分类集合
                $oldCates = $v['cates'];
                $oldCatesArray = explode(',',$oldCates);

                //是否存在旧的关联关系,存在就删除旧的
                if(!empty($oldSelfCategory)){

                    $cateExistKey = array_search($oldSelfCategory,$oldCateArray);
                    if ($cateExistKey !== false){
                        array_splice($oldCateArray, $cateExistKey, 1);
                    }

                    $catesExistKey = array_search($oldSelfCategory,$oldCatesArray);
                    if ($catesExistKey !== false){
                        array_splice($oldCatesArray, $catesExistKey, 1);
                    }

                }

                //修改商品中对应的那一级分类集合
                $oldCateArray[] = $selectSelfCate;
                $newCate = implode(',',array_filter($oldCateArray));

                //修改商品的总分类集合
                $oldCatesArray[] = $selectSelfCate;
                $newCates = implode(',',array_filter($oldCatesArray));

                pdo_update('superdesk_shop_goods',
                    array(
                        $inputColumnName => $newCate,
                        'cates' => $newCates
                    ),
                    array(
                        'id' => $v['id']
                    )
                );
            }

            show_json(1,$goodsList);
        }

        $children = array();
        $category = pdo_fetchall(
            'SELECT * FROM ' . tablename('superdesk_shop_category') .
            ' WHERE uniacid = \'' . $_W['uniacid'] . '\' ORDER BY parentid ASC, displayorder DESC');

        $category_relations = pdo_fetchall(
            ' SELECT * FROM ' . tablename('superdesk_shop_category_relation')
        );

//        var_dump($category);die;

        $category_relation_other = array_column($category_relations,NULL,'other_category');

//        print_r($category_relation_other);die;

        $categoryIdKey = array_column($category,NULL,'id');

        foreach ($category as $index => &$row) {
            if(isset($category_relation_other[$row['id']])){
                $row['relation'] = $category_relation_other[$row['id']]['self_category'];
            }

            if (!empty($row['parentid'])) {
                $children[$row['parentid']][] = $row;
                unset($category[$index]);
            }

        }
        unset($row);

//        var_dump($category);die;

        include $this->template();
    }

    public function delRelation(){
        global $_GPC;

        $otherLevel  = $_GPC['otherLevel'];

        $selectOtherCate = $_GPC['selectOtherCateId'];
        $selectSelfCate = $_GPC['selectSelfCateId'];
        $selectColumnName = '';
        $inputColumnName = '';

        if($otherLevel == 1){
            $selectColumnName = 'pcate';
        }elseif($otherLevel == 2){
            $selectColumnName = 'ccate';
        }elseif($otherLevel == 3){
            $selectColumnName = 'tcate';
        }

        $self_category = pdo_fetch(
            ' SELECT * FROM ' . tablename('superdesk_shop_category') .
            ' WHERE id=:id ',
            array(
                ':id' => $selectSelfCate
            )
        );

        if($self_category['level'] == 1){
            $inputColumnName = 'pcates';
        }elseif($self_category['level'] == 2){
            $inputColumnName = 'ccates';
        }elseif($self_category['level'] == 3){
            $inputColumnName = 'tcates';
        }

        //查找关联表.看是否存在
        $category_relation = pdo_fetch(
            ' SELECT * FROM ' . tablename('superdesk_shop_category_relation') .
            ' WHERE other_category=:other_category AND self_category=:self_category ',
            array(
                ':other_category' => $selectOtherCate,
                ':self_category'  => $selectSelfCate
            )
        );

        $oldSelfCategory = 0;
        if(empty($category_relation)){
            show_json(0,'无已有关联');
        }

        //获取有对应分类的商品
        $goodsList = pdo_fetchall(
            ' SELECT id,cates,' . $inputColumnName . ' FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE ' . $selectColumnName . '=:selectOtherCate ',
            array(
//                    ':selectColumnName' => $selectColumnName,
                ':selectOtherCate' => $selectOtherCate
            )
        );

        //遍历更新
        foreach($goodsList as $k => $v){

            //商品当前的对应级分类集合
            $oldCate = $v[$inputColumnName];
            $oldCateArray = explode(',',$oldCate);

            //商品当前的总分类集合
            $oldCates = $v['cates'];
            $oldCatesArray = explode(',',$oldCates);

            $needUpdate = 0;

            //是否存在需要删除的关联关系,存在就删除
            $cateExistKey = array_search($selectSelfCate,$oldCateArray);
            if ($cateExistKey !== false){
                array_splice($oldCateArray, $cateExistKey, 1);
                $needUpdate = 1;
            }

            $catesExistKey = array_search($selectSelfCate,$oldCatesArray);
            if ($catesExistKey !== false){
                array_splice($oldCatesArray, $catesExistKey, 1);
                $needUpdate = 1;
            }

            if(!$needUpdate){
                continue;
            }

            //修改商品中对应的那一级分类集合
            $newCate = implode(',',array_filter($oldCateArray));

            //修改商品的总分类集合
            $newCates = implode(',',array_filter($oldCatesArray));

            pdo_update('superdesk_shop_goods',
                array(
                    $inputColumnName => $newCate,
                    'cates' => $newCates
                ),
                array(
                    'id' => $v['id']
                )
            );
        }

        //删除关联表
        pdo_delete('superdesk_shop_category_relation',array(
            'self_category'  => $selectSelfCate,
            'other_category' => $selectOtherCate
        ));

        show_json(1,$goodsList);
    }

    public function goods_status(){
        global $_GPC;

        $id = $_GPC['id'];

        if(empty($id)){
            show_json(0,'id不能为空');
        }

        $item = pdo_fetch(
            'SELECT id, level FROM ' . tablename('superdesk_shop_category') .
            ' WHERE id = :id',
            array(':id' => $id)
        );

        if(empty($item)){
            show_json(0,'分类不存在');
        }

        $column = $item['level'] == 1 ? 'pcate' : ($item['level'] == 2 ? 'ccate' : 'tcate');

        pdo_update('superdesk_shop_goods',
            array(
                'deleted' => 1,
                'updatetime' => time()
            ),
            array(
                $column => $id
            )
        );

        show_json(1);
    }
}


