<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/3/19
 * Time: 1:51 PM
 */


// 用于从企业正式2企业测试 shop_member init
//ALTER TABLE `ims_superdesk_shop_member` ADD COLUMN `core_organization`  int(11) NULL DEFAULT 0 COMMENT '项目id' AFTER `core_enterprise`;
//ALTER TABLE `ims_superdesk_shop_member` ADD COLUMN `core_user` int(10) NULL DEFAULT 0 COMMENT "楼宇之窗用户ID" AFTER `openid`;


// 用于楼宇之窗数据合并变更新 reset
// patch_build_window_superdesk_shop_setup_02_reset_core_user_0.inc.php
// 相当于
//UPDATE `ims_superdesk_shop_abonus_billp` SET core_user = 0;
//UPDATE `ims_superdesk_shop_article_log` SET core_user = 0;
//UPDATE `ims_superdesk_shop_article_report` SET core_user = 0;
//UPDATE `ims_superdesk_shop_author_billp` SET core_user = 0;
//UPDATE `ims_superdesk_shop_bargain_actor` SET core_user = 0;
//UPDATE `ims_superdesk_shop_bargain_record` SET core_user = 0;
//UPDATE `ims_superdesk_shop_commission_clickcount` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_commission_repurchase` SET core_user = 0;
//UPDATE `ims_superdesk_shop_coupon_data` SET core_user = 0;  已更新
//UPDATE `ims_superdesk_shop_coupon_guess` SET core_user = 0;
//UPDATE `ims_superdesk_shop_coupon_log` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_coupon_sendshow` SET core_user = 0;
//UPDATE `ims_superdesk_shop_coupon_taskdata` SET core_user = 0;
//UPDATE `ims_superdesk_shop_creditshop_log` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_customer_guestbook` SET core_user = 0;
//UPDATE `ims_superdesk_shop_diyform_data` SET core_user = 0;   已更新
//UPDATE `ims_superdesk_shop_diyform_temp` SET core_user = 0;   已更新
//UPDATE `ims_superdesk_shop_enterprise_account` SET core_user = 0;   已更新
//UPDATE `ims_superdesk_shop_enterprise_import_log` SET core_user = 0;   已更新
//UPDATE `ims_superdesk_shop_enterprise_reg` SET core_user = 0;   已更新
//UPDATE `ims_superdesk_shop_enterprise_user` SET core_user = 0;
//UPDATE `ims_superdesk_shop_feedback` SET core_user = 0;
//UPDATE `ims_superdesk_shop_globonus_billp` SET core_user = 0;   已更新
//UPDATE `ims_superdesk_shop_goods_comment` SET core_user = 0;   已更新
//UPDATE `ims_superdesk_shop_groups_order` SET core_user = 0;
//UPDATE `ims_superdesk_shop_groups_order_refund` SET core_user = 0;
//UPDATE `ims_superdesk_shop_groups_paylog` SET core_user = 0;
//UPDATE `ims_superdesk_shop_groups_verify` SET core_user = 0;
//UPDATE `ims_superdesk_shop_member` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_member_address` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_member_cart` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_member_credit_log` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_member_favorite` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_member_history` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_member_invoice` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_member_log` SET core_user = 0; 已更新
//UPDATE `ims_superdesk_shop_merch_account` SET core_user = 0;
//UPDATE `ims_superdesk_shop_merch_reg` SET core_user = 0;
//UPDATE `ims_superdesk_shop_merch_saler` SET core_user = 0;
//UPDATE `ims_superdesk_shop_merch_user` SET core_user = 0;
//UPDATE `ims_superdesk_shop_order` SET core_user = 0;  已更新
//UPDATE `ims_superdesk_shop_order_comment` SET core_user = 0;  已更新
//UPDATE `ims_superdesk_shop_order_examine` SET core_user = 0;  已更新
//UPDATE `ims_superdesk_shop_order_goods` SET core_user = 0;    已更新
//UPDATE `ims_superdesk_shop_poster_log` SET core_user = 0;    已更新
//UPDATE `ims_superdesk_shop_poster_qr` SET core_user = 0;    已更新
//UPDATE `ims_superdesk_shop_poster_scan` SET core_user = 0;    已更新
//UPDATE `ims_superdesk_shop_postera_log` SET core_user = 0;    已更新
//UPDATE `ims_superdesk_shop_postera_qr` SET core_user = 0;    已更新
//UPDATE `ims_superdesk_shop_refund_address` SET core_user = 0;    已更新
//UPDATE `ims_superdesk_shop_sale_coupon_data` SET core_user = 0;
//UPDATE `ims_superdesk_shop_saler` SET core_user = 0;
//UPDATE `ims_superdesk_shop_sign_records` SET core_user = 0;
//UPDATE `ims_superdesk_shop_sign_user` SET core_user = 0;
//UPDATE `ims_superdesk_shop_sns_board_follow` SET core_user = 0;
//UPDATE `ims_superdesk_shop_sns_like` SET core_user = 0;
//UPDATE `ims_superdesk_shop_sns_manage` SET core_user = 0;
//UPDATE `ims_superdesk_shop_sns_member` SET core_user = 0;
//UPDATE `ims_superdesk_shop_sns_post` SET core_user = 0;
//UPDATE `ims_superdesk_shop_task_log` SET core_user = 0;
//UPDATE `ims_superdesk_shop_task_poster_qr` SET core_user = 0;
//UPDATE `ims_superdesk_shop_virtual_data` SET core_user = 0;






















