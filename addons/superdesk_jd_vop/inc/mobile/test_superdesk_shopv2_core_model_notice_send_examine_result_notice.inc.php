<?php
/**
 * Created by PhpStorm.
 * User=> linjinyu
 * Date=> 4/28/18
 * Time=> 3=>17 AM
 *
 * https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=test_superdesk_shopv2_core_model_notice
 */


echo m('notice')->sendExamineResultNotice($examine['openid'], 2/* reject */, $member['realname'], $examine['price'],$examine['createtime'],$examine['mobile'],$orderid);