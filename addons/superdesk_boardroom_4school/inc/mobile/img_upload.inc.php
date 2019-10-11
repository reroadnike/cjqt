<?php
/**
 * 处理图片上传;
 * TODO 此处的图片地址为全路径，后期可能要修改
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/13/17
 * Time: 4:48 PM
 */
global $_W, $_GPC;
if (!empty($_GPC['pic'])) {
    preg_match("/data\:image\/([a-z]{1,5})\;base64\,(.*)/", $_GPC['pic'], $r);
    $imgname = 'bl' . time() . rand(10000, 99999) . '.' . $r[1];
    $path = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/images/';
    $f = fopen($path . $imgname, 'w+');
    fwrite($f, base64_decode($r[2]));
    fclose($f);
    $imgurl = $_W['attachurl'] . 'images/' . $imgname;
    $is = pdo_insert('superdesk_boardroom_4school_images', array('src' => $imgurl));
    $id = pdo_insertid();
    if (empty($is)) {
        exit(json_encode(array(
            'errCode' => 1,
            'message' => '上传出现错误',
            'data' => array('id' => $_GPC['t'], 'picId' => $id)
        )));
    } else {
        exit(json_encode(array(
            'errCode' => 0,
            'message' => '上传成功',
            'data' => array('id' => $_GPC['id'], 'picId' => $id)
        )));
    }
}