<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/19/17
 * Time: 11:28 AM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_core&do=sync_virtualarchitecture_all
 */
global $_GPC, $_W;


include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
$_virtualarchitectureService = new VirtualarchitectureService();


$_virtualarchitectureService->syncAll();