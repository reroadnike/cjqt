<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 5/30/18
 * Time: 4:45 PM
 *
 * https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=test_superdesk_shopv2_core_model_notice_send_examine_create_notice
 */


//{
//    "openid": "oX8KYwkxwNW6qzHF4cF-tGxYTcPg",
//    "orderid": "1818",
//    "ordersn": "ME20180428032656772838",
//    "price": "418.95",
//    "username": "安先生",
//    "mobile": "13422832499"
//}


echo m('notice')->sendExamineCreateNotice(
    "oX8KYwkxwNW6qzHF4cF-tGxYTcPg","13422832499", /*采购经理信息*/
    "安先生",
    "ME20180716121845628820","342.30","12447");

