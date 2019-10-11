<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/05/15
 * Time: 15:09
 * http://192.168.1.124/smart_office_building/web/index.php?c=site&a=entry&m=superdesk_recovery&do=cc_superdesk_shop_goods_cc_sku
 */

global $_GPC, $_W;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
$key = $_W['uniacid'] . '_cc_superdesk_shop_category_check';

if ($op == 'list') {
    $_redis->del($key);

    include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
    $jd_sdk = new JDVIPOpenPlatformSDK();
    $jd_sdk->init_access_token();

    $list = pdo_fetchall(
        'select id,name as db_name,level,enabled,jd_vop_page_num,old_shop_cate_id from ' . tablename('superdesk_shop_category') .
        ' where uniacid=:uniacid '
        ,array(':uniacid'=>$_W['uniacid'])
    );

    include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
    $_productService = new ProductService();

    $i = 1;
    $result = $_productService->getCategorys($i,5000);

    //对比错误.
    $diff_array = array();
    $category_all = array();
    while(!empty($result['result'])){
        $category = $result['result']['categorys'];

        $category_all = array_merge($category_all,$category);
        $diff_result = getDiff($category,$list);

        $diff = $diff_result['diff_array'];
        $diff_array = array_merge($diff_array,$diff);

        $i++;
        $result = $_productService->getCategorys($i,5000);
    }


    //这是对比京东有而我们没有的
    //京东有而我们没有
    $jd_lack_array = array();
    $list_id_array = array_column($list,'id');
    foreach($category_all as $v){
        if(!in_array($v['catId'],$list_id_array)){
            $jd_lack_array[] = $v;
        }
    }
    //京东没有而我们有
    $shop_lack_array = array();
    $category_id = array_column($category_all,'catId');
    foreach($list as $v){
        if(!in_array($v['id'],$category_id)){
            $shop_lack_array[] = $v;
        }
    }
    $hh = 1;

    if(!empty($diff_array) || !empty($jd_lack_array) || !empty($shop_lack_array)){
        $_redis->set($key,json_encode(compact('diff_array','jd_lack_array','shop_lack_array')));
    }

    $diff_array = json_encode($diff_array);
    $jd_lack_array = json_encode($jd_lack_array);
    $shop_lack_array = json_encode($shop_lack_array);

    include $this->template('cc_superdesk_shop_category_check');

}else if($op == 'edit'){
    $redis_data = $_redis->get($key);
    $redis_data = json_decode($redis_data,true);
    $diff_array = $redis_data['diff_array'];
    $jd_lack_array = $redis_data['jd_lack_array'];
    $shop_lack_array = $redis_data['shop_lack_array'];

    //上面都是检查,下面就是开始更新了
    $can_change_level = $_GPC['level'];
    $can_change_name = $_GPC['name'];
    $can_change_jd_have = $_GPC['jd_have'];
    $can_change_shop_have = $_GPC['shop_have'];

    foreach($diff_array as $v){
        $update_data = array();
        if(!empty($can_change_level)){
            $update_data['level'] = $v['catClass'] + 1;
        }
        if(!empty($can_change_name)){
            $update_data['name'] = $v['name'];
        }
        if(!empty($can_change_level) || !empty($can_change_name)){
            pdo_update('superdesk_shop_category',$update_data,array('id'=>$v['catId'],'uniacid'=>$_W['uniacid']));
        }
    }

    if(!empty($can_change_jd_have)){
        foreach($jd_lack_array as $v){
            if($v['state'] == 1){
                $check = pdo_fetch('select id from '.tablename('superdesk_shop_category').' where id=:id',array(':id'=>$v['catId']));
                if(!empty($check)){
                    pdo_insert('superdesk_shop_category',
                        array(
                            'id'             => $v['catId'],
                            'uniacid'        => $_W['uniacid'],
                            'name'            => $v['name'],
                            'parentid'        => $v['parentId'],
                            'description'     => $v['name'],
                            'level'            => $v['catClass'] + 1,
                            'jd_vop_page_num' => $v['catId'],
                            'enabled'          =>0,
                            'is_jd'             => 1
                        )
                    );
                }
            }
        }
    }

    if(!empty($can_change_shop_have)){
        foreach($shop_lack_array as $v){
            pdo_update('superdesk_shop_category',array('enabled'=>0),array('id'=>$v['id'],'uniacid'=>$_W['uniacid']));
        }
    }

    $_redis->del($key);

    message('成功！', $this->createWebUrl('cc_superdesk_shop_category_check', array('op' => 'list')), 'success');

}

function getDiff($category,$list){
    global $_W;
    $diff_array = array();
    foreach($category as $v){
        foreach($list as &$l){
            $l['diff_level'] = 0;
            $l['diff_name'] = 0;
            if($v['catId'] == $l['id']){
                $l = array_merge($l,$v);
                $l['diff'] = '';
                if($v['catClass'] + 1 != $l['level']){
                    $l['diff_level'] = 1;
                    $diff_array[] = $l;
                }
                if($v['name'] != $l['db_name']){
                    $l['diff_name'] = 1;
                    $diff_array[] = $l;
                }
                //这里有个问题.就是假如京东这个分类已经没有了那么就无法更新那个分类
                //这里还有个问题,可能会覆盖掉自己添加分类
                if($l['is_jd'] == 0){
                    pdo_update('superdesk_shop_category',array('is_jd'=>1),array('id'=>$v['catId'],'uniacid'=>$_W['uniacid']));
                }
            }
        }
    }
    unset($l);

    return compact('diff_array');
}
