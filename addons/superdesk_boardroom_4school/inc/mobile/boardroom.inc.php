<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/27/17
 * Time: 5:11 AM
 * 
 * 192.168.1.124/superdesk/app/index.php?i=15&c=entry&do=boardroom&m=superdesk_boardroom_4school
 */


global $_GPC, $_W;

if(empty($_W['fans']['from_user'])){
    include $this->template('error/error');
    exit(0);
}

$userMobile = $_GPC['userMobile'];
if(empty($userMobile)){
//    die("请在中航超级前台公众号中打开");
}

$superdesk_user_info = $this->superdesk_core_user_mobile($userMobile);

//var_dump($superdesk_user_info);


$title = "会议室预定";
$now = strtotime("+30 minutes", time());

$init_timestamp_start = strtotime("+1 hours", time());

$Ymd_start = date('Y/m/d', $init_timestamp_start);
$Ymd_end = date('Y/m/d', strtotime("+1 week" , time()));// TODO "+1 week" 修改为后台设置

// 用于datetime picker 限制一天内的时间
$datetimepicker_start = date('Y-m-d 00:00:00', $init_timestamp_start);
$datetimepicker_end = date('Y-m-d 23:59:59', $init_timestamp_start);


//echo date('Y-m-d H:i:s', time());

$curr = time();

$timestamp_start = strtotime("+".(60- intval(date('i', $curr)))." minutes", $curr);
$timestamp_end = strtotime("+30 minutes", $timestamp_start);

$date_start = date('Y-m-d', $timestamp_start);
$span_date_start = date('m-d', $timestamp_start);
$date_end = date('Y-m-d', $timestamp_end);

$time_start = date('H:i', $timestamp_start);
$time_end = date('H:i', $timestamp_end);

$structures_parentid    = isset($_GPC['structures_parentid'])?$_GPC['structures_parentid']:0;
$structures_childid     = isset($_GPC['structures_childid'])?$_GPC['structures_childid']:0;
$attribute              = isset($_GPC['attribute'])?$_GPC['attribute']:0;

$url_boardroom_async_list = $this->createMobileUrl('boardroom_async_list');
$url_ajax_boardroom_get_floor = $this->createMobileUrl('ajax_boardroom_get_floor');


/************ 初始数据 start ************/


/************ attribute start ************/
include_once(MODULE_ROOT . '/model/boardroom_4school_building_attribute.class.php');
$attributeModel = new boardroom_4school_building_attributeModel();


$attribute_where = array();
$attribute_page = intval($_GPC['page'],1);
$attribute_page_size = 100;

if($superdesk_user_info){
    $attribute_where['organization_code']   = $superdesk_user_info['organizationCode'];
    $attribute_where['virtual_code']        = $superdesk_user_info['virtualCode'];
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

if($superdesk_user_info){
    $structures_where['organization_code']  = $superdesk_user_info['organizationCode'];
    $structures_where['virtual_code']       = $superdesk_user_info['virtualCode'];
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


/************ 初始数据 end   ************/


/************ 通知数据 start   ************/
$pindex = max(1, intval($_GPC['page']));
$psize = 1;
$sql =
    " select * ".
    " from " . tablename("superdesk_boardroom_4school_announcement") .
    " where uniacid='{$_W['uniacid']}' order by createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize;

$announcement = pdo_fetchall($sql);

//var_dump($announcement);
/************ 通知数据 end   ************/



include_once(MODULE_ROOT . '/model/boardroom_equipment.class.php');
$boardroom_equipment = new boardroom_equipmentModel();

$page = 1;
$page_size = 100;
$where = array(
    "enabled" => 1
);
$equipment_result = $boardroom_equipment->queryByApi4AjaxTypehead($where,$page,$page_size);
$equipment_total        = $equipment_result['total'];
$equipment_page         = $equipment_result['page'];
$equipment_page_size    = $equipment_result['page_size'];
$equipment_list         = $equipment_result['data'];

include $this->template('boardroom');