<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/13/17
 * Time: 5:15 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=shop_category_image_move
 */

global $_W, $_GPC;

$list = pdo_fetchall(
    ' select id,thumb from ' . tablename('superdesk_shop_category') .
    ' where thumb != "" and thumb not like "%shop_category%" and uniacid=:uniacid ',
    array(':uniacid' => $_W['uniacid'])
);

load()->func('file');
//file_move(ATTACHMENT_ROOT . 'images/shop_category/' . $list[0]['id'] . '.jpg', ATTACHMENT_ROOT . '/' . $list[0]['thumb']);
//file_move(ATTACHMENT_ROOT . '/' . $list[0]['thumb'], ATTACHMENT_ROOT . 'images/shop_category/' . $list[0]['id'] . '.jpg');
//print_r($list[0]);die;

$change_path_array = array();
foreach ($list as $v) {
    if (file_exists(ATTACHMENT_ROOT . '/' . $v['thumb'])) {
        $old_file_path = ATTACHMENT_ROOT . '/' . $v['thumb'];

        $path = ATTACHMENT_ROOT . 'images/shop_category/';
        if (!is_dir($path)) {
            mkdirs($path);
        }
        $new_path      = 'images/shop_category/' . $v['id'] . '.jpg';
        $new_file_path = ATTACHMENT_ROOT . $new_path;
        $remove_result = file_move($old_file_path, $new_file_path);

//        file_move($new_file_path, $old_file_path);
        if ($remove_result) {
            pdo_update('superdesk_shop_category', array('thumb' => $new_path), array('id' => $v['id']));

            $change_path_array[] = array(
                'old_path' => $old_file_path,
                'new_path' => $new_file_path
            );
        }

    }
}
include_once(IA_ROOT . '/framework/library/logs/LogsUtil.class.php');
LogsUtil::logging('info', json_encode($change_path_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 'shop_category');

show_json(1, $list);


////4.5  验证四级地址是否正确
//$jd_sdk->api_area_check_area();
