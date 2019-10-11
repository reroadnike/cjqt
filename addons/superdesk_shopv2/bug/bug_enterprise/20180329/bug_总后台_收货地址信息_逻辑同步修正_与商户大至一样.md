<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="shortcut icon" href="http://www.avic-s.com/plugins/attachment/images/global/wechat.jpg" />
	<link href="./resource/css/bootstrap.min.css" rel="stylesheet">
	<link href="./resource/css/font-awesome.min.css" rel="stylesheet">
	<link href="./resource/css/common.css" rel="stylesheet">
	<script>var require = { urlArgs: 'v=2018032922' };</script>
	<script src="./resource/js/lib/jquery-1.11.1.min.js"></script>
	<script src="./resource/js/app/util.js"></script>
	<script src="./resource/js/require.js"></script>
	<script src="./resource/js/app/config.js"></script>
	<!--[if lt IE 9]>
		<script src="./resource/js/html5shiv.min.js"></script>
		<script src="./resource/js/respond.min.js"></script>
	<![endif]-->
	<script type="text/javascript">
	if(navigator.appName == 'Microsoft Internet Explorer'){
		if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {
			alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
		}
	}
	
	window.sysinfo = {
		'uniacid': '16',
		'acid': '16',
		'uid': '1',
		'siteroot': 'http://www.avic-s.com/plugins/',
		'siteurl': 'http://www.avic-s.com/plugins/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=order.op.changeaddress',
		'attachurl': 'http://www.avic-s.com/plugins/attachment/',
		'attachurl_local': 'http://www.avic-s.com/plugins/attachment/',
		'attachurl_remote': '',
		'MODULE_URL': 'http://www.avic-s.com/plugins/addons/superdesk_shopv2/',
		'cookie' : {'pre': '222a_'}
	};
	</script>
