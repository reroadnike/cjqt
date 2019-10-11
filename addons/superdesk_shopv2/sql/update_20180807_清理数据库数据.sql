

-- 微擎 支付 日志
delete from ims_core_paylog where 1;
# 已清除

-- 微信 余额/积分 充值/扣减 日志
delete from ims_mc_credits_record where 1;
# 已清除

-- 微信 粉丝表. 不确定要不要删
delete from ims_mc_mapping_fans where 1;
# 已清除

-- 微信 会员表. 不确定要不要删
delete from ims_mc_members where 1;
# 已清除

-- 京东VOP 财务 对帐 记录
delete from ims_superdesk_jd_vop_balance_detail where 1;
# 已清除

-- 京东接口调用日志
delete from ims_superdesk_jd_vop_logs where 1;
# 已清除

-- 京东订单
delete from ims_superdesk_jd_vop_order_submit_order where 1;
# 已清除

-- 京东订单商品
delete from ims_superdesk_jd_vop_order_submit_order_sku where 1;
# 已清除

-- 搜索日志
delete from ims_superdesk_jd_vop_search where 1;
# 已清除

-- 积分商城表日志
delete from ims_superdesk_shop_creditshop_log where 1
# 已清除

-- 商城 分类 总表 TODO 区分 京东 | 自填 好删除
UPDATE `ims_superdesk_shop_category` SET uniacid = 17 WHERE uniacid = 16
# 已清除

-- 企业端导入日志
delete from ims_superdesk_shop_enterprise_import_log where 1;
# 已清除

-- 企业端权限日志
delete from ims_superdesk_shop_enterprise_perm_log where 1;
# 已清除









-- 反馈表
delete from ims_superdesk_feedback_feedback where 1;

-- 分销 提现 申请
delete from ims_superdesk_shop_commission_apply where 1;
# 已清除

-- 分销 分享 记录 谁通过分销员 进入的
delete from ims_superdesk_shop_commission_clickcount where 1;
# 已清除

-- 用户 表
delete from ims_superdesk_shop_member where 1;
# 已清除

-- 用户 收货地址
delete from ims_superdesk_shop_member_address where 1;
# 已清除

-- 用户 购物车
delete from ims_superdesk_shop_member_cart where 1;
# 已清除

-- 用户 收藏
delete from ims_superdesk_shop_member_favorite where 1;
# 已清除

-- 用户 足迹
delete from ims_superdesk_shop_member_history where 1;
# 已清除

-- 用户 发票
delete from ims_superdesk_shop_member_invoice where 1;
# 已清除

-- 用户 余额/积分 充值/扣减 日志
delete from ims_superdesk_shop_member_log where 1;
# 已清除

-- 商户 帐户
delete from ims_superdesk_shop_merch_account where 1;
# 已清除

-- 商户 广告
delete from ims_superdesk_shop_merch_adv where 1;
# 已清除

-- 商户 基于 权限 操作 日志
delete from ims_superdesk_shop_merch_perm_log where 1;
# 已清除

-- 商户 角色 权限
delete from ims_superdesk_shop_merch_perm_role where 1;
# 已清除

-- 商户 banner
delete from ims_superdesk_shop_merch_banner where 1;
# 已清除

-- 商户 入驻 申请
delete from ims_superdesk_shop_merch_reg where 1;
# 已清除

-- 商户 门店 销售员
delete from ims_superdesk_shop_merch_saler where 1;
# 已清除

-- 商户 O2O 门店
delete from ims_superdesk_shop_merch_store where 1;
# 已清除

-- 多商户
delete from ims_superdesk_shop_merch_user where 1;
# 已清除

-- 商户 服务 企业
delete from ims_superdesk_shop_merch_x_enterprise where 1
# 已清除

-- 订单 表
delete from ims_superdesk_shop_order where 1;
# 已清除

-- 订单 评价
delete from ims_superdesk_shop_order_comment where 1;
# 已清除

-- 订单 审核
delete from ims_superdesk_shop_order_examine where 1;
# 已清除

-- 订单 财务 追踪
delete from ims_superdesk_shop_order_finance where 1;
# 已清除

-- 订单 商品 清单
delete from ims_superdesk_shop_order_goods where 1;
# 已清除

-- 订单 维权 退款
delete from ims_superdesk_shop_order_refund where 1;
# 已清除

-- 订单 转介 记录
delete from ims_superdesk_shop_order_transfer where 1;
# 已清除

-- 总端 基于 权限 操作 日志
delete from ims_superdesk_shop_perm_log where 1;
# 已清除

-- 总端 角色 权限
delete from ims_superdesk_shop_perm_role where 1;
# 已清除

-- 总端 用户 权限
delete from ims_superdesk_shop_perm_user where 1;
# 已清除

-- O2O 门店
delete from ims_superdesk_shop_store where 1;
# 已清除


ims_superdesk_shop_enterprise_account

UPDATE `ims_superdesk_shop_enterprise_account` SET uniacid = 17 WHERE uniacid = 16
UPDATE `ims_superdesk_shop_enterprise_perm_role` SET uniacid = 17 WHERE uniacid = 16
UPDATE `ims_superdesk_shop_enterprise_user` SET uniacid = 17 WHERE uniacid = 16







-- 功能 清除重复sku
ims_cc_superdesk_shop_goods_cc_sku
drop table ims_cc_superdesk_shop_goods_cc_sku

-- 功能 微擎 附件
ims_core_attachment
TRUNCATE table ims_core_attachment

-- 微擎 门店 折扣
ims_activity_coupon_password

-- 微擎 数据库日志
ims_core_performance


ims_core_sessions
ims_core_cache

ims_elasticsearch_dictionary
ims_elasticsearch_dictionary_category


清理

-- 红包
DROP TABLE `ims_n1ce_shake_acts`, `ims_n1ce_shake_before`;
-- 调查
DROP TABLE `ims_research`, `ims_research_data`, `ims_research_fields`, `ims_research_order`, `ims_research_reply`, `ims_research_rows`;
-- 星座
DROP TABLE `ims_hongapis`, `ims_hongapitype`;
-- 签到
DROP TABLE `ims_nsign_add`, `ims_nsign_prize`, `ims_nsign_record`, `ims_nsign_reply`;
-- 停车 计费 ?
DROP TABLE `ims_parking_order`, `ims_parking_reserve`;
-- 羊城同创汇_物业 报事报修?
DROP TABLE `ims_property_express`, `ims_property_home`, `ims_property_order`, `ims_property_sy_report`, `ims_property_water`;