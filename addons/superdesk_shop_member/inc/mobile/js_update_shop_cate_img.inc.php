<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/8/18
 * Time: 8:00 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shop_member&do=js_update_shop_cate_img
 */

//advimg
//advurl
//jd_vop_page_num
//$advimg = 'http://www.avic-s.com/memberShop-admin/images/pic/1504237696531.jpg';

global $_W, $_GPC;


$_overwrite      = false;
$thumb          = $_GPC['thumb'];
$jd_vop_page_num = intval($_GPC['page_num']);
$_overwrite      = $_GPC['overwrite'];

if(empty($jd_vop_page_num)){
    show_json(0,'page_num is null');
}

$where = array(
    'jd_vop_page_num' => $_GPC['page_num']
);




include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
$_categoryModel = new categoryModel();
$_category = $_categoryModel->getOneByColumn($where);



if($_category){

//    print_r($_category);
//    Array
//    (
//        [id] => 4837
//        [uniacid] => 16
//        [name] => 办公文具
//        [thumb] =>
//        [parentid] => 729
//        [isrecommand] => 0
//        [description] => 办公文具
//        [displayorder] => 0
//        [enabled] => 1
//        [ishome] => 0
//        [advimg] =>
//        [advurl] =>
//        [level] => 2
//        [jd_vop_page_num] => 12254565
//        [fiscal_code] =>
//    )
    if($_category['jd_vop_page_num'] == $jd_vop_page_num) {

        if($_overwrite || empty($_category['thumb'])){

            //$ext       = '.jpg';
            //$file_name = time() . $ext;

            $file_name = basename($thumb);


            $uniacid   = intval($_W['uniacid']);
            $path      = "images/{$uniacid}/" . date('Y/00');// 'Y/m'

            $save_to   = ATTACHMENT_ROOT . $path . '/' . $file_name;

            if (!file_exists(dirname($save_to))) {
                mkdir(dirname($save_to), 0777, true);
            }

            $in  = fopen($thumb, "rb");
            $out = fopen($save_to, "wb");

            while ($chunk = fread($in, 8192)) {
                fwrite($out, $chunk, 8192);
            }

            fclose($in);
            fclose($out);

            $update = array(
                'thumb' => $path . '/' . $file_name
            );

            $_categoryModel->saveOrUpdateByColumn($update , $where);

            show_json(1,"success");
        }

        show_json(0,"thumb Already");

    }
}else{

    show_json(0,"page num is no exist");
}

