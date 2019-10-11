<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 3/6/18
 * Time: 1:42 PM
 */
global $_W, $_GPC;

// shop_member start

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/member/MemberService.class.php');
$_memberService = new MemberService();


$_cookie_key = '__superdesk_shopv2_mobile_session_' . $_W['uniacid'];

$userMobile = empty($_GPC['userMobile']) ? $_COOKIE[$_W['config']['cookie']['pre'] . $_cookie_key] : $_GPC['userMobile'];

$_shop_member_openid = $_memberService->getOneByOpenid($_W['openid']); // query shop member by openid
$_shop_member_mobile = $_memberService->getOneByMobile($userMobile); // query shop member by mobile

//socket_log(json_encode($_shop_member_openid,JSON_UNESCAPED_UNICODE));
//socket_log(json_encode($_shop_member_mobile,JSON_UNESCAPED_UNICODE));

$_shop_member = false;

if($_shop_member_openid != false){
    $_shop_member = $_shop_member_openid;
}

if($_shop_member_mobile != false){
    $_shop_member = $_shop_member_mobile;
}

if($_shop_member != false){
    $this->_fromuser = $_shop_member['openid'];

//    $nickname   = $_shop_member['nickname'];
//    $headimgurl = $_shop_member['avatar'];
} else {

}

// shop_member end

$from_user = $this->_fromuser;
$uniacid   = $this->_uniacid;

$method  = 'index';//method
$authurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true) . '&authkey=1';
$url     = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);

//if (isset($_COOKIE[$this->_auth2_openid])) {
//
//    $from_user  = $_COOKIE[$this->_auth2_openid];
//    $nickname   = $_COOKIE[$this->_auth2_nickname];
//    $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
//
//} else {
//    if (isset($_GPC['code'])) {
//
////                $userinfo = $this->oauth2($authurl);
//
//        if (!empty($userinfo)) {
//            $from_user  = $userinfo["openid"];
//            $nickname   = $userinfo["nickname"];
//            $headimgurl = $userinfo["headimgurl"];
//        } else {
//            message('授权失败!');
//        }
//
//    } else {
//
//        if (!empty($this->_appsecret)) {
////                    $this->toAuthUrl($url);
//        }
//    }
//}

$setting = pdo_fetch(
    " select * " .
    " from " . tablename($this->modulename . '_setting') .
    " where uniacid =:uniacid LIMIT 1",
    array(
        ':uniacid' => $_W['uniacid']
    )
);

$topimgurl = RES . 'images/logo.png';
$ischeck   = 1;
$pindex    = max(1, intval($_GPC['page']));
$psize     = 10;

if (!empty($setting)) {

    $psize = intval($setting['pagesize']) == 0 ? 10 : intval($setting['pagesize']);
    if (!empty($setting['topimgurl'])) {
        if (strstr($setting['topimgurl'], 'http')) {
            $topimgurl = $setting['topimgurl'];
        } else {
            $topimgurl = $_W['attachurl'] . $setting['topimgurl'];
        }
    }

    $ischeck = intval($setting['ischeck']);
}

$where = 'AND status=1 AND parentid=0';

$list = pdo_fetchall(
    " SELECT * " .
    " FROM " . tablename($this->modulename . '_feedback') .
    " WHERE uniacid=" . $_W['uniacid'] .
    " {$where} " .
    " ORDER BY displayorder DESC,id DESC " .
    " LIMIT " . ($pindex - 1) * $psize . ",{$psize}", array(), 'id');


$parentids = array_keys($list);

$childlist = pdo_fetchall(
    " SELECT * " .
    " FROM " . tablename($this->modulename . '_feedback') .
    " WHERE parentid IN ('" . implode("','", is_array($parentids) ? $parentids : array($parentids)) . "') " .
    "       AND parentid!=0 " .
    "       AND status=1".
    "       AND uniacid=:uniacid " .
    " ORDER BY displayorder DESC,id DESC", array(':uniacid' => $uniacid));

foreach ($childlist as $index => $row) {
    if (!empty($row['parentid'])) {
        $children[$row['parentid']][] = $row;
    }
}

if (!empty($list)) {
    $total = pdo_fetchcolumn(
        " SELECT COUNT(*) " .
        " FROM " . tablename($this->modulename . '_feedback') .
        " WHERE uniacid=" . $_W['uniacid'] .
        " {$where}");

    $pager = $this->pagination($total, $pindex, $psize);
}

include $this->template('index');