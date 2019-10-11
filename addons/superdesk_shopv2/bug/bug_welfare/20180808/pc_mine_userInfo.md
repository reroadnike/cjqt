<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>福利商城_非开发者模式连接_FAKE - 福利商城_非开发者模式连接_FAKE</title>
	<meta name="format-detection" content="telephone=no, address=no">
	<meta name="apple-mobile-web-app-capable" content="yes" /> <!-- apple devices fullscreen -->
	<meta name="apple-touch-fullscreen" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<meta name="keywords" content="本创,众创" />
	<meta name="description" content="本创 - 介绍" />
	<link rel="shortcut icon" href="https://wxn.avic-s.com/attachment/images/global/wechat.jpg" />
	<link href="https://wxn.avic-s.com/app/resource/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://wxn.avic-s.com/app/resource/css/font-awesome.min.css" rel="stylesheet">
	<link href="https://wxn.avic-s.com/app/resource/css/animate.css" rel="stylesheet">
	<link href="https://wxn.avic-s.com/app/resource/css/common.css" rel="stylesheet">
	<link href="https://wxn.avic-s.com/app/resource/css/app.css" rel="stylesheet">
	<link href="https://wxn.avic-s.com/app/index.php?i=17&c=utility&a=style&" rel="stylesheet">
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script src="https://wxn.avic-s.com/app/resource/js/require.js"></script>
	<script src="https://wxn.avic-s.com/app/resource/js/app/config.js"></script>
	<script type="text/javascript" src="https://wxn.avic-s.com/app/resource/js/lib/jquery-1.11.1.min.js"></script>
	<script type="text/javascript">
	if(navigator.appName == 'Microsoft Internet Explorer'){
		if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {
			alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
		}
	}
		window.sysinfo = {
		'uniacid': '17',
		'acid': '17',
		'openid': 'wap_user_17_13422832499',
		'siteroot': 'https://wxn.avic-s.com/',
		'siteurl': 'https://wxn.avic-s.com/app/index.php?i=17&c=entry&m=superdesk_shopv2&do=mobile&r=pc.api.member.info.submit',
		'attachurl': 'https://wxn.avic-s.com/attachment/',
		'attachurl_local': 'https://wxn.avic-s.com/attachment/',
		'attachurl_remote': '',
		'MODULE_URL': 'https://wxn.avic-s.com/addons/superdesk_shopv2/',
		'cookie' : {'pre': '222a_'}
	};
	
	// jssdk config 对象
	jssdkconfig = null || {};
	
	// 是否启用调试
	jssdkconfig.debug = false;
	
	jssdkconfig.jsApiList = [
		'checkJsApi',
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'onMenuShareQQ',
		'onMenuShareWeibo',
		'hideMenuItems',
		'showMenuItems',
		'hideAllNonBaseMenuItem',
		'showAllNonBaseMenuItem',
		'translateVoice',
		'startRecord',
		'stopRecord',
		'onRecordEnd',
		'playVoice',
		'pauseVoice',
		'stopVoice',
		'uploadVoice',
		'downloadVoice',
		'chooseImage',
		'previewImage',
		'uploadImage',
		'downloadImage',
		'getNetworkType',
		'openLocation',
		'getLocation',
		'hideOptionMenu',
		'showOptionMenu',
		'closeWindow',
		'scanQRCode',
		'chooseWXPay',
		'openProductSpecificView',
		'addCard',
		'chooseCard',
		'openCard'
	];
	
	</script>
	
	<script>
	function _removeHTMLTag(str) {
		if(typeof str == 'string'){
			str = str.replace(/<script[^>]*?>[\s\S]*?<\/script>/g,'');
			str = str.replace(/<style[^>]*?>[\s\S]*?<\/style>/g,'');
			str = str.replace(/<\/?[^>]*>/g,'');
			str = str.replace(/\s+/g,'');
			str = str.replace(/&nbsp;/ig,'');
		}
		return str;
	}
	</script>
