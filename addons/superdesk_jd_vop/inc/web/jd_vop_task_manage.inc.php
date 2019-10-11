<?php
/**
 * 京东任务管理(弃用unSafe)
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/1/17
 * Time: 3:33 PM
 */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/lib/crontab.class.php');

$_DEBUG = true;

$op = !empty($_GPC['op']) ? $_GPC['op'] : '';

if ($op == 'active') {
    $cron   = new Crontab();
    $active = $cron->listJobs();
    die(json_encode($active));

} elseif ($op == 'add') {

    $cron = new Crontab();

    if (isset($_GPC['minute']) && $_GPC['minute'] != '') {
        $cron->onMinute($_GPC['minute']);
    }

    if (isset($_GPC['hour']) && $_GPC['hour'] != '') {
        $cron->onHour($_GPC['hour']);
    }

    if (isset($_GPC['month']) && $_GPC['month'] != '') {
        $cron->onMonth($_GPC['month']);
    }

    if (isset($_GPC['dayweek']) && $_GPC['dayweek'] != '') {
        $cron->onDayOfWeek($_GPC['dayweek']);
    }

    if (isset($_GPC['daymonth']) && $_GPC['daymonth'] != '') {
        $cron->onDayOfMonth($_GPC['daymonth']);
    }

    if (isset($_GPC['command']) && $_GPC['command'] != '') {
        $cron->doJob($_GPC['command']);
    }

    if ($cron->activate()) {
        die(json_encode($cron));
    } else {
        echo FALSE;
        exit();
    }

} elseif ($op == 'deleteall') {

    $cron = new Crontab();
    $cron->deleteAllJobs();

} elseif ($op == 'deletejob') {

    $cron = new Crontab();
    $cron->deleteJob($_GPC['jobid']);

} else {
    include $this->template('jd_vop_task_manage');
}

