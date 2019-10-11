<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/4/17
 * Time: 11:32 AM
 */

global $_W, $_GPC;


//$redirect_cover 		= $this->createAngularJsUrl('cover');
//$redirect_home 			= $this->createAngularJsUrl('home');
//
//
//echo $redirect_cover;
//echo '<br/>';
//echo $redirect_home;
//echo '<br/>';
//
//
//$redirect_cover = urlencode($redirect_cover);
//$redirect_home 	= urlencode($redirect_home);
//
//$forward_snsapi_userinfo_cover = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_W['oauth_account']['key'].'&redirect_uri=' . $redirect_cover . '&response_type=code&scope=snsapi_userinfo'.'#wechat_redirect';
//$forward_snsapi_userbase_cover = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_W['oauth_account']['key'].'&redirect_uri=' . $redirect_cover . '&response_type=code&scope=snsapi_userbase'.'#wechat_redirect';
//
//
//$forward_snsapi_userinfo_home = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_W['oauth_account']['key'].'&redirect_uri=' . $redirect_home . '&response_type=code&scope=snsapi_userinfo'.'#wechat_redirect';
//$forward_snsapi_userbase_home = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_W['oauth_account']['key'].'&redirect_uri=' . $redirect_home . '&response_type=code&scope=snsapi_userbase'.'#wechat_redirect';
////        header("location: $forward_snsapi_userinfo");
////        header("location: $forward_snsapi_userbase");
//
//echo $forward_snsapi_userinfo_cover;
//echo '<br/>';
//echo $forward_snsapi_userbase_cover;
//echo '<br/>';
//
//echo $forward_snsapi_userinfo_home;
//echo '<br/>';
//echo $forward_snsapi_userbase_home;
//echo '<br/>';

echo urldecode("https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8f5a07e8746f85e3&redirect_uri=http%3a%2f%2fwww.avic-s.com%2fsuper_reception%2fwechat%2ffrontSecurity%2fauth%3fredirectUrl%3d%2fwechat%2fassets%2findexPage%26pageType%3dmeetionRoomPageSch&response_type=code&scope=snsapi_base&state=1#wechat_redirect");