<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_img */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_img.class.php');
$wx_img = new wx_imgModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_img->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'uid' => $_GPC['uid'],// 
    'uname' => $_GPC['uname'],// 
    'keyword' => $_GPC['keyword'],// 
    'precisions' => $_GPC['precisions'],// 
    'text' => $_GPC['text'],// 简介
    'classid' => $_GPC['classid'],// 
    'classname' => $_GPC['classname'],// 
    'pic' => $_GPC['pic'],// 封面图片
    'showpic' => $_GPC['showpic'],// 图片是否显示封面
    'info' => $_GPC['info'],// 
    'url' => $_GPC['url'],// 图文外链地址
    'uptatetime' => $_GPC['uptatetime'],// 
    'click' => $_GPC['click'],// 
    'token' => $_GPC['token'],// 
    'title' => $_GPC['title'],// 
    'usort' => $_GPC['usort'],// 
    'longitude' => $_GPC['longitude'],// 
    'latitude' => $_GPC['latitude'],// 
    'type' => $_GPC['type'],// 
    'writer' => $_GPC['writer'],// 作者
    'texttype' => $_GPC['texttype'],// 文本类型
    'usorts' => $_GPC['usorts'],// 分类文章排列顺序
    'is_focus' => $_GPC['is_focus'],// 
    'keyworduuid' => $_GPC['keyworduuid'],// 
    'stauts' => $_GPC['stauts'],// 1:表示启用，0表示停用

        );
        $wx_img->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_img', array('op' => 'list')), 'success');


    }
    include $this->template('wx_img_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_img->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_img', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_img->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_img_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_img->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_img->delete($id);

    message('删除成功！', referer(), 'success');
}

