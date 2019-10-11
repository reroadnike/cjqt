<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_boardroom&do=boardroom */

global $_GPC, $_W;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

load()->func('tpl');

$core_user = $this->superdesk_core_user();
//var_dump($core_user);

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

include_once(MODULE_ROOT . '/model/boardroom_4school_building_structures.class.php');
include_once(MODULE_ROOT . '/model/boardroom_4school_building_attribute.class.php');

$attributeModel = new boardroom_4school_building_attributeModel();
$structuresModel = new boardroom_4school_building_structuresModel();

$attributesResult = $attributeModel->queryAll($where = array() , 1, 999);
$attributes = $attributesResult['data'];


$sql = 'SELECT * FROM ' . tablename('superdesk_boardroom_4school_building_structures') . ' WHERE `uniacid` = :uniacid ORDER BY `parentid`, `displayorder` DESC';
$category = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']), 'id');
if (!empty($category)) {
    $parent = $children = array();
    foreach ($category as $cid => $cate) {
        if (!empty($cate['parentid'])) {
            $children[$cate['parentid']][] = $cate;
        } else {
            $parent[$cate['id']] = $cate;
        }
    }
}

if ($op == 'edit') {

    $item = $boardroom->getOne($_GPC['id']);

    if($core_user){

    } else{
        /************ 初始化项目 ***********/
        include_once(MODULE_ROOT . '/../superdesk_core/model/organization.class.php');
        $organizationModel = new organizationModel();
        $result_organization = $organizationModel->querySelector(array(),1,999);
        $selector_organization = $result_organization['data'];

        include_once(MODULE_ROOT . '/../superdesk_core/model/virtualarchitecture.class.php');
        $virtualarchitectureModel = new virtualarchitectureModel();
        $result_virtuals = $virtualarchitectureModel->queryForUsersAjax(array("codeNumber" => $item['virtual_code']), 1, 999);
        $selector_virtuals = $result_virtuals['data'];
        /************ 初始化项目 ***********/
    }



    if (checksubmit('submit')) {

        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'name' => $_GPC['name'],
            'address' => $_GPC['address'],
            "organization_code" => $_GPC['organization_code'],
            "virtual_code" => $_GPC['virtual_code'],
//            'floor' => $_GPC['floor'],
//            structures[parentid]
//            structures[childid]
//            attribute
            "structures_parentid" => $_GPC['structures']['parentid'],
            "structures_childid" => $_GPC['structures']['childid'],
            "attribute" => $_GPC['attribute'],
            
            'traffic' => $_GPC['traffic'],
//            'lat' => $_GPC['lat'],
//            'lng' => $_GPC['lng'],
            'lng' => $_GPC['baidumap']['lng'],
            'lat' => $_GPC['baidumap']['lat'],

            'thumb' => $_GPC['thumb'],
            'basic' => $_GPC['basic'],
//            'carousel' => $_GPC['carousel'],
            'price' => $_GPC['price'],
//            'equipment' => $_GPC['equipment'],

            'desk' => $_GPC['desk'],
            'chair' => $_GPC['chair'],

            'max_num' => $_GPC['max_num'],

            'appointment_num' => $_GPC['appointment_num'],
            'remark' => $_GPC['remark'],
            'cancle_rule' => $_GPC['cancle_rule'],

        );

        if($core_user){
            $params['organization_code']= $core_user['organization_code'];
            $params['virtual_code']     = $core_user['virtual_code'];
        } else{
            $data['organization_code']  = $_GPC['organization_code'];
            $data['virtual_code']       = $_GPC['virtual_code'];
        }

        $boardroom->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('boardroom_manage', array('op' => 'list')), 'success');


    }
    include $this->template('boardroom_manage_edit');

} elseif ($op == 'list') {



    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);
            $boardroom->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('boardroom_manage', array('op' => 'list')), 'success');
    }

    $core_user = $this->superdesk_core_user();


    /************ attribute start ************/
    include_once(MODULE_ROOT . '/model/boardroom_4school_building_attribute.class.php');
    $attributeModel = new boardroom_4school_building_attributeModel();


    $attribute_where = array();
    $attribute_page = intval($_GPC['page'],1);
    $attribute_page_size = 100;

    if($core_user){
        $attribute_where['organization_code'] = $core_user['organization_code'];
        $attribute_where['virtual_code']= $core_user['virtual_code'];
    }

    $attributesResult = $attributeModel->queryAllByCoreUser($attribute_where,$attribute_page,$attribute_page_size);
    $attributes = $attributesResult['data'];
    /************ attribute   end ************/

    /************ structures start ************/
    include_once(MODULE_ROOT . '/model/boardroom_4school_building_structures.class.php');
    $structuresModel = new boardroom_4school_building_structuresModel();

    $structures_where = array();
    $structures_page = intval($_GPC['page'],1);
    $structures_page_size = 100;

    if($core_user){
        $structures_where['organization_code'] = $core_user['organization_code'];
        $structures_where['virtual_code']= $core_user['virtual_code'];
    }

    $structuresResult = $structuresModel->queryAllByCoreUser($attribute_where,$attribute_page,$attribute_page_size);
    $category = $structuresResult['data'];
    $parent = $children = array();
    if (!empty($category)) {
        foreach ($category as $cid => $cate) {
            if (!empty($cate['parentid'])) {
                $children[$cate['parentid']][] = $cate;
            } else {
                $parent[$cate['id']] = $cate;
            }
        }
    }
    /************ structures   end ************/

    $structures_parentid    = isset($_GPC['structures']['parentid'])?$_GPC['structures']['parentid'] : $parent[key($parent)]['id'];
    $structures_childid     = isset($_GPC['structures']['childid'])? $_GPC['structures']['childid']  : $children[$structures_parentid][0]['id'];
    $attribute              = isset($_GPC['attribute'])?$_GPC['attribute']:$attributes[key($attributes)]['id'];

    $_where = array(
        "structures_parentid"   => $structures_parentid,
        "structures_childid"    => $structures_childid,
        "attribute"             => $attribute,
    );

