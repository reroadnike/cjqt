<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/26/18
 * Time: 2:12 PM
 */

$back_url = urldecode("https://bmt.superdesk.cn/app/index.php");
header('Location: ' . implode('', [
        $back_url,
        strpos($back_url, '?') ? '&' : '?',
        'i=' . $_GET['i'],
        '&c=' . $_GET['c'],
        '&a=' . $_GET['a'],
        '&code=' . $_GET['code'],
        '&state=' . $_GET['state'],
    ]));
