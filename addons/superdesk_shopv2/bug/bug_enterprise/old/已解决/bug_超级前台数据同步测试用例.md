

http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order&mid=94



http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order.detail&mid=94&id=298


# 较验


用例:南山彩娇 已经绑定过，信息通
SELECT * FROM `ims_superdesk_shop_member` WHERE mobile = 15019433985
SELECT * FROM `ims_superdesk_core_tb_user` WHERE userMobile = 15019433985
正常进入，数据正常


用例：Eric 超级前台未注册
转去超级前台注册


用例:安先生
SELECT * FROM `ims_superdesk_shop_member` WHERE mobile = 18938773714
SELECT * FROM `ims_superdesk_core_tb_user` WHERE userMobile = 18938773714
正常进入，数据正常
自动绑定手机 默认密码为:123456

用例:安先生
如果把安先生的tb_user数据删除(模拟非超级前台用户)，提示 请转跳到超级前台注册或请求管理员同步数据


用例:安先生
如果把安先生的shop_member数据删除(模拟内购商城新用户)，正常进入，数据正常
自动绑定手机 默认密码为:123456

用例:安先生 oX8KYwiNR5JrUFaIs5TZPDL9yrXI
安先生取消关注:超级前台服务站
删除下边表记录
SELECT * FROM `ims_mc_members` WHERE uid = 4192
SELECT * FROM `ims_mc_mapping_fans` WHERE openid = 'oX8KYwiNR5JrUFaIs5TZPDL9yrXI'
SELECT * FROM `ims_superdesk_shop_member` WHERE mobile = 18938773714
tb_user 数据存在
正常进入，数据正常
自动绑定手机 默认密码为:123456



http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=util.task&mid=94&_=1517891231740
http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order.create.caculate&mid=94
http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order.create.submit&mid=94

