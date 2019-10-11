<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/13/17
 * Time: 4:50 PM
 */

global $_W, $_GPC;
if (empty($_FILES['files'])) {
    $ret['code'] = '203';
    $ret['msg'] = '请求成功';
    $ret['data'] = '';
} else {
    load()->func('file');
    $returnPath = file_upload($_FILES['files'], 'image');
    file_image_thumb(ATTACHMENT_ROOT . $returnPath['path'], ATTACHMENT_ROOT . $returnPath['path'], 300);
    $path = tomedia($returnPath['path']);
    $is = pdo_insert('superdesk_boardroom_4school_images', array('src' => $path));
    $id = pdo_insertid();

    if (empty($is)) {
        $ret['code'] = '202';
        $ret['msg'] = '上传出现错误';
        $ret['data'] = array('id' => 0);
    } else {
        $ret['code'] = '200';
        $ret['msg'] = '上传成功';
        $ret['data'] = array('id' => $id);
    }
}
echo json_encode($ret, JSON_UNESCAPED_UNICODE);
exit();