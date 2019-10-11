<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_boardroom&do=boardroom_appointment */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/boardroom_appointment.class.php');
$boardroom_appointment = new boardroom_appointmentModel();

$core_user = $this->superdesk_core_user();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

$paytype = array (
    '0' => array('css' => 'default', 'name' => '未支付'),
    '1' => array('css' => 'danger','name' => '余额支付'),
    '2' => array('css' => 'info', 'name' => '在线支付'),
    '3' => array('css' => 'warning', 'name' => '当面付款'),
    '4' => array('css' => 'info', 'name' => '无需支付')
);
$orderstatus = array (
    '-1' => array('css' => 'default', 'name' => '已取消'),
    '0' => array('css' => 'danger', 'name' => '待审核'),
    '1' => array('css' => 'info', 'name' => '待审核'),//待审核已收款
    '2' => array('css' => 'warning', 'name' => '已审核'),
    '3' => array('css' => 'success', 'name' => '已审核')
);

if ($op == 'edit') {

    $item = $boardroom_appointment->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'boardroom_id' => $_GPC['boardroom_id'],
            'openid' => $_GPC['openid'],
            'client_name' => $_GPC['client_name'],
            'client_telphone' => $_GPC['client_telphone'],
            'deleted' => $_GPC['deleted'],
            'state' => $_GPC['state'],
            'relate_id' => $_GPC['relate_id'],
            'people_num' => $_GPC['people_num'],

            'starttime' => $_GPC['starttime'],
            'endtime' => $_GPC['endtime'],

        );

        if($core_user){
            $params['organization_code'] = $core_user['organization_code'];
            $params['virtual_code']= $core_user['virtual_code'];
        }

        $boardroom_appointment->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('boardroom_appointment', array('op' => 'list')), 'success');


    }
    include $this->template('boardroom_appointment_edit');

} elseif ($op == 'list') {

    // 更新排序
//    if (!empty($_GPC['displayorder'])) {
//        foreach ($_GPC['displayorder'] as $id => $displayorder) {
//
//            $params = array('displayorder' => $displayorder);
//            $where = array('id' => $id);
//
//            $boardroom_appointment->update($params,$where);
//        }
//        message('显示顺序更新成功！', $this->createWebUrl('boardroom_appointment', array('op' => 'list')), 'success');
//    }
    $page = $_GPC['page'];
    $page_size = isset($_GPC['page_size'])?$_GPC['page_size']:10;
    $_where = array();

    $_where['subject']             = $_GPC['subject'];
    $_where['out_trade_no']        = $_GPC['out_trade_no'];
    $_where['client_name']         = $_GPC['client_name'];
    $_where['client_telphone']     = $_GPC['client_telphone'];
    $_where['start']               = $_GPC['date']['start'];
    $_where['end']                 = $_GPC['date']['end'];
    $_where['status']              = $_GPC['status'];
    $_where['attribute']           = $_GPC['attribute'];
    $_where['structures_parentid'] = $_GPC['structures']['parentid'];
    $_where['structures_childid']  = $_GPC['structures']['childid'];


    if ($_GPC['out_put'] == 'output') {

        $list = $boardroom_appointment->queryAllByCoreUserExport($_where);

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');
        require_once '../framework/library/phpexcel/PHPExcel.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("预约服务订单")
            ->setLastModifiedBy("预约服务订单")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A1', '项目码')
//            ->setCellValue('B1', '企业码')
            ->setCellValue('A1', '订单号')
            ->setCellValue('B1', '会议主题')
            ->setCellValue('C1', '预约人姓名')
            ->setCellValue('D1', '预约人电话')
            ->setCellValue('E1', '会议室')
            ->setCellValue('F1', '场地设备')
            ->setCellValue('G1', '地址')
            ->setCellValue('H1', '会议室人数')
            ->setCellValue('I1', '会议组织人')
            ->setCellValue('J1', '组织人电话')
            ->setCellValue('K1', '使用时间')
            ->setCellValue('L1', '状态')
            ->setCellValue('M1', '下单时间')
            ->setCellValue('N1', '会议级别')
            ->setCellValue('O1', '附加服务用品及数量');

        foreach ($list as $i => $v) {
            $i = $i + 2;
            $v['status'] = $orderstatus[$v['status']]['name'];
            $add_server = '';
            foreach($v['order_goods'] as $key => $value){
                $add_server .= $value['buy_total'] . '*' .$value['title'].'；';
            }

            //联系人
            $appoint_name = $appoint_mobile = '';
            if(!empty($v['openid'])){
                $_W['openid'] = $v['openid'];
                $superdesk_user_info = $this->superdesk_core_user_mobile();
                if(!empty($superdesk_user_info)){
                    $appoint_name = $superdesk_user_info['userName'];
                    $appoint_mobile = $superdesk_user_info['userMobile'];
                }
            }


            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $v['out_trade_no'])
                ->setCellValue('B' . $i, $v['subject'])
                ->setCellValue('C' . $i, $appoint_name)
                ->setCellValue('D' . $i, $appoint_mobile)
                ->setCellValue('E' . $i, $v['boardroom_name'])
                ->setCellValue('F' . $i, $v['boardroom_equipment'])
                ->setCellValue('G' . $i, $v['boardroom_address'])
                ->setCellValue('H' . $i, $v['people_num'])
                ->setCellValue('I' . $i, $v['client_name'])
                ->setCellValue('J' . $i, $v['client_telphone'])
                ->setCellValue('K' . $i, date('Y-m-d H:i' , $v['starttime']) .' - '. date('Y-m-d H:i' , $v['endtime']))
                ->setCellValue('L' . $i, $v['status'])
                ->setCellValue('M' . $i, date('Y-m-d H:i' , $v['createtime']))
                ->setCellValue('N' . $i, '')//TODO 会议级别 不知道是什么字段
                ->setCellValue('O' . $i, $add_server);

        }
        $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
        // Rename worksheet
        $time = time();
        $objPHPExcel->getActiveSheet()->setTitle('预约服务订单' . $time);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)]

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="预约服务订单_' . $_where['start'].' - ' .$_where['end'] . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        exit();
    }




    if($core_user){
        $_where['organization_code'] = $core_user['organization_code'];
        $_where['virtual_code']= $core_user['virtual_code'];
    }


