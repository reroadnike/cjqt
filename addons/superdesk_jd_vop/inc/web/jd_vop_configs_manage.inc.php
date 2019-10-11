<?php
/**
 *
 * 京东商品池策略管理
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/18
 * Time: 10:18
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=configs
 */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/configs.class.php');
$configs = new configsModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $configs->getOne($_GPC['id']);

    if (checksubmit('submit')) {

        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

        $params = array(
            'client_id'      => $_GPC['client_id'],// 对应接口参数client_id
            'client_secret' => $_GPC['client_secret'],// 对应接口参数client_secret
            'username'       => $_GPC['username'],// 对应接口参数username
            'password'       => $_GPC['password'],// 对应接口参数password
            'title'          => $_GPC['title'],// 标题
        );

        $list = $configs->queryAll();
        if($list['total'] == 0){
            $params['is_default'] = 1;
        }

        $new_id = $configs->saveOrUpdate($params, $id);

        if($list['total'] == 0 || (!empty($id) && $item['is_default'] == 1)){
            $item = $configs->getOne($new_id);
            setRedisOfJdConfigs($item);
        }

        message('成功！', $this->createWebUrl('jd_vop_configs_manage', array('op' => 'list')), 'success');


    }
    include $this->template('configs_edit');

} elseif ($op == 'list') {


    $page      = $_GPC['page'];
    $page_size = 20;

    $result    = $configs->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('configs_list');

} elseif ($op == 'is_default') {

    $is_default = $_GPC['is_default'];

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $configs->getOne($_GPC['id']);

    if (empty($item)) {
        die('抱歉，该信息不存在或是已经被删除！');
    }

    if($is_default == 1){
        $configs->updateByColumn(array('is_default' => 0),array('is_default' => 1));
    }

    setRedisOfJdConfigs($item,$is_default);

    $configs->update(array('is_default' => $is_default), $id);

    die('success');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $configs->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $configs->delete($id);

    message('删除成功！', referer(), 'success');
} elseif ($op == 'deleted') {

    $deleted = $_GPC['deleted'];

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $configs->getOne($_GPC['id']);

    if (empty($item)) {
        die('抱歉，该信息不存在或是已经被删除！');
    }

    $configs->update(array(
        'deleted' => $deleted
    ), $id);

    die('success');
}

function setRedisOfJdConfigs($item,$is_default=1){
    global $_W;

    include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
    $_redis = new RedisUtil();

    $key     = 'superdesk_jd_vop_' . 'web_jd_vop_configs_manage' . ':' . $_W['uniacid'];

    $_redis->del($key);
    if($is_default == 1){
        $_redis->set($key, json_encode($item));
    }
}

