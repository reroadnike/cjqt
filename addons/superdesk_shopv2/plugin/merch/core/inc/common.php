<?php

global $_W;

$routes = explode('.', $_W['routes']);

$GLOBALS['_W']['tab'] = (isset($routes[2]) ? $routes[2] : '');

$uniacid = intval($_GPC['__uniacid']);
$session = $_SESSION['__merch_uniacid'];

if (!empty($session)) {
    $uniacid = $session;
}


if ($_W['routes'] != 'merch.manage.login') {
    $session_key = '__merch_' . $uniacid . '_session';
    $session     = json_decode(base64_decode($_GPC[$session_key]), true);

    if (is_array($session)) {
        $account = pdo_fetch('select * from ' . tablename('superdesk_shop_merch_account') . ' where id=:id limit 1', array(':id' => $session['id']));
        if (!is_array($account) || ($session['hash'] != md5($account['pwd'] . $account['salt']))) {
            isetcookie($session_key, false, -100);
            header('location: ' . merchurl('login'));
            exit();
        }


        $GLOBALS['_W']['uniaccount'] = $account;
    } else {
        isetcookie($session_key, false, -100);
        header('location: ' . merchurl('login'));
        exit();
    }
}


$GLOBALS['_W']['uniacid']        = $uniacid;
$GLOBALS['_W']['merchid']        = $session['merchid'];
$GLOBALS['_W']['merchuid']       = $session['id'];
$GLOBALS['_W']['merchusername']  = $session['username'];
$GLOBALS['_W']['merchisfounder'] = $session['isfounder'];

$merch_user = pdo_fetch(
    'select u.*,g.groupname,g.goodschecked,g.commissionchecked,g.changepricechecked,g.finishchecked ' .
    ' from ' . tablename('superdesk_shop_merch_user') . ' u ' .
    ' left join ' . tablename('superdesk_shop_merch_group') . ' g on u.groupid=g.id ' .
    ' where u.id=:id limit 1',
    array(
        ':id' => $session['merchid']
    )
);

$GLOBALS['_W']['merch_user']     = $merch_user;
$GLOBALS['_W']['merch_username'] = $merch_user['merchname'];
$GLOBALS['_W']['accounttotal']   = $merch_user['accounttotal'];

unset($merch_user);

load()->func('tpl');

function merchUrl($do = '', $query = NULL, $full = false)
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

    return str_replace('./index.php', './superdesk_shopv2_merchant.php', wurl('site/entry', $query));
}

function mce($permtype = '', $item = NULL)
{
    $perm = plugin_run('merch::check_edit', $permtype, $item);
    return $perm;
}

function mcp($plugin = '')
{
    return true;
}

function mcv($permtypes = '')
{
    $perm = plugin_run('merch::check_perm', $permtypes);
    return $perm;
}

function mplog($type = '', $op = '')
{
    plugin_run('merch::log', $type, $op);
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