<?php
/**
 * 京东计划任务
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/22
 * Time: 16:05
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=task_cron
 */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/task_cron.class.php');
$task_cron = new task_cronModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $task_cron->getOne($_GPC['name']);

    if (checksubmit('submit')) {

        $name   = isset($_GPC['name']) ? empty($_GPC['name']) ? "" : $_GPC['name'] : "";
        $params = array(
            'name'   => $_GPC['name'],//
//            'orde'   => isset($_GPC['orde']) ? $_GPC['orde'] : 0,//
            'file'   => $_GPC['file'],//
            'no'     => $_GPC['no'],//
            'desc'   => $_GPC['desc'],//
            'freq'   => $_GPC['freq'],//
            'lastdo' => $_GPC['lastdo'],//
            'log'    => $_GPC['log'],//

        );

        $task_cron->saveOrUpdate($params, $name);

        message('成功！', $this->createWebUrl('task_cron', array('op' => 'list')), 'success');


    }
    include $this->template('task_cron_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['order'])) {
        foreach ($_GPC['order'] as $key => $value) {

            $params = array('orde' => $value);

            $task_cron->update($params, $key);
        }

        message('运行顺序 更新成功！', $this->createWebUrl('task_cron', array('op' => 'list')), 'success');
    }

    $page      = $_GPC['page'];
    $page_size = 50;

    $result    = $task_cron->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('task_cron_list');

} elseif ($op == 'uninst') {

    $name = isset($_GPC['name']) ? empty($_GPC['name']) ? "" : $_GPC['name'] : "";

    $item = $task_cron->getOne($name);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被卸载！');
    }

    $task_cron->delete($name);

    message('卸载任务 成功！', referer(), 'success');

} elseif ($op == 'dis') {

    $name = isset($_GPC['name']) ? empty($_GPC['name']) ? "" : $_GPC['name'] : "";

    $item = $task_cron->getOne($name);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被卸载！');
    }

    $params = array('no' => 1);

    $task_cron->saveOrUpdate($params, $name);

    message('忽略任务 成功！', $this->createWebUrl('task_cron', array('op' => 'list')), 'success');

} elseif ($op == 'act') {

    $name = isset($_GPC['name']) ? empty($_GPC['name']) ? "" : $_GPC['name'] : "";

    $item = $task_cron->getOne($name);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被卸载！');
    }

    $params = array('no' => 0);

    $task_cron->saveOrUpdate($params, $name);

    message('激活任务 成功！', $this->createWebUrl('task_cron', array('op' => 'list')), 'success');

} elseif ($op == 'run') {

    message('运行任务 成功！', $this->createWebUrl('task_cron', array('op' => 'list')), 'success');
}

