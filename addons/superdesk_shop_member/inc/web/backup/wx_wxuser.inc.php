<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_wxuser */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_wxuser.class.php');
$wx_wxuser = new wx_wxuserModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_wxuser->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'uid' => $_GPC['uid'],// 
    'wxname' => $_GPC['wxname'],// 公众号名称
    'winxintype' => $_GPC['winxintype'],// 公众号类型
    'aeskey' => $_GPC['aeskey'],// 加密串
    'encode' => $_GPC['encode'],// 加密方式,0:表示明文
    'appid' => $_GPC['appid'],// 公众号appid
    'appsecret' => $_GPC['appsecret'],// 公众号密钥
    'wxid' => $_GPC['wxid'],// 公众号原始ID
    'weixin' => $_GPC['weixin'],// 微信号
    'headerpic' => $_GPC['headerpic'],// 头像地址
    'token' => $_GPC['token'],// 登陆用户唯一token
    'pigsecret' => $_GPC['pigsecret'],// 第三方者绑定密钥
    'province' => $_GPC['province'],// 省
    'city' => $_GPC['city'],// 市
    'qq' => $_GPC['qq'],// 公众号邮箱
    'wxfans' => $_GPC['wxfans'],// 微信粉丝
    'typeid' => $_GPC['typeid'],// 分类ID
    'typename' => $_GPC['typename'],// 分类名
    'oauth' => $_GPC['oauth'],// 
    'oauthinfo' => $_GPC['oauthinfo'],// 
    'state' => $_GPC['state'],// 
    'mchid' => $_GPC['mchid'],// 支付id
    'wxkey' => $_GPC['wxkey'],// 支付key

        );
        $wx_wxuser->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_wxuser', array('op' => 'list')), 'success');


    }
    include $this->template('wx_wxuser_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_wxuser->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_wxuser', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_wxuser->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_wxuser_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_wxuser->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_wxuser->delete($id);

    message('删除成功！', referer(), 'success');
}

