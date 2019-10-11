<?php
defined('IN_IA') or exit('Access Denied');
session_start();
include 'model/common.func.php';

/**
 * Class Superdesk_boardroomModuleSite
 */
class Superdesk_boardroomModuleSite extends WeModuleSite
{

    public $settings;

    public function __construct()
    {
        global $_W;

        $sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
        $settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => 'superdesk_boardroom'));
        $this->settings = iunserializer($settings);

//        $this->jsonData = json_decode(trim(file_get_contents('php://input')), true);

        // DEBUG 超级前台 openid
//        $_W['openid'] = "oX8KYwkxwNW6qzHF4cF-tGxYTcPg";

    }

    public function getMenus()
    {
        global $_W;

        /*
        <entry title="预约服务订单" do="boardroom_appointment" state="" direct="false"/>
        <entry title="附加服务订单" do="order" state="" direct="false"/>

        <entry title="附加服务分类" do="category" state="" direct="false"/>
        <entry title="附加服务管理" do="goods" state="" direct="false"/>

        <entry title="会议室管理" do="boardroom_manage" state="" direct="false"/>
        <entry title="设备管理" do="boardroom_equipment" state="" direct="false"/>

        <entry title="幻灯片管理" do="adv" state="" direct="false"/>
        <entry title="维权与告警" do="notice" state="" direct="false"/>

        <entry title="物流管理" do="express" state="" direct="false"/>
        <entry title="配送方式" do="dispatch" state="" direct="false"/>
        */
        return array(
            array('title' => '预约服务订单', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('boardroom_appointment')),
            array('title' => '附加服务订单', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('order')),

            array('title' => '附加服务分类', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('category')),
            array('title' => '附加服务管理', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('goods')),

            array('title' => '会议室管理', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('boardroom_manage')),
            array('title' => '设备管理', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('boardroom_equipment')),

            array('title' => '幻灯片管理', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('adv')),
            array('title' => '维权与告警', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('notice')),

            array('title' => '物流管理', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('express')),
            array('title' => '配送方式', 'icon' => 'fa fa-puzzle-piece', 'url' => $this->createWebUrl('dispatch')),
        );
    }

    function mobile_pagination($total, $pageIndex, $pageSize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => ''))
    {
        global $_W;
        $pdata = array(
            'tcount' => 0,
            'tpage' => 0,
            'cindex' => 0,
            'findex' => 0,
            'pindex' => 0,
            'nindex' => 0,
            'lindex' => 0,
            'options' => ''
        );
        if ($context['ajaxcallback']) {
            $context['isajax'] = true;
        }

        $pdata['tcount'] = $total;
        $pdata['tpage'] = (empty($pageSize) || $pageSize < 0) ? 1 : ceil($total / $pageSize);
        if ($pdata['tpage'] <= 1) {
            return '';
        }
        $cindex = $pageIndex;
        $cindex = min($cindex, $pdata['tpage']);
        $cindex = max($cindex, 1);
        $pdata['cindex'] = $cindex;
        $pdata['findex'] = 1;
        $pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
        $pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
        $pdata['lindex'] = $pdata['tpage'];

        if ($context['isajax']) {
            if (!$url) {
                $url = $_W['script_name'] . '?' . http_build_query($_GET);
            }
            $pdata['faa'] = 'href="javascript:;" page="' . $pdata['findex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', this);return false;"' : '');
            $pdata['paa'] = 'href="javascript:;" page="' . $pdata['pindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', this);return false;"' : '');
            $pdata['naa'] = 'href="javascript:;" page="' . $pdata['nindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', this);return false;"' : '');
            $pdata['laa'] = 'href="javascript:;" page="' . $pdata['lindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', this);return false;"' : '');
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

        $html = '<div class="row">';
        if ($pdata['cindex'] >= 1) {
            $html .= "<div class='col-xs-3 pagination'><a {$pdata['faa']} class=\"pager-nav\">首条</a></div>";
            $html .= "<div class='col-xs-3 pagination'><a {$pdata['paa']} class=\"pager-nav\">&laquo;上一条</a></div>";
        }
        if (!$context['before'] && $context['before'] != 0) {
            $context['before'] = 5;
        }
        if (!$context['after'] && $context['after'] != 0) {
            $context['after'] = 4;
        }

        if ($context['after'] != 0 && $context['before'] != 0) {
            $range = array();
            $range['start'] = max(1, $pdata['cindex'] - $context['before']);
            $range['end'] = min($pdata['tpage'], $pdata['cindex'] + $context['after']);
            if ($range['end'] - $range['start'] < $context['before'] + $context['after']) {
                $range['end'] = min($pdata['tpage'], $range['start'] + $context['before'] + $context['after']);
                $range['start'] = max(1, $range['end'] - $context['before'] - $context['after']);
            }
            for ($i = $range['start']; $i <= $range['end']; $i++) {
                if ($context['isajax']) {
                    $aa = 'href="javascript:;" page="' . $i . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $i . '\', this);return false;"' : '');
                } else {
                    if ($url) {
                        $aa = 'href="?' . str_replace('*', $i, $url) . '"';
                    } else {
                        $_GET['page'] = $i;
                        $aa = 'href="?' . http_build_query($_GET) . '"';
                    }
                }
                $html .= ($i == $pdata['cindex'] ? '<li class="active"><a href="javascript:;">' . $i . '</a></li>' : "<li><a {$aa}>" . $i . '</a></li>');
            }
        }

        if ($pdata['cindex'] <= $pdata['tpage']) {
            $html .= "<div class='col-xs-3 pagination'><a {$pdata['naa']} class=\"pager-nav\">下一条&raquo;</a></div>";
            $html .= "<div class='col-xs-3 pagination'><a {$pdata['laa']} class=\"pager-nav\">尾条</a></div>";
        }
        $html .= '</div>';
        return $html;
    }

    public function dataEchoH_i($time)
    {

//        'Y-m-d H:i:s'
        $str = date('H:i', $time);
        return $str;
    }

    public function dateWeekEnd($which_day_of_the_week, $time)
    {
        if (is_numeric($which_day_of_the_week)) {
            $add_day = 6 - $which_day_of_the_week;// 如果6,为星期日为第一天,如果7,为星期一为第一天
            $end_date = date('Y-m-d', strtotime("+" . $add_day . " day", $time));
            return $end_date;
        }
    }

    public function getWeekTableData($target)
    {
        //echo date('N',time());echo "<br>";
        //echo date('w');echo "<br>";

        //date(); y年m月d日 w周(0为周日,1为周一)
        //strtotime("+1 week", time()); 下周
        //strtotime("-1 week", time()); 上周


        $end_date = $this->dateWeekEnd(date('N', $target), $target);
        $start_date = date('Y-m-d', strtotime("$end_date -6 day"));

//        $dayOfWeek4Sunday = array('（日）', '（一）', '（二）', '（三）', '（四）', '（五）', '（六）');
        $dayOfWeek4Sunday = array('日', '一', '二', '三', '四', '五', '六');
//        $dayOfWeek4Monday = array('（一）', '（二）', '（三）', '（四）', '（五）', '（六）', '（日）',);

        $day_time = array();

        $day_time[] = $start_date;
        for ($i = 1; $i < 6; $i++) {
            $day_time[] = date('Y-m-d', strtotime("$start_date +$i day"));
        }
        $day_time[] = $end_date;

        $table = array();
//        $table['header'] = $dayOfWeek4Sunday;
//        $table['day_time'] = $day_time;

        foreach ($day_time as $index => $item) {
            $temp = array();
            $temp['header'] = $dayOfWeek4Sunday[$index];
            $temp['day_time'] = date("m-d", strtotime($day_time[$index]));
            $temp['day'] = date("Y-m-d", strtotime($day_time[$index]));

            $table[] = $temp;
        }
        return $table;
    }

    public function getWeekTableHtml($table_head, $table_body)
    {

        $curr = date('w');
        $html = "";
        ob_start();
        include $this->template('____week_table');
        $html = ob_get_contents();
        ob_clean();
        return $html;

    }


    public function init_boardroom_situation($boardroom_id,$Ymd){
        global $_GPC, $_W;

        $now = time();


        //echo date('Y-m-d H:i:s', $now);

        //'timestamp'
        //'enable'
        //'lable'

        //'Y-m-d H:i:s'



        $_init_am_start = strtotime($Ymd . " 00:00:00");
        $_init_am_end = strtotime($Ymd . " 11:59:59");

        $_init_pm_start = strtotime($Ymd . " 12:00:00");
        $_init_pm_end = strtotime($Ymd . " 23:59:59");

        //$_init_pm_end = strtotime("+1 seconds" ,strtotime($Ymd . " 23:59:59"));

        $time_am = array();
        $time_pm = array();

        $time_am[] = $_init_am_start;
        $time_pm[] = $_init_pm_start;

        $result_am = array();
        $result_pm = array();

        $i_am = 0;

        while ($time_am[$i_am] < $_init_am_end) {

            $temp_am = array();

            $temp_am['index'] = $i_am;
            $temp_am['key'] = date('Y-m-d H:i:s', $time_am[$i_am]);
            $time_am[] = strtotime("+30 minutes", $time_am[$i_am]);

            $temp_am['timestamp'] = strtotime("+30 minutes", $time_am[$i_am]);
            $temp_am['is_use'] = 0 ;
            $temp_am['lable']  = $this->dataEchoH_i($time_am[$i_am]) . "-" . $this->dataEchoH_i($time_am[$i_am+1]);

            $result_am[] = $temp_am;

            $i_am = $i_am + 1;
        }

        $i_pm = 0;

        while ($time_pm[$i_pm] < $_init_pm_end) {

            $temp_pm = array();

            $temp_pm['index'] = $i_pm+24;
            $temp_pm['key'] = date('Y-m-d H:i:s', $time_pm[$i_pm]);
            $time_pm[] = strtotime("+30 minutes", $time_pm[$i_pm]);

            $temp_pm['timestamp'] = strtotime("+30 minutes", $time_pm[$i_pm]);
            $temp_pm['is_use'] = 0;
            $temp_pm['lable'] = $this->dataEchoH_i($time_pm[$i_pm]) . "-" . $this->dataEchoH_i($time_pm[$i_pm+1]);

            $result_pm[] = $temp_pm;

            $i_pm = $i_pm + 1;
        }

        $result = array();
        $result['am'] = $result_am;
        $result['pm'] = $result_pm;

        return $result;

    }

    public function set_boardroom_situation($boardroom_id,$Ymd,$value){

        global $_W;

        /******************************************************* redis *********************************************************/
        include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
        $_redis = new RedisUtil();

        $key = 'superdesk_boardroom_' . 'appointment' . '_' . $_W['uniacid'];
        $hkey = $boardroom_id . "_" . $Ymd;

        $_redis->hset($key, $hkey, json_encode($value));

    }

    public function get_boardroom_situation($boardroom_id,$Ymd){

        global $_W;

//        $now = time();
//        $Ymd = date('Y-m-d', $now);

        /******************************************************* redis *********************************************************/
        include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
        $_redis = new RedisUtil();

        $key = 'superdesk_boardroom_' . 'appointment' . '_' . $_W['uniacid'];
        $hkey = $boardroom_id . "_" . $Ymd;

//        echo $key;
//        echo "<br/>";
//        echo $hkey;
//        echo "<br/>";
//        echo "<br/>";
//        echo "<br/>";echo "<br/>";
//        echo "<br/>";echo "<br/>";
//        echo json_encode($result_am);
//        echo "<br/>";
//        echo "<br/>";echo "<br/>";
//        echo "<br/>";echo "<br/>";
//        echo json_encode($result_pm);

        $result = array();

        if ($_redis->ishExists($key, $hkey) == 1) {
            $result = json_decode($_redis->hget($key, $hkey),true);
        } else {
            $result = $this->init_boardroom_situation($boardroom_id,$Ymd);
            $_redis->hset($key, $hkey, json_encode($result));
        }

        return $result;


        /******************************************************* redis *********************************************************/

    }

    public function doMobileTestWeekTable()
    {

        $table = $this->getWeekTableData(time());
        echo $this->getWeekTableHtml($table);
    }


    public function getUuid()
    {

        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant
            // DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        return $uuid;
    }


    public function createAngularJsUrl($do, $query = array(), $noredirect = true)
    {
        global $_W;

        $addhost = true;
        $query['do'] = $do;
//        $query['m'] = 'business_dongyuantang';
        $query['m'] = strtolower($this->modulename);
        return murl('entry', $query, $noredirect, $addhost);
    }

    public function doMobileCreateUrl()
    {

        $ApiGetAllCaseCate = $this->createAngularJsUrl('h5_step_01.inc');

        echo $ApiGetAllCaseCate;
        echo '<br/>';


    }

    // 检查是否登录
