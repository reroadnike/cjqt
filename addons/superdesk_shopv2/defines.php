<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
define('SUPERDESK_SHOPV2_DEBUG', false);

define('SUPERDESK_SHOPV2_DEBUG_OPENID', 'oX8KYwkxwNW6qzHF4cF-tGxYTcPg');
//'oX8KYwkxwNW6qzHF4cF-tGxYTcPg'// 超级前台服务 林进雨 13422832499

!defined('SUPERDESK_SHOPV2_MODULE_NAME') && define('SUPERDESK_SHOPV2_MODULE_NAME', 'superdesk_shopv2');// 1 超级前台 2 福利商城

!defined('SUPERDESK_SHOPV2_MODE_USER') && define('SUPERDESK_SHOPV2_MODE_USER', 1);// 1 超级前台 2 福利商城

!defined('SUPERDESK_SHOPV2_IS_BUILD_WINDOW') && define('SUPERDESK_SHOPV2_IS_BUILD_WINDOW', 1);// 0 没有绑定楼宇之窗 1 关联绑定楼宇之窗

!defined('SUPERDESK_SHOPV2_SOCKET_LOG_TARGET') && define('SUPERDESK_SHOPV2_SOCKET_LOG_TARGET', SUPERDESK_SHOPV2_MODE_USER == 1 ? 'superdesk' : 'welfare');// 1 超级前台 2 福利商城

//define('SUPERDESK_SHOPV2_SOCKET_LOG_TARGET', 'local');

!defined('SUPERDESK_SHOPV2_PC_URL') && define('SUPERDESK_SHOPV2_PC_URL', SUPERDESK_SHOPV2_MODE_USER == 1 ? 'https://b.superdesk.cn' : 'https://f.superdesk.cn');// 1 超级前台 2 福利商城

//楼宇之窗切换项目地址
//http://wx.palmnest.com/ -- 测试
//https://superdesk.avic-s.com/ --正式
!defined('SUPERDESK_SHOPV2_BUILD_WINDOW_URL') && define('SUPERDESK_SHOPV2_BUILD_WINDOW_URL', 'https://superdesk.avic-s.com/');

!defined('SUPERDESK_SHOPV2_MODE_SEARCH') && define('SUPERDESK_SHOPV2_MODE_SEARCH', 'elasticsearch');// elasticsearch mysql

!defined('SUPERDESK_SHOPV2_WECHAT_IS_NOTICE') && define('SUPERDESK_SHOPV2_WECHAT_IS_NOTICE', true);

!defined('SUPERDESK_SHOPV2_JD_VOP_IS_CALL_API') && define('SUPERDESK_SHOPV2_JD_VOP_IS_CALL_API', true);

!defined('SUPERDESK_SHOPV2_JD_VOP_MERCHID') && define('SUPERDESK_SHOPV2_JD_VOP_MERCHID', 8);

!defined('SUPERDESK_SHOPV2_PATH') && define('SUPERDESK_SHOPV2_PATH', IA_ROOT . '/addons/superdesk_shopv2/');

!defined('SUPERDESK_SHOPV2_CORE') && define('SUPERDESK_SHOPV2_CORE', SUPERDESK_SHOPV2_PATH . 'core/');

!defined('SUPERDESK_SHOPV2_DATA') && define('SUPERDESK_SHOPV2_DATA', SUPERDESK_SHOPV2_PATH . 'data/');

!defined('SUPERDESK_SHOPV2_VENDOR') && define('SUPERDESK_SHOPV2_VENDOR', SUPERDESK_SHOPV2_PATH . 'vendor/');

!defined('SUPERDESK_SHOPV2_CORE_WEB') && define('SUPERDESK_SHOPV2_CORE_WEB', SUPERDESK_SHOPV2_CORE . 'web/');

!defined('SUPERDESK_SHOPV2_CORE_MOBILE') && define('SUPERDESK_SHOPV2_CORE_MOBILE', SUPERDESK_SHOPV2_CORE . 'mobile/');

!defined('SUPERDESK_SHOPV2_CORE_SYSTEM') && define('SUPERDESK_SHOPV2_CORE_SYSTEM', SUPERDESK_SHOPV2_CORE . 'system/');

!defined('SUPERDESK_SHOPV2_PLUGIN') && define('SUPERDESK_SHOPV2_PLUGIN', SUPERDESK_SHOPV2_PATH . 'plugin/');

!defined('SUPERDESK_SHOPV2_PROCESSOR') && define('SUPERDESK_SHOPV2_PROCESSOR', SUPERDESK_SHOPV2_CORE . 'processor/');

!defined('SUPERDESK_SHOPV2_INC') && define('SUPERDESK_SHOPV2_INC', SUPERDESK_SHOPV2_CORE . 'inc/');

!defined('SUPERDESK_SHOPV2_URL') && define('SUPERDESK_SHOPV2_URL', $_W['siteroot'] . 'addons/superdesk_shopv2/');

!defined('SUPERDESK_SHOPV2_TASK_URL') && define('SUPERDESK_SHOPV2_TASK_URL', $_W['siteroot'] . 'addons/superdesk_shopv2/core/task/');

!defined('SUPERDESK_SHOPV2_LOCAL') && define('SUPERDESK_SHOPV2_LOCAL', '../addons/superdesk_shopv2/');

!defined('SUPERDESK_SHOPV2_STATIC') && define('SUPERDESK_SHOPV2_STATIC', SUPERDESK_SHOPV2_URL . 'static/');

!defined('SUPERDESK_SHOPV2_PREFIX') && define('SUPERDESK_SHOPV2_PREFIX', 'superdesk_shop_');

!defined('SUPERDESK_SHOPV2_REFRESH') && define('SUPERDESK_SHOPV2_REFRESH', false);

define('SUPERDESK_SHOPV2_PLACEHOLDER', $_W['siteroot'] . 'addons/superdesk_shopv2/static/images/placeholder.png');