//正在显示第 0 - 24 行 (共 4481 行, 查询花费 0.0005 秒。)
//SELECT * FROM `ims_superdesk_shop_member` WHERE core_user = 0




//正在显示第 0 - 24 行 (共 4304 行, 查询花费 0.0007 秒。)
//SELECT * FROM `ims_superdesk_shop_member` WHERE core_user = 0 and core_enterprise > 0 and core_organization >0



//正在显示第 0 - 24 行 (共 4337 行, 查询花费 0.0006 秒。)
//SELECT * FROM `ims_superdesk_shop_member` WHERE core_user = 0 and core_enterprise > 0





//正在显示第 100 - 124 行 (共 4157 行, 查询花费 0.0054 秒。)
//SELECT mobile,core_enterprise,core_organization FROM `ims_superdesk_shop_member` GROUP BY mobile,core_enterprise,core_organization


//正在显示第 0 - 33 行 (共 34 行, 查询花费 0.0081 秒。)
//SELECT mobile,core_enterprise,core_organization,COUNT(1) as total FROM `ims_superdesk_shop_member` GROUP BY mobile,core_enterprise,core_organization HAVING total >1

//正在显示第 0 - 24 行 (共 282 行, 查询花费 0.0008 秒。)
//SELECT * FROM `ims_superdesk_shop_member` WHERE 1 and mobile = ''


//正在显示第 0 - 6 行 (共 7 行, 查询花费 0.0025 秒。) 282
//SELECT mobile,core_enterprise,core_organization,COUNT(1) as total FROM `ims_superdesk_shop_member` WHERE mobile = '' GROUP BY mobile,core_enterprise,core_organization

//删除了 282 行。 (查询花费 0.1484 秒。)
//DELETE FROM `ims_superdesk_shop_member` WHERE 1 and mobile = ''


//正在显示第 0 - 0 行 (共 1 行, 查询花费 0.0099 秒。)
//SELECT SUM(total) FROM (SELECT mobile,core_enterprise,core_organization,COUNT(1) as total FROM `ims_superdesk_shop_member` GROUP BY mobile,core_enterprise,core_organization HAVING total >1) as a



//SELECT * FROM `ims_superdesk_shop_member` WHERE 1 and mobile in (
//    SELECT mobile FROM (SELECT mobile,core_enterprise,core_organization,COUNT(1) as total FROM `ims_superdesk_shop_member` GROUP BY mobile,core_enterprise,core_organization HAVING total >1) as a
//)
//and logintime = 0
//ORDER BY mobile

//删除了 74 行。 (查询花费 0.0235 秒。)
//DELETE FROM `ims_superdesk_shop_member` WHERE 1 and mobile in ( SELECT mobile FROM (SELECT mobile,core_enterprise,core_organization,COUNT(1) as total FROM `ims_superdesk_shop_member` GROUP BY mobile,core_enterprise,core_organization HAVING total >1) as a ) and logintime = 0


//正在显示第 0 - 24 行 (共 54 行, 查询花费 0.0024 秒。)
//SELECT mobile,core_enterprise,core_organization,COUNT(1) as total FROM `ims_superdesk_shop_member` WHERE core_enterprise = 0 and core_organization = 0 GROUP BY mobile,core_enterprise,core_organization



//正在显示第 50 - 56 行 (共 57 行, 查询花费 0.0174 秒。) [mobile: 18398464669 - 18988776311]
//SELECT * FROM `ims_superdesk_shop_member` WHERE 1 and mobile in ( SELECT mobile FROM `ims_superdesk_shop_member` WHERE core_enterprise = 0 and core_organization = 0 GROUP BY mobile,core_enterprise,core_organization ) ORDER BY mobile

//57

//正在显示第 0 - 24 行 (共 4037 行, 查询花费 0.0078 秒。) [mobile: 13001170008 - 13032128111]
//SELECT * FROM `ims_superdesk_shop_member` WHERE 1 and mobile in ( SELECT mobile FROM `ims_superdesk_shop_member` WHERE core_enterprise > 0 and core_organization > 0 GROUP BY mobile,core_enterprise,core_organization ) ORDER BY mobile


//4123
//
//正在显示第 0 - 24 行 (共 380 行, 查询花费 0.0025 秒。) [mobile: 13008832432 - 13322926641]
//SELECT * FROM `ims_superdesk_shop_member` WHERE core_user = 0 ORDER BY mobile
//
//
//正在显示第 0 - 24 行 (共 3743 行, 查询花费 0.0029 秒。) [mobile: 13001170008 - 13032823020]
//SELECT * FROM `ims_superdesk_shop_member` WHERE core_user > 0 ORDER BY mobile



//正在显示第 0 - 24 行 (共 359 行, 查询花费 0.0012 秒。)
//SELECT * FROM `ims_superdesk_shop_order` WHERE 1 and member_enterprise_id = 0 AND member_organization_id = 0