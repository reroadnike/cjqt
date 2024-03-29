<?php

defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/superdesk_feedback/template/');


require_once IA_ROOT . '/addons/superdesk_shopv2/version.php';
require_once IA_ROOT . '/addons/superdesk_shopv2/defines.php';
require_once SUPERDESK_SHOPV2_INC . 'functions.php';

require_once IA_ROOT . '/addons/superdesk_feedback/model.php';

class superdesk_feedbackModuleSite extends WeModuleSite
{
    public $cur_version = '20140917';
    public $modulename = 'superdesk_feedback';

    public $_debug = '1'; //default:0
    public $_weixin = '1'; //default:1

    public $_appid = '';
    public $_appsecret = '';
    public $_accountlevel = '';

    public $_uniacid = '';
    public $_fromuser = '';
    public $_nickname = '';
    public $_headimgurl = '';

    public $_auth2_openid = '';
    public $_auth2_nickname = '';
    public $_auth2_headimgurl = '';

    function __construct()
    {
        global $_W, $_GPC;

        $this->_fromuser = $_W['openid'];//debug

        if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
            $this->_fromuser = 'debug';
        }

        $this->_uniacid = $_W['uniacid'];
        $account        = account_fetch($this->_uniacid);

        $this->_auth2_openid     = 'auth2_openid_' . $_W['uniacid'];
        $this->_auth2_nickname   = 'auth2_nickname_' . $_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];


        $this->_appid        = '';
        $this->_appsecret    = '';
        $this->_accountlevel = $account['level']; //是否为高级号

        if ($this->_accountlevel == 4) {
            $this->_appid     = $account['key'];
            $this->_appsecret = $account['secret'];
        }
    }



    function pagination($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => ''))
    {
        global $_W;
        $pdata = array(
            'tcount'  => 0,
            'tpage'   => 0,
            'cindex'  => 0,
            'findex'  => 0,
            'pindex'  => 0,
            'nindex'  => 0,
            'lindex'  => 0,
            'options' => ''
        );
        if ($context['ajaxcallback']) {
            $context['isajax'] = true;
        }

        $pdata['tcount'] = $tcount;
        $pdata['tpage']  = ceil($tcount / $psize);
        if ($pdata['tpage'] <= 1) {
            return '';
        }
        $cindex          = $pindex;
        $cindex          = min($cindex, $pdata['tpage']);
        $cindex          = max($cindex, 1);
        $pdata['cindex'] = $cindex;
        $pdata['findex'] = 1;
        $pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
        $pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
        $pdata['lindex'] = $pdata['tpage'];

        if ($context['isajax']) {
            if (!$url) {
                $url = $_W['script_name'] . '?' . http_build_query($_GET);
            }
            $pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
        } else {
            if ($url) {
                $pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
                $pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
                $pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
                $pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
            } else {
                $_GET['page'] = $pdata['findex'];
                $pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['pindex'];
                $pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['nindex'];
                $pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['lindex'];
                $pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
            }
        }

        $html = '<div class="pagination">';
        if ($pdata['cindex'] > 1) {
            $html .= "<div class=\"left\"><a {$pdata['paa']}>上一页</a></div>";
        } else {
            $html .= "<div class=\"left disabled\"><a href=\"###\">上一页</a></div>";
        }

        $html .= "<div class=\"allpage\"><div class=\"currentpage\"> <span class=\"ipage\">{$pindex}</span> / <span class=\"cpage\">" . $pdata['tpage'] . "</span></div></div>";

        if ($pdata['cindex'] < $pdata['tpage']) {
            $html .= "<div class=\"right\"><a {$pdata['naa']}>下一页</a></div>";
        } else {
            $html .= "<div class=\"right disabled\"><a {$pdata['naa']}>下一页</a></div>";
        }
        $html .= '<div class="clr"></div></div>';
        return $html;
    }



    function authorization()
    {
        $host = get_domain();
        return base64_encode($host);
    }

//    function code_compare($a, $b)
//    {
//        if ($this->_debug == 1) {
//            if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
//                return true;
//            }
//        }
//        if ($a != $b) {
//            message(base64_decode("5a+55LiN6LW377yM5oKo5L2/55So55qE57O757uf5piv5pyJ6Zeu6aKY"));
//        }
//    }

    function isWeixin()
    {
        if ($this->_weixin == 1) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            if (!strpos($userAgent, 'MicroMessenger')) {
                include $this->template('s404');
                exit();
            }
        }
    }

    //auth2
    public function toAuthUrl($url)
    {
        global $_W;
        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
        header("location:$oauth2_code");
    }

    public function oauth2($authurl)
    {
        global $_GPC, $_W;
        load()->func('communication');
        $state = $_GPC['state']; //1为关注用户, 0为未关注用户
        $code  = $_GPC['code'];

        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->_appid . "&secret=" . $this->_appsecret . "&code=" . $code . "&grant_type=authorization_code";

        $content = ihttp_get($oauth2_code);

        $token = @json_decode($content['content'], true);

        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit;
        }
        $from_user = $token['openid'];

        if ($this->_accountlevel != 2) { //普通号
            $authkey = intval($_GPC['authkey']);
            if ($authkey == 0) {
                $url         = $authurl;
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
            }
        } else {
            //再次查询是否为关注用户

            $follow = pdo_fetchcolumn("SELECT follow FROM " . tablename('mc_mapping_fans') . " WHERE openid = :openid AND acid = :acid", array(':openid' => $from_user, ':acid' => $_W['uniacid']));


            if ($follow == 1) { //关注用户直接获取信息
                $state = 1;
            } else { //未关注用户跳转到授权页
                $url         = $authurl;
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
            }
        }

        //未关注用户和关注用户取全局access_token值的方式不一样
        if ($state == 1) {
            $oauth2_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->_appid . "&secret=" . $this->_appsecret . "";
            $content    = ihttp_get($oauth2_url);
            $token_all  = @json_decode($content['content'], true);
            if (empty($token_all) || !is_array($token_all) || empty($token_all['access_token'])) {
                echo '<h1>获取微信公众号授权失败[无法取得access_token], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                exit;
            }
            $access_token = $token_all['access_token'];
            $oauth2_url   = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $from_user . "&lang=zh_CN";
        } else {
            $access_token = $token['access_token'];
            $oauth2_url   = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $from_user . "&lang=zh_CN";
        }

        //使用全局ACCESS_TOKEN获取OpenID的详细信息
        $content = ihttp_get($oauth2_url);
        $info    = @json_decode($content['content'], true);
        if (empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname'])) {
            echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>' . 'state:' . $state . 'nickname' . 'uniacid:';
            exit;
        }
        $headimgurl = $info['headimgurl'];
        $nickname   = $info['nickname'];
        //设置cookie信息

        setcookie($this->_auth2_headimgurl, $headimgurl, time() + 3600 * 24);
        setcookie($this->_auth2_nickname, $nickname, time() + 3600 * 24);
        setcookie($this->_auth2_openid, $from_user, time() + 3600 * 24);
        return $info;
    }

    public function showMessage($msg, $status = 0)
    {
        $result = array('message' => $msg, 'status' => $status);
        die(json_encode($result));
    }

    public function message($error, $url = '', $errno = -1)
    {
        $data          = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;

        die(json_encode($data));
    }
}