//    public function checkAuth()
//    {
//        global $_W;
//
////        $url = $this->createMobileUrl('register');
////        $index = murl('');
////        echo "<script>if(window.confirm('当前微信号不是公寓住客或未通过审核，是否跳转到注册页？')){window.location.href='{$url}';}else {window.location.href='{$index}';}</script>";exit;
//
//        $redirect_register = $this->createMobileUrl('register');
//
//
//        $page = 1;
//        $page_size = 100;
////        checkauth();
//
////        echo $_W['openid'];
////        echo $_SESSION['openid'];
////        include_once(MODULE_ROOT . '/model/patient_doc.class.php');
//        include_once('model/patient_doc.class.php');
//        $patient_doc = new patient_docModel();
//
//        $where = array('openid' => $_W['openid']);
//
//        $result = $patient_doc->checkAuth($where, $page, $page_size);
//        $total = $result['total'];
////        $page = $result['page'];
////        $page_size = $result['page_size'];
//        $list = $result['data'];
//
////        echo $total;
//
//        if ($total == 0) {
////            header("location: $redirect_register");
//            include $this->template('patient_doc_list');
//            exit(0);
//        }
//    }


    public function getProfile()
    {

        global $_W, $_GPC;
        load()->model('mc');

        $profile = mc_fetch($_SESSION['openid'], array('avatar', 'mobile', 'email', 'realname', 'nickname', 'address', 'company', 'resideprovince', 'residecity', 'residedist', 'bio', 'credit3', 'credit4'));

        if (!isset($profile['avatar']) || empty($profile['avatar'])
            || isset($profile['nickname']) || empty($profile['nickname'])
            || isset($profile['address']) || empty($profile['address'])
        ) {
            mc_oauth_userinfo();
            $profile = mc_fetch($_SESSION['openid'], array('avatar', 'mobile', 'email', 'realname', 'nickname', 'address', 'company', 'resideprovince', 'residecity', 'residedist', 'bio', 'credit3', 'credit4'));
        }

        $profile['openid'] = $_SESSION['openid'];

        return $profile;
    }


    public function sendMobilePayMsg($order, $goods, $paytype, $ordergoods)
    {

        $address = pdo_fetch("SELECT * FROM " . tablename('gcross_eso_sale_address') . " WHERE id = :id", array(':id' => $order['addressid']));
        $cfg = $this->module['config'];
        $template_id = $cfg['paymsgTemplateid'];
        if (!empty($template_id)) {
            include 'messagetemplate/pay.php';
            $this->sendtempmsg($template_id, '', $data, '#FF0000');
        }

    }

    //发送模板消息
    public function sendtempmsg($template_id, $url, $data, $topcolor)
    {
        global $_W, $_GPC;
        //取TOKEN
        $from_user = $this->getFromUser();
        $tokens = $this->get_weixin_token();
        if (empty($tokens)) {
            return;
        }
        //
        $postarr = '{"touser":"' . $from_user . '","template_id":"' . $template_id . '","url":"' . $url . '","topcolor":"' . $topcolor . '","data":' . $data . '}';
        $res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $tokens, $postarr);
        //$res = $res['content'];
