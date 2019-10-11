<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=ms_integral_send */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/ms_integral_send.class.php');
$ms_integral_send = new ms_integral_sendModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $ms_integral_send->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'integral_send_id' => $_GPC['integral_send_id'],// 
    'integral_send_name' => $_GPC['integral_send_name'],// 发送规则名称
    'integral_standard_pkcode' => $_GPC['integral_standard_pkcode'],// 
    'integral_send_timetype' => $_GPC['integral_send_timetype'],// 发放时间规则类型，0：每天发放，1：每月发放，2：每年指定时间发放 3自定义
    'integral_send_diytime' => $_GPC['integral_send_diytime'],// 指定发放时间
    'integral_send_number' => $_GPC['integral_send_number'],// 发送的数量
    'integral_send_ctime' => $_GPC['integral_send_ctime'],// 创建时间
    'integral_send_state' => $_GPC['integral_send_state'],// 0:无效，1：有效
    'e_id' => $_GPC['e_id'],// 公司ID
    'integral_send_companyid' => $_GPC['integral_send_companyid'],// 公司标签的id
    'integral_send_branch' => $_GPC['integral_send_branch'],// 分公司标签的id
    'integral_send_department' => $_GPC['integral_send_department'],// 部门标签的id
    'integral_send_group' => $_GPC['integral_send_group'],// 小组标签的id
    'integral_send_position' => $_GPC['integral_send_position'],// 职位标签的id
    'integral_send_welfare' => $_GPC['integral_send_welfare'],// 福利等级标签的id
    'integral_send_endtime' => $_GPC['integral_send_endtime'],// 

        );
        $ms_integral_send->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('ms_integral_send', array('op' => 'list')), 'success');


    }
    include $this->template('ms_integral_send_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $ms_integral_send->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('ms_integral_send', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $ms_integral_send->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('ms_integral_send_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $ms_integral_send->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $ms_integral_send->delete($id);

    message('删除成功！', referer(), 'success');
}

