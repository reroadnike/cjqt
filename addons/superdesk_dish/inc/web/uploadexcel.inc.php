<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/11/17
 * Time: 10:17 AM
 */

global $_GPC, $_W;

if ($_GPC['leadExcel'] == "true") {
    $filename = $_FILES['inputExcel']['name'];
    $tmp_name = $_FILES['inputExcel']['tmp_name'];

    $flag = $this->checkUploadFileMIME($_FILES['inputExcel']);
    if ($flag == 0) {
        message('文件格式不对.');
    }

    if (empty($tmp_name)) {
        message('请选择要导入的Excel文件！');
    }

    $msg = $this->uploadFile($filename, $tmp_name, $_GPC);

    if ($msg == 1) {
        message('导入成功！', referer(), 'success');
    } else {
        message($msg, '', 'error');
    }
}