</head>
<body>
		<div class="navbar navbar-inverse navbar-static-top" role="navigation" style="position:static;">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="./?refresh"><i class="fa fa-reply-all"></i>返回系统</a></li>
													<li><a href="./index.php?c=home&a=welcome&do=platform&"><i class="fa fa-cog"></i>基础设置</a></li>									<li><a href="./index.php?c=home&a=welcome&do=site&"><i class="fa fa-life-bouy"></i>微站功能</a></li>									<li><a href="./index.php?c=home&a=welcome&do=mc&"><i class="fa fa-gift"></i>粉丝营销</a></li>									<li><a href="./index.php?c=home&a=welcome&do=setting&"><i class="fa fa-umbrella"></i>功能选项</a></li>									<li class="active"><a href="./index.php?c=home&a=welcome&do=ext&"><i class="fa fa-cubes"></i>扩展功能</a></li>								<li >
					<a href="./index.php?c=utility&a=emulator&" target="_blank"><i class="fa fa-mobile"></i> 模拟测试</a>
				</li>

			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown topbar-notice">
					<a type="button" data-toggle="dropdown">
						<i class="fa fa-bell"></i>
						<span class="badge" id="notice-total">0</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="dLabel">
						<div class="topbar-notice-panel">
							<div class="topbar-notice-arrow"></div>
							<div class="topbar-notice-head">
								<span>系统公告</span>
								<a href="./index.php?c=article&a=notice-show&do=list&" class="pull-right">更多公告>></a>
							</div>
							<div class="topbar-notice-body">
								<ul id="notice-container"></ul>
							</div>
						</div>
					</div>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="display:block; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "><i class="fa fa-group"></i>超级前台服务站 <b class="caret"></b></a>
					<ul class="dropdown-menu">
												<li><a href="./index.php?c=account&a=post&uniacid=16" target="_blank"><i class="fa fa-weixin fa-fw"></i> 编辑当前账号资料</a></li>
												<li><a href="./index.php?c=account&a=display&" target="_blank"><i class="fa fa-cogs fa-fw"></i> 管理其它公众号</a></li>
						<li><a href="./index.php?c=utility&a=emulator&" target="_blank"><i class="fa fa-mobile fa-fw"></i> 模拟测试</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="display:block; max-width:185px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "><i class="fa fa-user"></i>root (系统管理员) <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="./index.php?c=user&a=profile&do=profile&" target="_blank"><i class="fa fa-weixin fa-fw"></i> 我的账号</a></li>
												<li class="divider"></li>
						<li><a href="./index.php?c=system&a=welcome&" target="_blank"><i class="fa fa-sitemap fa-fw"></i> 系统选项</a></li>
						<li><a href="./index.php?c=system&a=welcome&" target="_blank"><i class="fa fa-cloud-download fa-fw"></i> 自动更新</a></li>
						<li><a href="./index.php?c=system&a=updatecache&" target="_blank"><i class="fa fa-refresh fa-fw"></i> 更新缓存</a></li>
						<li class="divider"></li>
												<li><a href="./index.php?c=user&a=logout&"><i class="fa fa-sign-out fa-fw"></i> 退出系统</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
			<div class="container-fluid">
				<div class="jumbotron clearfix alert alert-info">
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-lg-2">
					<i class="fa fa-5x fa-info-circle"></i>
				</div>
				<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
										<h2></h2>
					<p>SQL: <br/>UPDATE `ims_superdesk_shop_member_address` SET 1 WHERE `id` =  :__id<hr/>Params: <br/>array (
  ':__id' => '',
)<hr/>SQL Error: <br/>You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '1 WHERE `id` =  ''' at line 1<hr/>Traces: <br/>file: /framework/class/db.class.php; line: 95; <br />file: /framework/class/db.class.php; line: 242; <br />file: /framework/function/pdo.func.php; line: 62; <br />file: /addons/superdesk_shopv2/model/member/member_address.class.php; line: 46; <br />file: /addons/superdesk_shopv2/core/web/order/op.php; line: 1202; <br />file: /addons/superdesk_shopv2/core/model/route.php; line: 158; <br />file: /addons/superdesk_shopv2/site.php; line: 21; <br />file: /web/source/site/entry.ctrl.php; line: 76; <br />file: /web/index.php; line: 172; <br /></p>
																<p>[<a href="javascript:history.go(-1);">点击这里返回上一页</a>] &nbsp; [<a href="./?refresh">首页</a>]</p>
									</div>
					</div>av
		</div>
	</div>
	<script>
		function subscribe(){
			$.post("./index.php?c=utility&a=subscribe&", function(){
				setTimeout(subscribe, 5000);
			});
		}
		function sync() {
			$.post("./index.php?c=utility&a=sync&", function(){
				setTimeout(sync, 60000);
			});
		}
		$(function(){
			subscribe();
			sync();
		});
					function checknotice() {
				$.post("./index.php?c=utility&a=notice&", {}, function(data){
					var data = $.parseJSON(data);
					$('#notice-container').html(data.notices);
					$('#notice-total').html(data.total);
					if(data.total > 0) {
						$('#notice-total').css('background', '#ff9900');
					} else {
						$('#notice-total').css('background', '');
					}
					setTimeout(checknotice, 60000);
				});
			}
			checknotice();
		
				$.getJSON("./index.php?c=utility&a=checkupgrade&do=module&m=superdesk_shopv2", function(result) {
			if (result.message.errno == -10) {
				$('body').prepend('<div id="upgrade-tips-module" class="upgrade-tips"><a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzkzODAwMzEzOV8xNzEwOTZfNDAwMDgyODUwMl8yXw" target="_blank">' + result.message.message + '</a></div>');
				if ($('#upgrade-tips').size()) {
					$('#upgrade-tips-module').css('top', '25px');
				}
			}
		});
			</script>
	<script type="text/javascript">
		require(['bootstrap']);
		$('.js-clip').each(function(){
			util.clip(this, $(this).attr('data-url'));
		});
	</script>
	<div class="container-fluid footer" role="footer">
		<div class="page-header"></div>
		<span class="pull-left">
			<p></p>
		</span>
		<span class="pull-right">
			<p></p>
		</span>
	</div>
	</body>
</html>
