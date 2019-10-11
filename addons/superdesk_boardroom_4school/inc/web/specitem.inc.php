<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 5:02 AM
 */

global $_GPC;
load()->func('tpl');
$spec = array(
    "id" => $_GPC['specid']
);
$specitem = array(
    "id" => random(32),
    "title" => $_GPC['title'],
    "show" => 1
);
include $this->template('spec_item');