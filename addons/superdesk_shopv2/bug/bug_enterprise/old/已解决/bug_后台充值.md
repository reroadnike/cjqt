http://www.avic-s.com/plugins/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=member.list&realname=%E9%9B%A8&followed=&level=&groupid=&isblack=&time%5Bstart%5D=&time%5Bend%5D=


<p>SQL: <br/>select templateid  from `ims_superdesk_shop_member_message_template_default` where typecode=:typecode and uniacid=:uniacid  limit 1<hr/>Params: <br/>array (
  ':typecode' => 'recharge_ok',
  ':uniacid' => 16,
)<hr/>SQL Error: <br/>Table 'db_super_desk.ims_superdesk_shop_member_message_template_default' doesn't exist<hr/>Traces: <br/>file: /framework/class/db.class.php; line: 137; <br />file: /framework/function/pdo.func.php; line: 40; <br />file: /addons/superdesk_shopv2/core/model/notice.php; line: 1791; <br />file: /addons/superdesk_shopv2/core/model/notice.php; line: 1559; <br />file: /addons/superdesk_shopv2/core/web/finance/recharge.php; line: 51; <br />file: /addons/superdesk_shopv2/core/model/route.php; line: 158; <br />file: /addons/superdesk_shopv2/site.php; line: 21; <br />file: /web/source/site/entry.ctrl.php; line: 76; <br />file: /web/index.php; line: 172; <br /></p>
																<p>[<a href="javascript:history.go(-1);">点击这里返回上一页</a>] &nbsp; [<a href="./?refresh">首页</a>]</p>
									</div>
									
									
									
update_20180222.sql
解决
缺表了