//    var_dump($_where);

    $page = $_GPC['page'];
    $page_size = 10;

    if($core_user){
        $_where['organization_code']    = $core_user['organization_code'];
        $_where['virtual_code']         = $core_user['virtual_code'];
    }

    $result = $boardroom->queryAllByCoreUser($_where, $page, $page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    foreach ($list as $index => &$_item){
        $json_str = "{\"items\":[".iunserializer($_item['equipment'])."]}";
        $json = json_decode(htmlspecialchars_decode($json_str), true);
        $_item['equipment'] = $json['items'];
//        $_item['thumb'] = tomedia($_item['thumb']);
//        var_dump($json);
//        echo "<br/>";
    }
    unset($_item);

//    $pager = pagination($total, $page, $page_size);
    $pager = pagination($total, $page, $page_size, '',
        array(
            'before' => 5,
            'after' => 4,
            'ajaxcallback' => 'select_page',
            'isajax' => true
        ));

    include $this->template('boardroom_manage_list');

} elseif ($op == 'ajax') { // 编辑加载企业

    // http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&op=ajax&do=users&m=superdesk_core&organization_code=TJ-TJSWDX

    $organization_code = $_GPC['organization_code'];

    include_once(MODULE_ROOT . '/../superdesk_core/model/organization.class.php');
    $_organizationModel = new organizationModel();

    include_once(MODULE_ROOT . '/../superdesk_core/model/virtualarchitecture.class.php');
    $_virtualarchitectureModel = new virtualarchitectureModel();

    $_organization = $_organizationModel->getOneByColumn(array("code" => $organization_code));

//    var_dump($_organization);


    if ($organization_code) {
        $_result = $_virtualarchitectureModel->queryForUsersAjax(array("organizationId" => $_organization['id']), 1, 999);
        $virtuals = $_result['data'];
        print_r(json_encode($virtuals));
        exit();
    }

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $boardroom->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $boardroom->delete($id);

    message('删除成功！', referer(), 'success');

} elseif ($op == 'set_property') {

    $id = intval($_GPC['id']);
    $type = $_GPC['type'];
    $data = intval($_GPC['data']);

    if (in_array($type, array('enabled'))) {
        $data = ($data == 1 ? '0' : '1');

        $params = array($type => $data);
        $boardroom->update($params,$id);
        die(json_encode(array("result" => 1, "data" => $data)));
    }

    die(json_encode(array("result" => 0)));
}


