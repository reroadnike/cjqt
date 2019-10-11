


总端_微信端_PC端_添加订单来源字段_逻辑



task 微信端下单_记录_from_instert_order_
task PC端下单_记录_from_
task 过往订单_from_7月1日前_default_wechat_pc


task_tb_usr_数据_2_shop_member_条件_1_企业信息完整_Y_导入_N_丢弃_条件_2_认证状态_已认证_条件_3_可用状态_可用
task_

task_PC_微信_扫码支付
_再细分_TODO




2018/07/01
1530374400

2018/07/15
1531584000

SELECT *,FROM_UNIXTIME(createtime) FROM ims_superdesk_shop_order WHERE createtime   AND merchid = 8 


SELECT *,FROM_UNIXTIME(createtime) FROM ims_superdesk_shop_order WHERE createtime BETWEEN 1530374400 AND 1531584000  AND merchid = 8 

SELECT *,FROM_UNIXTIME(createtime) FROM ims_superdesk_shop_order WHERE createtime BETWEEN 1530374400 AND 1531584000 AND merchid = 8 AND source_from = 'wechat'

SELECT 
    so.id,so.openid,so.ordersn,so.status,so.paytype,FROM_UNIXTIME(so.createtime) as createtime ,sm.realname
FROM ims_superdesk_shop_order so
	LEFT JOIN ims_superdesk_shop_member sm ON so.openid = sm.openid
WHERE so.createtime BETWEEN 1530374400 AND 1531584000 AND merchid = 8 AND source_from = 'wechat' AND expresssn = ''



