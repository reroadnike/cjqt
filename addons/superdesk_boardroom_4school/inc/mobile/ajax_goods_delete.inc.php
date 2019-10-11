<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 5:08 AM
 */

global $_GPC;
$delurl = $_GPC['pic'];
if (file_delete($delurl)) {
    echo 1;
} else {
    echo 0;
}