</head>
<body>
<div class="container container-fill">
		<div class="jumbotron clearfix alert alert-info" style="width:80%;margin:0 auto;margin-top:100px;">
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-lg-2">
					<i class="fa fa-5x fa-info-circle"></i>
				</div>
				<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
										<h2></h2>
					<p>SQL: <br/>UPDATE `ims_superdesk_shop_member` SET `nickname` =  :nickname , `credit2` =  :credit2 , `credit1` =  :credit1 , `avatar` =  :avatar , `mobile` =  :mobile , `weixin` =  :weixin , `realname` =  :realname , `virtualarchitecture_name` =  :virtualarchitecture_name , `organization_name` =  :organization_name WHERE `openid` =  :__openid AND `uniacid` =  :__uniacid<hr/>Params: <br/>array (
  ':nickname' => '进雨',
  ':credit2' => '96.00',
  ':credit1' => '0.00',
  ':avatar' => '',
  ':mobile' => '13422832499',
  ':weixin' => 'l',
  ':realname' => '林进雨',
  ':virtualarchitecture_name' => '',
  ':organization_name' => '',
  ':__openid' => 'wap_user_17_13422832499',
  ':__uniacid' => 17,
)<hr/>SQL Error: <br/>Unknown column 'virtualarchitecture_name' in 'field list'<hr/>Traces: <br/>file: /framework/class/db.class.php; line: 95; <br />file: /framework/class/db.class.php; line: 242; <br />file: /framework/function/pdo.func.php; line: 62; <br />file: /addons/superdesk_shopv2/plugin/pc/core/mobile/api/member/info.php; line: 85; <br />file: /addons/superdesk_shopv2/core/model/route.php; line: 169; <br />file: /addons/superdesk_shopv2/site.php; line: 26; <br />file: /app/source/entry/__init.php; line: 35; <br />file: /app/index.php; line: 85; <br /></p>
															<p>[<a href="javascript:history.go(-1);">点击这里返回上一页</a>]</p>
									</div>
			</div>
		</div>
					<div class="text-center footer" style="margin:10px 0; width:100%; text-align:center; word-break:break-all;">
																	&nbsp;&nbsp;			</div>
						<script>require(['bootstrap']);</script>
	</div>
	<style>
		h5{color:#555;}
	</style>
	<script type="text/javascript">
	
	wx.config(jssdkconfig);
	
	var $_share = {"title":"\u798f\u5229\u5546\u57ce_\u975e\u5f00\u53d1\u8005\u6a21\u5f0f\u8fde\u63a5_FAKE","imgUrl":"","desc":"","link":"https:\/\/wxn.avic-s.com\/app\/index.php?i=17&c=entry&m=superdesk_shopv2&do=mobile&r=pc.api.member.info.submit"};
	
	if(typeof sharedata == 'undefined'){
		sharedata = $_share;
	} else {
		sharedata['title'] = sharedata['title'] || $_share['title'];
		sharedata['desc'] = sharedata['desc'] || $_share['desc'];
		sharedata['link'] = sharedata['link'] || $_share['link'];
		sharedata['imgUrl'] = sharedata['imgUrl'] || $_share['imgUrl'];
	}

	function tomedia(src) {
		if(typeof src != 'string')
			return '';
		if(src.indexOf('http://') == 0 || src.indexOf('https://') == 0) {
			return src;
		} else if(src.indexOf('../addons') == 0 || src.indexOf('../attachment') == 0) {
			src=src.substr(3);
			return window.sysinfo.siteroot + src;
		} else if(src.indexOf('./resource') == 0) {
			src=src.substr(2);
			return window.sysinfo.siteroot + 'app/' + src;
		} else if(src.indexOf('images/') == 0) {
			return window.sysinfo.attachurl+ src;
		}
	}
	
	if(sharedata.imgUrl == ''){
		var _share_img = $('body img:eq(0)').attr("src");
		if(_share_img == ""){
			sharedata['imgUrl'] = window.sysinfo.attachurl + 'images/global/wechat_share.png';
		} else {
			sharedata['imgUrl'] = tomedia(_share_img);
		}
	}
	
	if(sharedata.desc == ''){
		var _share_content = _removeHTMLTag($('body').html());
		if(typeof _share_content == 'string'){
			sharedata.desc = _share_content.replace($_share['title'], '')
		}
	}
	
	wx.ready(function () {
		wx.onMenuShareAppMessage(sharedata);
		wx.onMenuShareTimeline(sharedata);
		wx.onMenuShareQQ(sharedata);
		wx.onMenuShareWeibo(sharedata);
	});
		$(function(){
		if($('.js-quickmenu')!=null && $('.js-quickmenu')!=''){
			var h = $('.js-quickmenu').height()+'px';
			$('body').css("padding-bottom",h);
		}else{
			$('body').css("padding-bottom", "0");
		}
	});

		$.getJSON("./index.php?i=17&c=utility&a=checkupgrade&do=module&m=superdesk_shopv2&wxref=mp.weixin.qq.com#wechat_redirect", function(result) {
		if (result.message.errno == -10) {
			$('body').prepend('<div id="upgrade-tips-module" class="upgrade-tips text-center"><a class="label label-danger" href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzkzODAwMzEzOV8xNzEwOTZfNDAwMDgyODUwMl8yXw" target="_blank">' + result.message.message + '</a></div>');
			if ($('#upgrade-tips').size()) {
				$('#upgrade-tips-module').css('top', '25px');
			}
		}
	});
		</script>
</body>
</html>
