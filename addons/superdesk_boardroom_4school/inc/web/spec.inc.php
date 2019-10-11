<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 5:00 AM
 */

global $_GPC;
$spec = array(
    "id" => random(32),
    "title" => $_GPC['title']
);
include $this->template('spec');