<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/30 * Time: 16:38 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_core&do=tb_user */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/tb_user.class.php');
$tb_user = new tb_userModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $tb_user->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'userName'       => $_GPC['userName'],// 姓名
            'nickName'       => $_GPC['nickName'],// 昵称
            'userMobile'     => $_GPC['userMobile'],// 手机号码
            'userType'       => $_GPC['userType'],// 用户类型
            'userSex'        => $_GPC['userSex'],// 性别
            'userCardNo'     => $_GPC['userCardNo'],// 学生号/身份证
            'birthday'       => $_GPC['birthday'],// 生日
            'userPhotoUrl'   => $_GPC['userPhotoUrl'],// 头像
            'password'       => $_GPC['password'],// 密码
            'status'         => $_GPC['status'],// 认证状态
            'suggestion'     => $_GPC['suggestion'],// 审核建议
            'address'        => $_GPC['address'],// 详细地址
            'imageUrl01'     => $_GPC['imageUrl01'],// 证件照片1
            'imageUrl02'     => $_GPC['imageUrl02'],// 证件照片2
            'imageUrl03'     => $_GPC['imageUrl03'],//
            'organizationId' => $_GPC['organizationId'],// 用户所属组织
            'virtualArchId'  => $_GPC['virtualArchId'],// 学院/系部ID
            'userNumber'     => $_GPC['userNumber'],// 员工编号
            'enteringTime'   => $_GPC['enteringTime'],// 入司时间
            'positionName'   => $_GPC['positionName'],// 职位名称
            'departmentId'   => $_GPC['departmentId'],// 部门ID
            'facePlusUserId' => $_GPC['facePlusUserId'],// face++用户唯一标识
            'roleType'       => $_GPC['roleType'],// 企业用户角色（1-管理员，2-普通用户）
            'noticePower'    => $_GPC['noticePower'],// 接受审核通知（0-不接收用户申请通知，关，1-接收用户申请通知，开）
            'creator'        => $_GPC['creator'],// 创建者
            'createTime'     => $_GPC['createTime'],// 创建时间
            'modifier'       => $_GPC['modifier'],// 修改人
            'modifyTime'     => $_GPC['modifyTime'],// 修改时间
            'isEnabled'      => $_GPC['isEnabled'],// 是否可用
            'isSyncNeigou'   => $_GPC['isSyncNeigou'],// 是否同步内购网

        );
        $tb_user->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('tb_user', array('op' => 'list')), 'success');


    }
    include $this->template('tb_user_edit');

} elseif ($op == 'list') {


    $where = [];

    if (!empty($_GPC['keywords'])) {

        $where['keywords'] = $_GPC['keywords'];
    }

    $page      = $_GPC['page'];
    $page_size = 20;

    $result    = $tb_user->queryAll($where, $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('tb_user_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $tb_user->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $tb_user->delete($id);

    message('删除成功！', referer(), 'success');
}

