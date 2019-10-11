<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_fans */

global $_GPC, $_W;
$active='wx_fans';

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_fans.class.php');
$wx_fans = new wx_fansModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_fans->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'fansId' => $_GPC['fansId'],// 活动用户ID
    'openId' => $_GPC['openId'],// 微信用户OPENID
    'nickName' => $_GPC['nickName'],// 用户昵称
    'headImgUrl' => $_GPC['headImgUrl'],// 用户头像
    'telePhone' => $_GPC['telePhone'],// 手机号码
    'password' => $_GPC['password'],// 登陆密码
    'wxProvince' => $_GPC['wxProvince'],// 
    'city' => $_GPC['city'],// 所在城市
    'sex' => $_GPC['sex'],// 1:男性，2：女性,0：未知
    'is_subscribed' => $_GPC['is_subscribed'],// 是否关注:0否1是
    'is_wsd_user' => $_GPC['is_wsd_user'],// 是否已登陆用户:0否1是
    'version' => $_GPC['version'],// 版本号
    'created_time' => $_GPC['created_time'],// 创建时间
    'modified_time' => $_GPC['modified_time'],// 修改时间
    'token' => $_GPC['token'],// 来源
    'marking_time' => $_GPC['marking_time'],// 打标时间
    'scene_str' => $_GPC['scene_str'],// 推荐码/水店ID
    'fansType' => $_GPC['fansType'],// 0:微信会员1:pc端会员2:导入
    'language' => $_GPC['language'],// 语言
    'levelid' => $_GPC['levelid'],// 角色,0:普通客户，1：经销商，2:其他
    'userName' => $_GPC['userName'],// 会员名称
    'account' => $_GPC['account'],// 帐号
    'pwd' => $_GPC['pwd'],// 密码
    'salerid' => $_GPC['salerid'],// 所属销售ID
    'logintype' => $_GPC['logintype'],// 登陆类型,0:手机，1：后台
    'stated' => $_GPC['stated'],// 1:正常 2: 黑名单
    'pointPwd' => $_GPC['pointPwd'],// 积分密码
    'userInfoAddress' => $_GPC['userInfoAddress'],// 用户基本信息地址
    'storeQRCode' => $_GPC['storeQRCode'],// 店铺二维码

        );
        $wx_fans->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_fans', array('op' => 'list')), 'success');


    }
    include $this->template('wx_fans_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_fans->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_fans', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_fans->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_fans_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_fans->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_fans->delete($id);

    message('删除成功！', referer(), 'success');
}