//    var_dump($_where);exit;
    $result     = $boardroom_appointment->queryAllByCoreUser($_where,$page,$page_size);
    $total      = $result['total'];
    $page       = $result['page'];
    $page_size  = $result['page_size'];
    $list       = $result['data'];

    include_once(MODULE_ROOT . '/model/boardroom.class.php');
    $boardroom = new boardroomModel();

    foreach ($list as $key => &$value){
        $s = $value['status'];

        $value['status_css'] = $orderstatus[$value['status']]['css'];
        $value['status_name'] = $orderstatus[$value['status']]['name'];

//        if ($s < 1) {
//            $value['css'] = $paytype[$s]['css'];
//            $value['paytype'] = $paytype[$s]['name'];
//            continue;
//        }

        $value['css'] = $paytype[$value['paytype']]['css'];
        if ($value['paytype'] == 2) {
            if (empty($value['transaction_id'])) {
                $value['paytype'] = '支付宝支付';
            } else {
                $value['paytype'] = '微信支付';
            }
        } else {
            $value['paytype'] = $paytype[$value['paytype']]['name'];
        }

        $value["boardroom"]['name'] = $value['boardroom_name'];

    }


    $callbackfunc = 'dd';
    $_W['script_name'] = 'dd';
//    $pager = pagination($total, $page, $page_size);
    $pager = pagination($total, $page, $page_size, '',
        array(
            'before' => 5,
            'after' => 4,
            'ajaxcallback' => 'select_page',
            'isajax' => true
        ));

    $url_search =  $this->createWebUrl('boardroom_appointment');

    $url_export =  $this->createWebUrl('boardroom_appointment',array('op'=>'export'));


//    属性
    include_once(MODULE_ROOT . '/model/boardroom_4school_building_attribute.class.php');
    include_once(MODULE_ROOT . '/model/boardroom_4school_building_structures.class.php');
    $attributeModel = new boardroom_4school_building_attributeModel();
    $structuresModel = new boardroom_4school_building_structuresModel();

    if($core_user){
        $attribute_where['organization_code'] = $core_user['organization_code'];
        $attribute_where['virtual_code']= $core_user['virtual_code'];
    }

    $attributesResult = $attributeModel->queryAllByCoreUser([],1,1000);
    $attributes = $attributesResult['data'];