//		$res = $res['content'];
//		$res = json_decode($res, true);

        return true;

    }


    public function payResult($params)
    {
        global $_W;

        $fee = intval($params['fee']);

        $data = array('status' => $params['result'] == 'success' ? 1 : 0);

        $paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');

        // 卡券代金券备注
        if (!empty($params['is_usecard'])) {
            $cardType = array('1' => '微信卡券', '2' => '系统代金券');
            $data['paydetail'] = '使用' . $cardType[$params['card_type']] . '支付了' . ($params['fee'] - $params['card_fee']);
            $data['paydetail'] .= '元，实际支付了' . $params['card_fee'] . '元。';
        }


        $data['paytype'] = $paytype[$params['type']];
        if ($paytype[$params['type']] == '') {
            $data['paytype'] = 4;
        }

        if ($params['type'] == 'wechat') {
            $data['transaction_id'] = $params['tag']['transaction_id'];
        }

        if ($params['type'] == 'delivery') {
            $data['status'] = 1;
        }

        if ($_SESSION['superdesk_boardroom_pay_result'] != $params['tid']) {

            session_start();
            $_SESSION['superdesk_boardroom_pay_result'] = $params['tid'];


            $pay_status = pdo_get('superdesk_boardroom_s_order', array('id' => $params['tid']));
            $pay_status = $pay_status['status'];


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

            /******************************************************* 预约服务 *********************************************************/
            // TODO 更新会议预约表与redis中的会议室情况

            include_once(MODULE_ROOT . '/model/boardroom_appointment.class.php');
            $boardroom_appointment = new boardroom_appointmentModel();


            $column_appointment = array(
                'out_trade_no' => $params['tid'],
                'uniacid' => $_W['uniacid']
            );
            $_boardroom_appointment = $boardroom_appointment->getOneByColumn($column_appointment);


            // 数组对象
            $situation_target = $this->get_boardroom_situation($_boardroom_appointment['boardroom_id'],$_boardroom_appointment['lable_ymd']);
            $situation_select = json_decode($_boardroom_appointment['situation'],true);

            foreach ($situation_select['situation'] as $index => &$_situation){
                //    {
                //        ["index"]=> int(0)
                //        ["key"]=> string(19) "2017-07-31 00:00:00"
                //        ["timestamp"]=> int(1501432200)
                //        ["is_use"]=> int(0)
                //        ["lable"]=> string(11) "00:00-00:30"
                //        ["checked"]=> int(0)
                //    }
//                0-23
                if($_situation['index'] >= 0 && $_situation['index'] <= 23){
                    $situation_target['am'][$_situation['index']]['is_use'] = 1;
                }
//                24-47
                if($_situation['index'] >= 24 && $_situation['index'] <= 47){
                    $situation_target['pm'][$_situation['index']-24]['is_use'] = 1;
                }
            }

            $this->set_boardroom_situation($_boardroom_appointment['boardroom_id'],$_boardroom_appointment['lable_ymd'],$situation_target);

            $boardroom_appointment->saveOrUpdateByColumn($data,$column_appointment);




            /******************************************************* 预约服务 *********************************************************/


            /******************************************************* 附加服务 *********************************************************/
            $goods = pdo_fetchall("SELECT `goodsid`, `total`, `optionid` FROM " . tablename('superdesk_boardroom_s_order_goods') . " WHERE `out_trade_no` = :out_trade_no",
                array(
                    ':out_trade_no' => $params['tid']
                )
            );
            if (!empty($goods)) {
                $row = array();
                foreach ($goods as $row) {
                    $goodsInfo = pdo_fetch("SELECT `total`, `totalcnf`, `sales` FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE `id` = :id", array(':id' => $row['goodsid']));
                    $goodsupdate = array();

                    // 付款减库存
                    if ($goodsInfo['totalcnf'] == '1' && !empty($goodsInfo['total'])) {
                        $goodsupdate['total'] = $goodsInfo['total'] - $row['total'];
                        $goodsupdate['total'] = ($goodsupdate['total'] < 0) ? 0 : $goodsupdate['total'];
                    }

                    $goodsupdate['sales'] = $goodsInfo['sales'] + $row['total'];

                    if ($pay_status != 1) {
                        pdo_update('superdesk_boardroom_s_goods', $goodsupdate, array('id' => $row['goodsid']));
                    }

                    $optionInfo = pdo_fetch("SELECT `stock` FROM " . tablename('superdesk_boardroom_s_goods_option') . " WHERE `id` = :id",
                        array(
                            ':id' => $row['optionid']
                        )
                    );
                    $options = array();
                    if ($goodsInfo['totalcnf'] == '1' && !empty($optionInfo['stock'])) {
                        $options['stock'] = $optionInfo['stock'] - $row['total'];
                        $options['stock'] = ($optionInfo['stock'] < 0) ? 0 : $options['stock'];
                        if ($pay_status != 1) {
                            pdo_update('superdesk_boardroom_s_goods_option', $options, array('id' => $row['optionid']));
                        }
                    }
                }
            }

            pdo_update('superdesk_boardroom_s_order', $data, array('out_trade_no' => $params['tid'], 'uniacid' => $_W['uniacid']));
            /******************************************************* 附加服务 *********************************************************/


        } else {

            $setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
            $credit = $setting['creditbehaviors']['currency'];

            if ($params['type'] == $credit) {
                message('预定成功！', $this->createMobileUrl('boardroom_booking_success', array('status' => 2,'out_trade_no' =>$params['tid'])), 'success');
            } else {
                message('预定成功！', '../../app/' . $this->createMobileUrl('boardroom_booking_success', array('status' => 2,'out_trade_no' =>$params['tid'])), 'success');
            }
        }


        if ($params['from'] == 'return') {

            //积分变更
//            $this->setOrderCredit($params['tid']);


            if (!empty($this->module['config']['noticeemail']) // 发送邮件
                || !empty($this->module['config']['template']) // 发送模板消息
                || !empty($this->module['config']['mobile'])   // 发送短信
            ) {

                $order = pdo_fetch("SELECT `out_trade_no`, `price`, `paytype`, `from_user`, `address`, `createtime` FROM " . tablename('superdesk_boardroom_s_order') . " WHERE id = '{$params['tid']}'");
                $ordergoods = pdo_fetchall("SELECT goodsid, total FROM " . tablename('superdesk_boardroom_s_order_goods') . " WHERE orderid = '{$params['tid']}'", array(), 'goodsid');
                $goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
//				$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $order['addressid']));
                $address = explode('|', $order['address']);

                // 邮件提醒
                if (!empty($this->module['config']['noticeemail'])) {
                    $body = "<h3>购买商品清单</h3> <br />";
                    if (!empty($goods)) {
                        foreach ($goods as $row) {
                            $body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
                        }
                    }
                    $paytype = $order['paytype'] == '3' ? '货到付款' : '已付款' . '<br />';
                    $body .= '总金额：' . $order['price'] . '元' . $paytype . '<br />';
                    $body .= '<h3>购买用户详情</h3> <br />';
                    $body .= '真实姓名：' . $address[0] . '<br />';
                    $body .= '地区：' . $address[3] . ' - ' . $address[4] . ' - ' . $address[5] . '<br />';
                    $body .= '详细地址：' . $address[6] . '<br />';
                    $body .= '手机：' . $address[1] . '<br />';

                    load()->func('communication');
                    ihttp_email($this->module['config']['noticeemail'], '微商城订单提醒', $body);
                }



                //模板消息
                if (!empty($this->module['config']['template'])) {
                    $good = '';
                    $address = explode('|', $order['address']);
                    if (!empty($goods)) {
                        foreach ($goods as $row) {
                            $good .= "\n" . "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} ";
                        }
                    }
                    $paytype = $order['paytype'] == '3' ? '货到付款' : '已付款';
                    $data = array(
                        'first' => array('value' => '购买商品清单'),
                        'keyword1' => array('value' => date('Y-m-d H:i', strtotime('now'))),
                        'keyword2' => array('value' => "\n" . $good),
                        'keyword3' => array('value' => $order['price']),
                        'keyword4' => array('value' => "\n" . '真实姓名：' . $address[0] . "\n" . '地区：' . $address[3] . ' - ' . $address[4] . ' - ' . $address[5] . "\n" . '详细地址：' . $address[6] . "\n" . '手机：' . $address[1]),
                        'keyword5' => array('value' => $paytype)
                    );
                    $acc = WeAccount::create($_W['acid']);
                    $acc->sendTplNotice($_W['openid'], $this->module['config']['templateid'], $data);
                }



                // 短信提醒
                if (!empty($this->module['config']['mobile'])) {
                    load()->model('cloud');
                    cloud_prepare();
                    cloud_sms_send($this->module['config']['mobile'], '800001', array('user' => $address[0], 'mobile' => $address[1], 'datetime' => date('m月d日H:i'), 'order_no' => $order['out_trade_no'], 'totle' => $order['price']));
                }
            }

            $setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
            $credit = $setting['creditbehaviors']['currency'];

            if ($params['type'] == $credit) {
                message('预定成功！', $this->createMobileUrl('boardroom_booking_success',array('out_trade_no' =>$params['tid'])), 'success');
            } else {
                message('预定成功！', '../../app/' . $this->createMobileUrl('boardroom_booking_success',array('out_trade_no' =>$params['tid'])), 'success');
            }
        }
    }

    public function changeWechatSend($id, $status, $msg = '') {
        global $_W;
        $paylog = pdo_fetch("SELECT plid, openid, tag FROM " . tablename('core_paylog') . " WHERE tid = '{$id}' AND status = 1 AND type = 'wechat'");
        if (!empty($paylog['openid'])) {
            $paylog['tag'] = iunserializer($paylog['tag']);
            $acid = $paylog['tag']['acid'];
            $account = account_fetch($acid);
            $payment = uni_setting($account['uniacid'], 'payment');
            if ($payment['payment']['wechat']['version'] == '2') {
                return true;
            }
            $send = array(
                'appid' => $account['key'],
                'openid' => $paylog['openid'],
                'transaction_id' => $paylog['tag']['transaction_id'],
                'out_trade_no' => $paylog['plid'],
                'deliver_timestamp' => TIMESTAMP,
                'deliver_status' => $status,
                'deliver_msg' => $msg,
            );
            $sign = $send;
            $sign['appkey'] = $payment['payment']['wechat']['signkey'];
            ksort($sign);
            $string = '';
            foreach ($sign as $key => $v) {
                $key = strtolower($key);
                $string .= "{$key}={$v}&";
            }
            $send['app_signature'] = sha1(rtrim($string, '&'));
            $send['sign_method'] = 'sha1';
            $account = WeAccount::create($acid);
            $response = $account->changeOrderStatus($send);
            if (is_error($response)) {
                message($response['message']);
            }
        }
    }

    public function checkAuth()
    {
        global $_W;
        $setting = cache_load('unisetting:' . $_W['uniacid']);
        if (empty($_W['member']['uid']) && empty($setting['passport']['focusreg'])) {
            $fan = pdo_get('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'openid' => $_W['openid']));
            if (!empty($fan)) {
                $fanid = $fan['fanid'];
            } else {
                if (empty($_W['openid'])) {
                    $_W['opendi'] = random(28);
                }
                $post = array(
                    'uniacid' => $_W['uniacid'],
                    'updatetime' => time(),
                    'openid' => $_W['openid'],
                    'follow' => 0,
                );
                pdo_insert('mc_mapping_fans', $post);
                $fanid = pdo_insertid();
            }
            if (empty($fan['uid'])) {
                pdo_insert('mc_members', array('uniacid' => $_W['uniacid']));
                $uid = pdo_insertid();
                $_W['member']['uid'] = $uid;
                $_W['fans']['uid'] = $uid;
                pdo_update('mc_mapping_fans', array('uid' => $uid), array('fanid' => $fanid));
            } else {
                $_W['member']['uid'] = $fan['uid'];
                $_W['fans']['uid'] = $fan['uid'];
            }
        } else {
            checkauth();
        }
    }

    //设置订单商品的库存 minus  true 减少  false 增加
    public function setOrderStock($id = '', $minus = true) {
        $goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,g.total as goodstotal,o.total,o.optionid,g.sales FROM " . tablename('superdesk_boardroom_s_order_goods') . " o left join " . tablename('superdesk_boardroom_s_goods') . " g on o.goodsid=g.id "
            . " WHERE o.orderid='{$id}'");
        foreach ($goods as $item) {
            if ($minus) {
                //属性
                if (!empty($item['optionid'])) {
                    pdo_query("update " . tablename('superdesk_boardroom_s_goods_option') . " set stock=stock-:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
                }
                $data = array();
                if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
                    $data['total'] = $item['goodstotal'] - $item['total'];
                }
                $data['sales'] = $item['sales'] + $item['total'];
                pdo_update('superdesk_boardroom_s_goods', $data, array('id' => $item['id']));
            } else {
                //属性
                if (!empty($item['optionid'])) {
                    pdo_query("update " . tablename('superdesk_boardroom_s_goods_option') . " set stock=stock+:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
                }
                $data = array();
                if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
                    $data['total'] = $item['goodstotal'] + $item['total'];
                }
                $data['sales'] = $item['sales'] - $item['total'];
                pdo_update('superdesk_boardroom_s_goods', $data, array('id' => $item['id']));
            }
        }
    }

    public function getCartTotal() {
        global $_W;
        $cartotal = pdo_fetchcolumn("select sum(total) from " . tablename('superdesk_boardroom_s_cart') . " where uniacid = '{$_W['uniacid']}' and from_user='{$_W['openid']}'");
        return empty($cartotal) ? 0 : $cartotal;
    }

    public function getFeedbackType($type) {
        $types = array(1 => '维权', 2 => '告警');
        return $types[intval($type)];
    }

    public function getFeedbackStatus($status) {
        $statuses = array('未解决', '用户同意', '用户拒绝');
        return $statuses[intval($status)];
    }

    function time_tran($the_time) {
        $timediff = $the_time - time();
        $days = intval($timediff / 86400);
        if (strlen($days) <= 1) {
            $days = "0" . $days;
        }
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600);
        ;
        if (strlen($hours) <= 1) {
            $hours = "0" . $hours;
        }
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        if (strlen($mins) <= 1) {
            $mins = "0" . $mins;
        }
        $secs = $remain % 60;
        if (strlen($secs) <= 1) {
            $secs = "0" . $secs;
        }
        $ret = "";
        if ($days > 0) {
            $ret.=$days . " 天 ";
        }
        if ($hours > 0) {
            $ret.=$hours . ":";
        }
        if ($mins > 0) {
            $ret.=$mins . ":";
        }
        $ret.=$secs;
        return array("倒计时 " . $ret, $timediff);
    }

    //设置订单积分
    public function setOrderCredit($orderid, $add = true) {
        global $_W;
        $order = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_s_order') . " WHERE id = :id AND uniacid = :uniacid  limit 1", array(':id' => $orderid, ':uniacid' => $_W['uniacid']));
        if (empty($order)) {
            return false;
        }
        $sql = 'SELECT `goodsid`, `total` FROM ' . tablename('superdesk_boardroom_s_order_goods') . ' WHERE `orderid` = :orderid';
        $orderGoods = pdo_fetchall($sql, array(':orderid' => $orderid));
        if (!empty($orderGoods)) {
            $credit = 0.00;
            $sql = 'SELECT `credit` FROM ' . tablename('superdesk_boardroom_s_goods') . ' WHERE `id` = :id';
            foreach ($orderGoods as $goods) {
                $goodsCredit = pdo_fetchcolumn($sql, array(':id' => $goods['goodsid']));
                $credit += $goodsCredit * floatval($goods['total']);
            }
        }
        //增加积分
        if (!empty($credit)) {
            load()->model('mc');
            load()->func('compat.biz');
            $uid = mc_openid2uid($order['from_user']);
            $fans = fans_search($uid, array("credit1"));
            if (!empty($fans)) {
                if (!empty($add)) {
                    mc_credit_update($_W['member']['uid'], 'credit1', $credit, array('0' => $_W['member']['uid'], '购买商品赠送'));
                } else {
                    mc_credit_update($_W['member']['uid'], 'credit1', 0 - $credit, array('0' => $_W['member']['uid'], '微商城操作'));
                }
            }
        }
    }

}
