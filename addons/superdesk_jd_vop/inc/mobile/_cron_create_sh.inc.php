<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/25/17
 * Time: 9:06 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_create_sh
 */

global $_W, $_GPC;

$loop_ = array();
for ($i = 0; $i < 60; $i+=5) {
    $loop_[] = $i;
}

include $this->template('_cron_create_sh');