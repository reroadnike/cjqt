<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/2/17
 * Time: 4:53 AM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=15&c=entry&m=superdesk_boardroom_4school&do=test_superdesk_userinfo
 */

global $_GPC, $_W;


//$userMobile = $_GPC['userMobile'];
//$userMobile = "13422832499";
//if(empty($userMobile)){
//    die("请在中航超级前台公众号中打开");
//}

$superdesk_user_info = $this->superdesk_core_user_mobile($userMobile);

var_dump($superdesk_user_info);