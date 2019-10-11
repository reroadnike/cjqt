<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/4/17
 * Time: 10:51 AM
 */

global $_GPC, $_W;

$title = "我的订单";
//$this->checkAuth();

/******************************************************* redis *********************************************************/
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
//$_redis = new RedisUtil();
//$_redis->hset('business_dongyuantang_' . 'api_push_patient_doc' . '_' . $_W['uniacid'], time(), $json_str);
/******************************************************* redis *********************************************************/

/***************************************************** socket_log ******************************************************/
include_once(IA_ROOT . '/framework/library/socket_log/slog.function.php');

//配置
slog(array(
    'host'                => '121.40.87.68',//websocket服务器地址，默认localhost
    'optimize'            => false,//是否显示利于优化的参数，如果运行时间，消耗内存等，默认为false
    'show_included_files' => false,//是否显示本次程序运行加载了哪些文件，默认为false
    'error_handler'       => false,//是否接管程序错误，将程序错误显示在console中，默认为false
    'force_client_ids'    => array(//日志强制记录到配置的client_id,默认为空,client_id必须在allow_client_ids中
        'client_01',
        //'client_02',
    ),
    'allow_client_ids'    => array(//限制允许读取日志的client_id，默认为空,表示所有人都可以获得日志。
        'client_01',
        //'client_02',
        //'client_03',
    ),
),'config');

//输出日志
//slog('hello world');  //一般日志
//slog('msg','log');  //一般日志
//slog('msg','error'); //错误日志
//slog('msg','info'); //信息日志
//slog('msg','warn'); //警告日志
//slog('msg','trace');// 输入日志同时会打出调用栈
//slog('msg','alert');//将日志以alert方式弹出
//slog('msg','log','color:red;font-size:20px;');//自定义日志的样式，第三个参数为css样式
/***************************************************** socket_log ******************************************************/

//include_once(MODULE_ROOT . '/model/patient_doc.class.php');
//$patient_doc = new patient_docModel();

$profile_on = 1;
$home_on = 0;

//$column = array('openid'=>$_W['openid']);
//$_patient_doc = $patient_doc->getOneByColumn($column);

$mc_profile = $this->getProfile();

slog(json_encode($mc_profile),'log');

include $this->template('profile');