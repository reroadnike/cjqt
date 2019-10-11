<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/22/17
 * Time: 6:31 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_00
 */


global $_W, $_GPC;


include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_00);

if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
}

echo "SUCCESS";