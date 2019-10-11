<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=zc_import_member */

global $_GPC, $_W;
$active='zc_import_member';

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_import_member.class.php');
$zc_import_member = new zc_import_memberModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $zc_import_member->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'm_id'                => $_GPC['m_id'],//
            'm_name'              => $_GPC['m_name'],// 会员名称
            'm_mobile'            => $_GPC['m_mobile'],// 手机号码
            'm_id_card'           => $_GPC['m_id_card'],// 身份证
            'm_status'            => $_GPC['m_status'],// 会员状态0：黑名单，1：正常 2:未注册
            'm_registration_time' => $_GPC['m_registration_time'],// 注册时间
            'm_account'           => $_GPC['m_account'],// 会员帐号
            'm_poll_code'         => $_GPC['m_poll_code'],// 帐号注册码
            'm_account_pwd'       => $_GPC['m_account_pwd'],// 帐号密码
            'm_fansId'            => $_GPC['m_fansId'],// 对应粉丝表粉丝的id
            'm_e_id'              => $_GPC['m_e_id'],// 所属企业id
            'm_company'           => $_GPC['m_company'],// 所属企业名称
            'm_branch_company'    => $_GPC['m_branch_company'],// 所属分公司
            'm_department'        => $_GPC['m_department'],// 所属部门
            'm_team'              => $_GPC['m_team'],// 所属小组
            'm_position'          => $_GPC['m_position'],// 所属职务
            'm_welfare_level'     => $_GPC['m_welfare_level'],// 所属福利等级
            'm_import_time'       => $_GPC['m_import_time'],// 导入时间
            'm_card_num'          => $_GPC['m_card_num'],// 福利卡号
            'm_job_num'           => $_GPC['m_job_num'],// 工号
            'm_e_code'            => $_GPC['m_e_code'],// 企业编号

        );
        $zc_import_member->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('zc_import_member', array('op' => 'list')), 'success');


    }
    include $this->template('zc_import_member_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where  = array('id' => $id);

            $zc_import_member->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('zc_import_member', array('op' => 'list')), 'success');
    }

    $page      = $_GPC['page'];
    $page_size = 20;

    $result    = $zc_import_member->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('zc_import_member_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $zc_import_member->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $zc_import_member->delete($id);

    message('删除成功！', referer(), 'success');
}

