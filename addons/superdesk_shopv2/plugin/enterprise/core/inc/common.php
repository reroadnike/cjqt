<?php

global $_W;
$routes               = explode('.', $_W['routes']);
$GLOBALS['_W']['tab'] = (isset($routes[2]) ? $routes[2] : '');
$uniacid              = intval($_GPC['__uniacid']);
$session              = $_SESSION['__enterprise_uniacid'];

if (!empty($session)) {
    $uniacid = $session;
}


if ($_W['routes'] != 'enterprise.manage.login') {
    $session_key = '__enterprise_' . $uniacid . '_session';
    $session     = json_decode(base64_decode($_GPC[$session_key]), true);

    if (is_array($session)) {
        $account = pdo_fetch(
            'select * from ' . tablename('superdesk_shop_enterprise_account') . // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
            ' where id=:id limit 1',
            array(
                ':id' => $session['id']
            )
        );

        if (!is_array($account) || ($session['hash'] != md5($account['pwd'] . $account['salt']))) {
            isetcookie($session_key, false, -100);
            header('location: ' . enterpriseUrl('login'));
            exit();
        }


        $GLOBALS['_W']['uniaccount'] = $account;
    } else {
        isetcookie($session_key, false, -100);
        header('location: ' . enterpriseUrl('login'));
        exit();
    }
}


$GLOBALS['_W']['uniacid']              = $uniacid;
$GLOBALS['_W']['enterprise_id']        = $session['enterprise_id'];
$GLOBALS['_W']['enterprise_uid']       = $session['id'];
$GLOBALS['_W']['enterprise_username']  = $session['username'];
$GLOBALS['_W']['enterprise_isfounder'] = $session['isfounder'];
$enterprise_user                       = pdo_fetch('select * from ' . tablename('superdesk_shop_enterprise_user') . ' u where u.id=:id limit 1', array(':id' => $session['enterprise_id']));
$GLOBALS['_W']['enterprise_user']      = $enterprise_user;
$GLOBALS['_W']['enterprise_username']  = $enterprise_user['enterprise_name'];
$GLOBALS['_W']['accounttotal']         = $enterprise_user['accounttotal'];


unset($enterprise_user);

load()->func('tpl');

function enterpriseUrl($do = '', $query = NULL, $full = false)
{
    global $_W;
    global $_GPC;
    $dos      = explode('/', trim($do));
    $routes   = array();
    $routes[] = $dos[0];

    if (isset($dos[1])) {
        $routes[] = $dos[1];
    }


    if (isset($dos[2])) {
        $routes[] = $dos[2];
    }


    if (isset($dos[3])) {
        $routes[] = $dos[3];
    }


    $r = implode('.', $routes);

    if (!is_array($query)) {
        $query = array();
    }


    if (!empty($r)) {
        $query = array_merge(array('r' => $r), $query);
    }


    $query = array_merge(array('do' => 'web'), $query);
    $query = array_merge(array('m' => 'superdesk_shopv2'), $query);
    return str_replace('./index.php', './superdesk_shopv2_enterprise.php', wurl('site/entry', $query));
}

function mce($permtype = '', $item = NULL)
{
    $perm = plugin_run('enterprise::check_edit', $permtype, $item);
    return $perm;
}

function mcp($plugin = '')
{
    return true;
}

function mcv($permtypes = '')
{
    $perm = plugin_run('enterprise::check_perm', $permtypes);
    return $perm;
}

function mplog($type = '', $op = '')
{
    plugin_run('enterprise::log', $type, $op);
}

function mca($permtypes = '')
{
}

function mp($plugin = '')
{
    $plugin = p($plugin);

    if (!$plugin) {
        return false;
    }


    if (mcp($plugin)) {
        return $plugin;
    }


    return false;
}

function mcom($com = '')
{
    return true;
}