//    楼层楼宇
    $structuresResult = $structuresModel->queryAllByCoreUser([],1,1000);
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

    $structures_parentid    = isset($_GPC['structures']['parentid'])?$_GPC['structures']['parentid'] : $parent[key($parent)]['id'];
    $structures_childid     = isset($_GPC['structures']['childid'])? $_GPC['structures']['childid']  : $children[$structures_parentid][0]['id'];
    $attribute              = isset($_GPC['attribute'])?$_GPC['attribute']:$attributes[key($attributes)]['id'];

    include $this->template('boardroom_appointment_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $boardroom_appointment->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $boardroom_appointment->delete($id);

    message('删除成功！', referer(), 'success');

} elseif ($op == 'cancel') {

    /************ 4 test start ************/
//    $data = array();
//    $data['code'] = 200;
//    $data['msg'] = "取消预定成功！";
//    $data['data'] = 1;
//    die(json_encode($data));
    /************ 4 test end ************/

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $_boardroom_appointment = $boardroom_appointment->getOne($_GPC['id']);

    if (empty($_boardroom_appointment)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    // 数组对象

    $situation_select = json_decode($_boardroom_appointment['situation'],true);

    // 数据结构参考
//            var_dump($situation_select['stbs']);exit(0);
    foreach ($situation_select['stbs'] as $index_key => $_situation_val){

        $situation_target = $this->get_boardroom_situation($_boardroom_appointment['boardroom_id'],$_situation_val['lable']);

        //    {
        //        ["index"]=> int(0)
        //        ["key"]=> string(19) "2017-07-31 00:00:00"
        //        ["timestamp"]=> int(1501432200)
        //        ["is_use"]=> int(0)
        //        ["lable"]=> string(11) "00:00-00:30"
        //        ["checked"]=> int(0)
        //    }

        foreach($_situation_val['select_time_bar'] as $index => &$_situation){
            // 0-23
            if($_situation['index'] >= 0 && $_situation['index'] <= 23){
                $situation_target['am'][$_situation['index']]['is_use'] = 0;
            }
            // 24-47
            if($_situation['index'] >= 24 && $_situation['index'] <= 47){
                $situation_target['pm'][$_situation['index']-24]['is_use'] = 0;
            }
        }

        $this->set_boardroom_situation($_boardroom_appointment['boardroom_id'],$_situation_val['lable'],$situation_target);
    }

    $params = array("status" => -1);
    $boardroom_appointment->update($params,$id);

//    message('取消成功！', referer(), 'success');
    $data = array();
    $data['code'] = 200;
    $data['msg'] = "取消预定成功！";
    $data['data'] = 1;
    die(json_encode($data));

//referer()

}

elseif ($op == 'set_property') {

    $id = intval($_GPC['id']);
    $type = $_GPC['type'];
    $data = intval($_GPC['data']);


    if (in_array($type, array('status'))) {
        $data = ($data == 1 ? '3' : '1');

        $params = array($type => $data);
        $boardroom_appointment->update($params,$id);
        die(json_encode(array("result" => 1, "data" => $data)));
    }

    die(json_encode(array("result" => 0)));
}

elseif ( $op == 'export' ){

    $_where['start'] = $_GPC['date']['start'];
    $_where['end']   = $_GPC['date']['end'];

    $list = $boardroom_appointment->queryAllByCoreUserExport($_where);

    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    if (PHP_SAPI == 'cli')
        die('This example should only be run from a Web Browser');
    require_once '../framework/library/phpexcel/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("预约服务订单")
        ->setLastModifiedBy("预约服务订单")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '项目码')
        ->setCellValue('B1', '企业码')
        ->setCellValue('C1', '订单号')
        ->setCellValue('D1', '预约人姓名')
        ->setCellValue('E1', '联系电话')
        ->setCellValue('F1', '会议室(人数)')
        ->setCellValue('G1', '支付方式')
        ->setCellValue('H1', '使用时间')
        ->setCellValue('I1', '状态')
        ->setCellValue('J1', '下单时间')
        ->setCellValue('K1', '附加服务');

    foreach ($list as $i => $v) {
        $i = $i + 2;
        $v['paytype'] = $paytype[$v['paytype']]['name'];
        $v['status'] = $orderstatus[$v['status']]['name'];
//var_dump($v);exit;
        $add_server = '';
        foreach($v['order_goods'] as $key => $value){
            $add_server .= $value['buy_total'] . '*' .$value['title'].'；';
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, $v['organization_code'])
            ->setCellValue('B' . $i, $v['virtual_code'])
            ->setCellValue('C' . $i, $v['out_trade_no'])
            ->setCellValue('D' . $i, $v['client_name'])
            ->setCellValue('E' . $i, $v['client_telphone'])
            ->setCellValue('F' . $i, $v['people_num'])
            ->setCellValue('G' . $i, $v['paytype'])
            ->setCellValue('H' . $i, date('Y-m-d H:i' , $v['starttime']) .' - '. date('Y-m-d H:i' , $v['endtime']))
            ->setCellValue('I' . $i, $v['status'])
            ->setCellValue('J' . $i, date('Y-m-d H:i' , $v['createtime']))
            ->setCellValue('K' . $i, $add_server);
    }
    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
    // Rename worksheet
    $time = time();
    $objPHPExcel->getActiveSheet()->setTitle('预约服务订单' . $time);

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel2007)]

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="预约服务订单_' . $_where['start'].' - ' .$_where['end'] . '.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');

    exit;


}