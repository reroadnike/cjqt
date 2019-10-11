<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/28/17
 * Time: 4:07 PM
 */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') { //会议室轮播图添加

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
    $img = $_GPC['img'];

    $item = $boardroom->getOne($_GPC['id']);

    if(!empty($item['carousel'])){
        $ser_infos   = iunserializer($item['carousel']);
    }

    $ser_infos[] = $img;

//    if (checksubmit('submit')) {
    $params = array(
        'carousel' => serialize($ser_infos),
    );

    $ret = $boardroom->saveOrUpdate($params, $id);

    die(json_encode($ret));

//    }

} elseif ($op == 'list') { //会议室轮播图

    $id = $_GPC['id'];

    $item = $boardroom->getOne($_GPC['id']);

    if(!empty($item)){
        $list = iunserializer($item['carousel']);
    }

//    var_dump($list);

    $url_api_boardroom_set_carousel_add = $this->createWebUrl('boardroom_set_carousel',array('op' => 'edit'));

    include $this->template('boardroom_set_carousel');

} elseif ($op == 'delete') { //删除会议室轮播图

    $id = intval($_GPC['id']);
    $key = intval($_GPC['key']);

    $item = $boardroom->getOne($_GPC['id']);

    $ser = iunserializer($item['carousel']);

    unset($ser[$key]);

    $params = array(
        'carousel' => iserializer($ser),
    );

    $ret = $boardroom->saveOrUpdate($params, $id);


    message('删除成功', $this->createWebUrl('boardroom_set_carousel', array('id' => $id , 'op' => 'list')));
    exit;
}


