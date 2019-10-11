<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 3/8/19
 * Time: 1:26 AM
 *
 * view-source:http://192.168.1.223/superdesk/app/index.php?i=16&c=entry&m=superdesk_recovery&do=backup_superdesk_shop
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_recovery&do=backup_superdesk_shop
 */

$backup_datetime = '20190308';
$backup_database = 'db_super_desk';
$backup_table    = [
    "ims_superdesk_shop_abonus_billp",
    "ims_superdesk_shop_article_log",
    "ims_superdesk_shop_article_report",
    "ims_superdesk_shop_author_billp",
    "ims_superdesk_shop_bargain_actor",
    "ims_superdesk_shop_bargain_record",
    "ims_superdesk_shop_commission_clickcount",
    "ims_superdesk_shop_commission_repurchase",
    "ims_superdesk_shop_coupon_data",
    "ims_superdesk_shop_coupon_guess",
    "ims_superdesk_shop_coupon_log",
    "ims_superdesk_shop_coupon_sendshow",
    "ims_superdesk_shop_coupon_taskdata",
    "ims_superdesk_shop_creditshop_log",
    "ims_superdesk_shop_customer_guestbook",
    "ims_superdesk_shop_diyform_data",
    "ims_superdesk_shop_diyform_temp",
    "ims_superdesk_shop_enterprise_account",
    "ims_superdesk_shop_enterprise_import_log",
    "ims_superdesk_shop_enterprise_reg",
    "ims_superdesk_shop_enterprise_user",
    "ims_superdesk_shop_feedback",
    "ims_superdesk_shop_globonus_billp",
    "ims_superdesk_shop_goods_comment",
    "ims_superdesk_shop_groups_order",
    "ims_superdesk_shop_groups_order_refund",
    "ims_superdesk_shop_groups_paylog",
    "ims_superdesk_shop_groups_verify",
    "ims_superdesk_shop_member",
    "ims_superdesk_shop_member_address",
    "ims_superdesk_shop_member_cart",
    "ims_superdesk_shop_member_credit_log",
    "ims_superdesk_shop_member_favorite",
    "ims_superdesk_shop_member_history",
    "ims_superdesk_shop_member_invoice",
    "ims_superdesk_shop_member_log",
    "ims_superdesk_shop_merch_account",
    "ims_superdesk_shop_merch_reg",
    "ims_superdesk_shop_merch_saler",
    "ims_superdesk_shop_merch_user",
    "ims_superdesk_shop_order",
    "ims_superdesk_shop_order_comment",
    "ims_superdesk_shop_order_examine",
    "ims_superdesk_shop_order_goods",
    "ims_superdesk_shop_poster_log",
    "ims_superdesk_shop_poster_qr",
    "ims_superdesk_shop_poster_scan",
    "ims_superdesk_shop_postera_log",
    "ims_superdesk_shop_postera_qr",
    "ims_superdesk_shop_refund_address",
    "ims_superdesk_shop_sale_coupon_data",
    "ims_superdesk_shop_saler",
    "ims_superdesk_shop_sign_records",
    "ims_superdesk_shop_sign_user",
    "ims_superdesk_shop_sns_board_follow",
    "ims_superdesk_shop_sns_like",
    "ims_superdesk_shop_sns_manage",
    "ims_superdesk_shop_sns_member",
    "ims_superdesk_shop_sns_post",
    "ims_superdesk_shop_task_log",
    "ims_superdesk_shop_task_poster_qr",
    "ims_superdesk_shop_virtual_data",
];

foreach ($backup_table as $table_name) {

    echo 'mysqldump -uroot -proot@2016 ' . $backup_database . ' ' . $table_name . '>/mnv/mysql_single/'.$backup_datetime.'/' . $table_name . '.sql;';
//    echo '<br/>';
    echo PHP_EOL;
}