<?php
/**
 * 超级前台-点餐
 *
 */
defined('IN_IA') or exit('Access Denied');

include "model.php";
//include "plugin/feyin/HttpClient.class.php";
//include "plugin/feyin/FeyinAPI.php";
//define('FEYIN_HOST', 'my.feyin.net');
//define('FEYIN_PORT', 80);


include "templateMessage.php";
define('RES', '../addons/superdesk_dish/template/');
define('CUR_MOBILE_DIR', 'dish/');
//define('LOCK', 'Li4vYWRkb25zL3dlaXNyY19kaXNoL3RlbXBsYXRlL2ltYWdlcy92ZXJzaW9uLmNzcw==');
define('LOCK', '../addons/superdesk_dish/template/images/version.css');



class superdesk_dishModuleSite extends WeModuleSite
{
    //模块标识
    public $modulename = 'superdesk_dish';

    public $member_code = '';
    public $feyin_key = '';
    public $device_no = '';

    public $msg_status_success = 1;
    public $msg_status_bad = 0;
    public $_debug = '1'; //default:0
    public $_weixin = '0'; //default:1

    public $_appid = '';
    public $_appsecret = '';
    public $_accountlevel = '';
    public $_account = '';

    public $_weid = '';
    public $_fromuser = '';
    public $_nickname = '';
    public $_headimgurl = '';

    public $_auth2_openid = '';
    public $_auth2_nickname = '';
    public $_auth2_headimgurl = '';

    public $table_area = 'superdesk_dish_area';
    public $table_blacklist = 'superdesk_dish_blacklist';
    public $table_cart = 'superdesk_dish_cart';
    public $table_category = 'superdesk_dish_category';
    public $table_email_setting = 'superdesk_dish_email_setting';
    public $table_goods = 'superdesk_dish_goods';
    public $table_intelligent = 'superdesk_dish_intelligent';
    public $table_nave = 'superdesk_dish_nave';
    public $table_order = 'superdesk_dish_order';
    public $table_order_goods = 'superdesk_dish_order_goods';
    public $table_print_order = 'superdesk_dish_print_order';
    public $table_print_setting = 'superdesk_dish_print_setting';
    public $table_reply = 'superdesk_dish_reply';
    public $table_setting = 'superdesk_dish_setting';
    public $table_sms_checkcode = 'superdesk_dish_sms_checkcode';
    public $table_sms_setting = 'superdesk_dish_sms_setting';
    public $table_store_setting = 'superdesk_dish_store_setting';
    public $table_mealtime = 'superdesk_dish_mealtime';
    public $table_stores = 'superdesk_dish_stores';
    public $table_collection = 'superdesk_dish_collection';
    public $table_type = 'superdesk_dish_type';

    function __construct()
    {
        global $_W, $_GPC;
        $this->_fromuser = $_W['fans']['from_user']; //debug
        if ($_SERVER['HTTP_HOST'] == '127.0.0.1'
            || $_SERVER['HTTP_HOST'] == 'localhost:8888'
            || $_SERVER['HTTP_HOST'] == '192.168.1.124') {
            $this->_fromuser = 'debug';
        }

        $this->_weid = $_W['uniacid'];
        $account = $_W['account'];

        $this->_auth2_openid = 'auth2_openid_' . $_W['uniacid'];
        $this->_auth2_nickname = 'auth2_nickname_' . $_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];

        $this->_appid = '';
        $this->_appsecret = '';
        $this->_accountlevel = $account['level']; //是否为高级号

        if (isset($_COOKIE[$this->_auth2_openid])) {
            $this->_fromuser = $_COOKIE[$this->_auth2_openid];
        }

        if ($this->_accountlevel < 4) {
            $setting = uni_setting($this->_weid);
            $oauth = $setting['oauth'];
            if (!empty($oauth) && !empty($oauth['account'])) {
                $this->_account = account_fetch($oauth['account']);
                $this->_appid = $this->_account['key'];
                $this->_appsecret = $this->_account['secret'];
            }
        } else {
            $this->_appid = $_W['account']['key'];
            $this->_appsecret = $_W['account']['secret'];
        }
    }


    public function check_black_list()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_blacklist) . " WHERE weid=:weid AND from_user=:from_user LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));

        if (!empty($item) && $item['status'] == 0) {
            message('你在黑名单中,不能进行相关操作...');
        }
    }

    public function check_mealtime()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $timelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_mealtime) . " WHERE weid=:weid AND storeid=0 ", array(':weid' => $weid));

        $nowtime = intval(date("Hi"));

        foreach ($timelist as $key => $value) {
            $begintime = intval(str_replace(':', '', $value['begintime']));
            $endtime = intval(str_replace(':', '', $value['endtime']));

            if ($nowtime >= $begintime && $nowtime <= $endtime) {
                return 1;
            }
        }
        return 0;
    }

    public function testSendFormatedMessage()
    {
        $msgNo = time() + 1;
        /*
         格式化的打印内容
        */
        $msgInfo = array(
            'memberCode' => $this->member_code,
            'charge' => '3000',
            'customerName' => '刘小姐',
            'customerPhone' => '13321332245',
            'customerAddress' => '五山华南理工',
            'customerMemo' => '请快点送货',
            'msgDetail' => '番茄炒粉@1000@2||客家咸香鸡@2000@1',
            'deviceNo' => $this->device_no,
            'msgNo' => $msgNo,
        );

        echo $this->sendFormatedMessage($msgInfo);
        return $msgNo;
    }

    function feiyinSendFreeMessage($orderid = 0, $print_type = 0)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        if ($orderid == 0) {
            return -2;
        }

        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE  id =:id AND weid=:weid ORDER BY id DESC limit 1", array(':id' => $orderid, ':weid' => $weid));

        if (empty($order)) {
            return -3;
        }

        $storeid = $order['storeid'];
        //打印机配置信息
        $settings = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1 AND type='feiyin' AND print_type=:print_type", array(':storeid' => $storeid, ':print_type' => 0));

        if ($settings == false) {
            return -4;
        }

        $paytype = array('0' => '线下付款', '1' => '余额支付', '2' => 在线支付, '3' => '货到付款');
        //商品id数组
        $goodsid = pdo_fetchall("SELECT goodsid, total FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $orderid), 'goodsid');
        //商品
        $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
        $order['goods'] = $goods;

        $store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE  id =:id AND weid=:weid ORDER BY id DESC limit 1", array(':id' => $storeid, ':weid' => $weid));

        $content = '单号:' . $order['ordersn'] . "\n";
        $content .= '门店:' . $store['title'] . "\n";
        $content .= '支付方式:' . $paytype[$order['paytype']] . "\n";
        $content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "\n";
        $content .= '预定时间:' . $order['meal_time'] . "\n";
        if (!empty($order['seat_type'])) {
            $seat_type = $order['seat_type'] == 1 ? '大厅' : '包间';
            $content .= '位置类型:' . $seat_type . "\n";
        }
        if (!empty($order['tables'])) {
            $content .= '桌号:' . $order['tables'] . "\n";
        }

        if (!empty($order['remark'])) {
            $content .= '备注:' . $order['remark'] . "\n";
        }
        $content .= "\n菜单列表\n";
        $content .= "-------------------------\n";

        $content1 = '';
        foreach ($order['goods'] as $v) {
            if ($v['isspecial'] == 2) {
                $money = intval($v['marketprice']) == 0 ? $v['productprice'] : $v['marketprice'];
            } else {
                $money = $v['productprice'];
            }
            $content1 .= $v['title'] . ' ' . $goodsid[$v['id']]['total'] . $v['unitname'] . ' ' . number_format($money, 1) . "元\n\n";
        }

        $content2 = "-------------------------\n";
        $content2 .= "总数量:" . $order['totalnum'] . "   总价:" . number_format($order['totalprice'], 1) . "元\n";
        if (!empty($order['username'])) {
            $content2 .= '姓名:' . $order['username'] . "\n";
        }
        if (!empty($order['tel'])) {
            $content2 .= '手机:' . $order['tel'] . "\n";
        }
        if (!empty($order['address'])) {
            $content2 .= '地址:' . $order['address'];
        }

        if (!empty($setting['print_bottom'])) {
            $content2 .= "" . $setting['print_bottom'] . "\n";
        }

        foreach ($settings as $item => $value) {
            if (!empty($value['print_top'])) {
                $print_top = "" . $value['print_top'] . "\n";
            }
            if (!empty($value['print_bottom'])) {
                $print_bottom = "" . $value['print_bottom'] . "\n";
            }

            if ($value['type'] == 'feiyin') { //飞印
                $print_order_data = array(
                    'weid' => $weid,
                    'orderid' => $orderid,
                    'print_usr' => $value['print_usr'],
                    'print_status' => -1,
                    'dateline' => TIMESTAMP
                );
                $print_order = pdo_fetch("SELECT * FROM " . tablename($this->table_print_order) . " WHERE orderid=:orderid AND print_usr=:usr LIMIT 1", array(':orderid' => $orderid, ':usr' => $value['print_usr']));
                if (empty($print_order)) {
                    pdo_insert('superdesk_dish_print_order', $print_order_data);
                    $oid = pdo_insertid();
                }
            }

            $this->member_code = $value['member_code'];
            $this->device_no = $value['print_usr'];
            $this->feyin_key = $value['feyin_key'];

            $msgNo = time() + 1;
            $freeMessage = array(
                'memberCode' => $this->member_code,
                'msgDetail' => $print_top . $content . $content1 . $content2 . $print_bottom,
                'deviceNo' => $this->device_no,
                'msgNo' => $oid,
            );
            $feiyinstatus = $this->sendFreeMessage($freeMessage);
            pdo_update('superdesk_dish_print_order', array('print_status' => $feiyinstatus), array('id' => $oid));
        }
        return $msgNo;
    }

    //用户打印机处理订单
    private function feiyinformat($string, $length = 0, $isleft = true)
    {
        $substr = '';
        if ($length == 0 || $string == '') {
            return $string;
        }
        if ($this->print_strlen($string) > $length) {
            for ($i = 0; $i < $length; $i++) {
                $substr = $substr . "  ";
            }
            $string = $string . $substr;
        } else {
            for ($i = $this->print_strlen($string); $i < $length; $i++) {
                $substr = $substr . " ";
            }
            $string = $isleft ? ($string . $substr) : ($substr . $string);
        }
        return $string;
    }

    /**
     * @param string $l
     * @param string $r
     *
     * @return string
     */
    function formatstr($l = '', $r = '')
    {
        $nbsp = '                              ';
        $llen = $this->print_strlen($l);
        $rlen = $this->print_strlen($r);
        if ($l && $r) {
            $lr = $llen + $rlen;
            $nl = $this->print_strlen($nbsp);
            if ($lr >= $nl) {
                $strtxt = $l . "\r\n" . $this->formatstr(null, $r);
            } else {
                $strtxt = $l . substr($nbsp, $lr) . $r;
            }
        } elseif ($r) {
            $strtxt = substr($nbsp, $rlen) . $r;
        } else {
            $strtxt = $l;
        }
        return $strtxt;
    }

    /**
     * PHP获取字符串中英文混合长度
     *
     * @param        $str        字符串
     * @param string $charset    编码
     *
     * @return int 返回长度，1中文=2位(utf-8为3位)，1英文=1位
     */
    private function print_strlen($str, $charset = '')
    {
        global $_W;
        if (empty($charset)) {
            $charset = $_W['charset'];
        }
        if (strtolower($charset) == 'gbk') {
            $charset = 'gbk';
            $ci = 2;
        } else {
            $charset = 'utf-8';
            $ci = 3;
        }
        if (strtolower($charset) == 'utf-8') $str = iconv('utf-8', 'GBK//IGNORE', $str);
        $num = strlen($str);
        $cnNum = 0;
        for ($i = 0; $i < $num; $i++) {
            if (ord(substr($str, $i + 1, 1)) > 127) {
                $cnNum++;
                $i++;
            }
        }
        $enNum = $num - ($cnNum * $ci);
        $number = $enNum + $cnNum * $ci;
        return ceil($number);
    }







    //取得购物车中的商品
    public function getDishCountInCart($storeid)
    {
        global $_GPC, $_W;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $dishlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_cart) . " WHERE  storeid=:storeid AND from_user=:from_user AND weid=:weid", array(':from_user' => $from_user, ':weid' => $weid, ':storeid' => $storeid));
        foreach ($dishlist as $key => $value) {
            $arr[$value['goodsid']] = $value['total'];
        }
        return $arr;
    }

    //购物车增加商品
    public function doMobileUpdateDishNumOfCategory()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $_GPC['from_user'];
        $this->_fromuser = $from_user;

        $storeid = intval($_GPC['storeid']); //门店id
        $dishid = intval($_GPC['dishid']); //商品id
        $total = intval($_GPC['o2uNum']); //更新数量

        if (empty($from_user)) {
            $result['msg'] = '会话已过期，请重新发送关键字!';
            message($result, '', 'ajax');
        }

        //查询商品是否存在
        $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE  id=:id", array(":id" => $dishid));
        if (empty($goods)) {
            $result['msg'] = '没有相关商品';
            message($result, '', 'ajax');
        }

        //查询购物车有没该商品
        $cart = pdo_fetch("SELECT * FROM " . tablename($this->table_cart) . " WHERE goodsid=:goodsid AND weid=:weid AND storeid=:storeid AND from_user='" . $from_user . "'", array(':goodsid' => $dishid, ':weid' => $weid, ':storeid' => $storeid));

        if (empty($cart)) {
            //不存在的话增加商品点击量
            pdo_query("UPDATE " . tablename($this->table_goods) . " SET subcount=subcount+1 WHERE id=:id", array(':id' => $dishid));
            //添加进购物车
            $data = array(
                'weid' => $weid,
                'storeid' => $goods['storeid'],
                'goodsid' => $goods['id'],
                'goodstype' => $goods['pcate'],
                'price' => $goods['isspecial'] == 1 ? $goods['productprice'] : $goods['marketprice'],
                'from_user' => $from_user,
                'total' => 1
            );
            pdo_insert($this->table_cart, $data);
        } else {
            //更新商品在购物车中的数量
            pdo_query("UPDATE " . tablename($this->table_cart) . " SET total=" . $total . " WHERE id=:id", array(':id' => $cart['id']));
        }

        $result['code'] = 0;
        message($result, '', 'ajax');
    }





    public function payResult($params)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $orderid = $params['tid'];
        $fee = intval($params['fee']);
        $data = array('status' => $params['result'] == 'success' ? 1 : 0);
        $paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
        $data['paytype'] = $paytype[$params['type']];

        if ($params['type'] == 'wechat') {
            $data['dateline'] = TIMESTAMP;
            $paylog = pdo_fetch("SELECT plid FROM " . tablename('core_paylog') . " WHERE uniacid= :uniacid and tid = :tid", array(':uniacid' => $weid, ':tid' => $orderid));
            if (!empty($paylog)) {
                $data['transid'] = $paylog['plid'];
            }
//            $data['transid'] = $params['tag']['transaction_id'];
        }

        if ($params['type'] == 'delivery') {
            $data['status'] = 1;
        }

        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = '{$orderid}'");
        $storeid = $order['storeid'];
        pdo_update($this->table_order, $data, array('id' => $orderid));
        if ($params['from'] == 'return') {

            //邮件提醒
            $goods_str = '';
            //本订单产品
            $goods = pdo_fetchall("SELECT a.*,b.title,b.unitname FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$orderid}");
            $goods_str = '';
            $goods_tplstr = '';
            $flag = false;
            foreach ($goods as $key => $value) {
                if (!$flag) {
                    $goods_str .= "{$value['title']} 价格：{$value['price']} 数量：{$value['total']}{$value['unitname']}";
                    $goods_tplstr .= "{$value['title']} {$value['total']}{$value['unitname']}";
                    $flag = true;
                } else {
                    $goods_str .= "<br/>{$value['title']} 价格：{$value['price']} 数量：{$value['total']}{$value['unitname']}";
                    $goods_tplstr .= ",{$value['title']} {$value['total']}{$value['unitname']}";
                }
            }

            //发送邮件提醒
            $emailSetting = pdo_fetch("SELECT * FROM " . tablename($this->table_email_setting) . " WHERE weid=:weid AND storeid=:storeid LIMIT 1", array(':weid' => $weid, ':storeid' => $storeid));
            $email_tpl = $emailSetting['email_business_tpl'];
            //您有新的订单：[sn]，收货人：[name]，菜单：[goods]，电话：[tel]，请及时确认订单！
            $email_tpl = "
        您有新订单：<br/>
        所属门店：[store]<br/>
        单号[sn] 总数量:[totalnum] 总价:[totalprice]<br/>
        菜单：[goods]<br/>
        姓名：[name]<br/>
        电话：[tel]<br/>
        桌号：[tables]<br/>
        备注：[remark]
        ";

            if ($order['dining_mode'] == 2) {
                $email_tpl = "
        您有新订单：<br/>
        所属门店：[store]<br/>
        单号[sn] 总数量:[totalnum] 总价:[totalprice]<br/>
        收货人：[name]<br/>
        送达时间：[mealtime]<br/>
        菜单：<br/>[goods]<br/>
        电话：[tel]<br/>
        地址：[address]<br>
        备注：[remark]
        ";
            }
            $smsStatus = '';
            //发送短信提醒
            $smsSetting = pdo_fetch("SELECT * FROM " . tablename($this->table_sms_setting) . " WHERE weid=:weid AND storeid=:storeid LIMIT 1", array(':weid' => $weid, ':storeid' => $storeid));
            $sendInfo = array();
            if (!empty($smsSetting)) {
                if ($smsSetting['sms_enable'] == 1 && !empty($smsSetting['sms_mobile'])) {
                    //模板
                    if (empty($smsSetting['sms_business_tpl'])) {
                        $smsSetting['sms_business_tpl'] = '您有新的订单：[sn]，收货人：[name]，电话：[tel]，请及时确认订单！';
                    }
                    //订单号
                    $smsSetting['sms_business_tpl'] = str_replace('[sn]', $order['ordersn'], $smsSetting['sms_business_tpl']);
                    //用户名
                    $smsSetting['sms_business_tpl'] = str_replace('[name]', $order['username'], $smsSetting['sms_business_tpl']);
                    //就餐时间
                    $smsSetting['sms_business_tpl'] = str_replace('[date]', $order['meal_time'], $smsSetting['sms_business_tpl']);
                    //电话
                    $smsSetting['sms_business_tpl'] = str_replace('[tel]', $order['tel'], $smsSetting['sms_business_tpl']);
                    $smsSetting['sms_business_tpl'] = str_replace('[totalnum]', $order['totalnum'], $smsSetting['sms_business_tpl']);
                    $smsSetting['sms_business_tpl'] = str_replace('[totalprice]', $order['totalprice'], $smsSetting['sms_business_tpl']);
                    $smsSetting['sms_business_tpl'] = str_replace('[address]', $order['address'], $smsSetting['sms_business_tpl']);
                    $smsSetting['sms_business_tpl'] = str_replace('[remark]', $order['remark'], $smsSetting['sms_business_tpl']);
                    $smsSetting['sms_business_tpl'] = str_replace('[goods]', $goods_str, $smsSetting['sms_business_tpl']);

                    $sendInfo['username'] = $smsSetting['sms_username'];
                    $sendInfo['pwd'] = $smsSetting['sms_pwd'];
                    $sendInfo['mobile'] = $smsSetting['sms_mobile'];
                    $sendInfo['content'] = $smsSetting['sms_business_tpl'];
                    //debug
                    if ($data['status'] == 1) {
                        if ($order['issms'] == 0) {
                            pdo_update($this->table_order, array('issms' => 1), array('id' => $orderid));
                            $return_result_code = $this->_sendSms($sendInfo);
                            $smsStatus = $this->sms_status[$return_result_code];
                        }
                    }
                }
            }


            $store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid=:weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $storeid));

            if (!empty($emailSetting) && !empty($emailSetting['email'])) {
                $email_tpl = str_replace('[store]', $store['title'], $email_tpl);
                $email_tpl = str_replace('[sn]', $order['ordersn'], $email_tpl);
                $email_tpl = str_replace('[name]', $order['username'], $email_tpl);
                //用户名
                $email_tpl = str_replace('[name]', $order['username'], $email_tpl);
                //就餐时间
                $email_tpl = str_replace('[mealtime]', $order['meal_time'], $email_tpl);
                //电话
                $email_tpl = str_replace('[tel]', $order['tel'], $email_tpl);
                $email_tpl = str_replace('[tables]', $order['tables'], $email_tpl);
                $email_tpl = str_replace('[goods]', $goods_str, $email_tpl);
                $email_tpl = str_replace('[totalnum]', $order['totalnum'], $email_tpl);
                $email_tpl = str_replace('[totalprice]', $order['totalprice'], $email_tpl);
                $email_tpl = str_replace('[address]', $order['address'], $email_tpl);
                $email_tpl = str_replace('[remark]', $order['remark'], $email_tpl);

                if ($emailSetting['email_host'] == 'smtp.qq.com' || $emailSetting['email_host'] == 'smtp.gmail.com') {
                    $secure = 'ssl';
                    $port = '465';
                } else {
                    $secure = 'tls';
                    $port = '25';
                }

                $mail_config = array();
                $mail_config['host'] = $emailSetting['email_host'];
                $mail_config['secure'] = $secure;
                $mail_config['port'] = $port;
                $mail_config['username'] = $emailSetting['email_user'];
                $mail_config['sendmail'] = $emailSetting['email_send'];
                $mail_config['password'] = $emailSetting['email_pwd'];
                $mail_config['mailaddress'] = $emailSetting['email'];
                $mail_config['subject'] = '订单提醒';
                $mail_config['body'] = $email_tpl;

                if ($data['status'] == 1) {
                    if ($order['isemail'] == 0) {
                        pdo_update($this->table_order, array('isemail' => 1), array('id' => $orderid));
                        $result = $this->sendmail($mail_config);
                    }
                }
            }

            $setting = pdo_fetch("select * from " . tablename($this->table_setting) . " where weid =:weid LIMIT 1", array(':weid' => $weid));
            if (!empty($setting) && $setting['istplnotice'] == 1) {
                $templateid = $setting['tplneworder'];
                $noticeuser = $setting['tpluser'];
                $template = array(
                    'touser' => $noticeuser,
                    'template_id' => $templateid,
                    'url' => '',
                    'topcolor' => "#FF0000",
                    'data' => array(
                        'first' => array(
                            'value' => urlencode("您有一个新的订单"),
                            'color' => '#000'
                        ),
                        'keyword1' => array(
                            'value' => urlencode($order['ordersn']),
                            'color' => '#000'
                        ),
                        'keyword2' => array(
                            'value' => urlencode($order['username'] . ' ' . $order['tel']),
                            'color' => '#000'
                        ),
                        'keyword3' => array(
                            'value' => urlencode($goods_tplstr),
                            'color' => '#000'
                        ),
                        'keyword4' => array(
                            'value' => urlencode($order['address']),
                            'color' => '#000'
                        ),
                        'keyword5' => array(
                            'value' => urlencode($order['meal_time']),
                            'color' => '#000'
                        ),
                        'remark' => array(
                            'value' => urlencode('总金额:' . $order['totalprice'] . '元'),
                            'color' => '#f00'
                        ),
                    )
                );

                if ($data['status'] == 1) {
                    if ($order['istpl'] == 0) {
                        pdo_update($this->table_order, array('istpl' => 1), array('id' => $orderid));
                        $templateMessage = new class_templateMessage($this->_appid, $this->_appsecret);
                        $access_token = WeAccount::token();
                        $templateMessage->send_template_message($template, $access_token);
                    }
                }
            }

            $this->feiyinSendFreeMessage($orderid);

            $setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
            $credit = $setting['creditbehaviors']['currency'];
            if ($params['type'] == $credit) {
                message('支付成功！' . $smsStatus, $this->createMobileUrl('order', array('storeid' => $storeid, 'status' => 1), true), 'success');
            } else {
                message('支付成功！' . $smsStatus, '../../app/' . $this->createMobileUrl('order', array('storeid' => $storeid), true), 'success');
            }
        }
    }

    //public

    public function checkModule($name)
    {
        $module = pdo_fetch("SELECT * FROM " . tablename("modules") . " WHERE name=:name ", array(':name' => $name));
        return $module;
    }

    //提示信息
    public function showMessageAjax($msg, $code = 0)
    {
        $result['code'] = $code;
        $result['msg'] = $msg;
        message($result, '', 'ajax');
    }

    public function getStoreID()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;

        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid  ORDER BY id DESC LIMIT 1", array(':weid' => $weid));
        if (!empty($setting)) {
            return intval($setting['storeid']);
        } else {
            $store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . "  WHERE weid={$weid}  ORDER BY id DESC LIMIT 1");
            return intval($store['id']);
        }
    }

    public function doMobileAjaxdelete()
    {
        global $_GPC;
        $delurl = $_GPC['pic'];
        load()->func('file');
        if (file_delete($delurl)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function img_url($img = '')
    {
        global $_W;
        if (empty($img)) {
            return "";
        }
        if (substr($img, 0, 6) == 'avatar') {
            return $_W['siteroot'] . "resource/image/avatar/" . $img;
        }
        if (substr($img, 0, 8) == './themes') {
            return $_W['siteroot'] . $img;
        }
        if (substr($img, 0, 1) == '.') {
            return $_W['siteroot'] . substr($img, 2);
        }
        if (substr($img, 0, 5) == 'http:') {
            return $img;
        }
        return $_W['attachurl'] . $img;
    }

    //发送短信
    public function _sendSms($sendinfo)
    {
        global $_W;
        load()->func('communication');
        $weid = $_W['uniacid'];
        $username = $sendinfo['username'];
        $pwd = $sendinfo['pwd'];
        $mobile = $sendinfo['mobile'];
        $content = $sendinfo['content'];
        $target = "http://www.dxton.com/webservice/sms.asmx/Submit";
        //替换成自己的测试账号,参数顺序和wenservice对应
        $post_data = "account=" . $username . "&password=" . $pwd . "&mobile=" . $mobile . "&content=" . rawurlencode($content);
        //请自己解析$gets字符串并实现自己的逻辑
        //<result>100</result>表示成功,其它的参考文档

        $result = ihttp_request($target, $post_data);
        $xml = simplexml_load_string($result['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
        $result = (string)$xml->result;
        $message = (string)$xml->message;
        return $result;
    }

    public function sendmail($config)
    {
        include 'plugin/email/class.phpmailer.php';
        $mail = new PHPMailer();
        $mail->CharSet = "utf-8";
        $body = $config['body'];
        $mail->IsSMTP();
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = $config['secure']; // sets the prefix to the servier
        $mail->Host = $config['host']; // sets the SMTP server
        $mail->Port = $config['port'];
        $mail->Username = $config['sendmail']; // 发件邮箱用户名
        $mail->Password = $config['password']; // 发件邮箱密码
        $mail->From = $config['sendmail']; //发件邮箱
        $mail->FromName = $config['username']; //发件人名称
        $mail->Subject = $config['subject']; //主题
        $mail->WordWrap = 50; // set word wrap
        $mail->MsgHTML($body);
        $mail->AddAddress($config['mailaddress'], ''); //收件人地址、名称
        $mail->IsHTML(true); // send as HTML
        if (!$mail->Send()) {
            $status = 0;
        } else {
            $status = 1;
        }
        return $status;
    }


    public function showMsg($msg, $status = 0)
    {
        $result = array('msg' => $msg, 'status' => $status);
        echo json_encode($result);
        exit();
    }


    public function getNewCheckCode($checkcode)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_from_user;

        $data = pdo_fetch("SELECT checkcode FROM " . tablename('superdesk_dish_sms_checkcode') . " WHERE weid = :weid AND checkcode = :checkcode AND from_user=:from_user ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':checkcode' => $checkcode, ':from_user' => $from_user));

        if (!empty($data)) {
            $checkcode = random(6, 1);
            $this->getNewCheckCode($checkcode);
        }
        return $checkcode;
    }

    //用户打印机处理订单
    private function stringformat($string, $length = 0, $isleft = true)
    {
        $substr = '';
        if ($length == 0 || $string == '') {
            return $string;
        }
        if (strlen($string) > $length) {
            for ($i = 0; $i < $length; $i++) {
                $substr = $substr . "_";
            }
            $string = $string . '%%' . $substr;
        } else {
            for ($i = strlen($string); $i < $length; $i++) {
                $substr = $substr . " ";
            }
            $string = $isleft ? ($string . $substr) : ($substr . $string);
        }
        return $string;
    }

    private $version = '';

    public function doMobileVersion()
    {
        message($this->version);
    }

    function authorization()
    {
        $host = get_domain();
        return base64_encode($host);
    }

    function code_compare($a, $b)
    {
        if ($this->_debug == 1) {
            if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
                return true;
            }
        }
        if ($a != $b) {
            message(base64_decode("5a+55LiN6LW377yM5oKo5L2/55So55qE57O757uf5piv55Sx6Z2e5rOV5rig6YGT5Lyg5pKt55qE"));
        }
    }

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

    public function oauth2($url)
    {
        global $_GPC, $_W;
        load()->func('communication');
        $code = $_GPC['code'];
        if (empty($code)) {
            message('code获取失败.');
        }
        $token = $this->getAuthorizationCode($code);
        $from_user = $token['openid'];
        $userinfo = $this->getUserInfo($from_user);
        $sub = 1;
        if ($userinfo['subscribe'] == 0) {
            //未关注用户通过网页授权access_token
            $sub = 0;
            $authkey = intval($_GPC['authkey']);
            if ($authkey == 0) {
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
            }
            $userinfo = $this->getUserInfo($from_user, $token['access_token']);
        }

        if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
            echo '<h1>获取微信公众号授权失败[无法取得userinfo], 请稍后重试！ 公众平台返回原始数据为: <br />' . $sub . $userinfo['meta'] . '<h1>';
            exit;
        }

        //设置cookie信息
        setcookie($this->_auth2_headimgurl      , $userinfo['headimgurl'], time() + 3600 * 24);
        setcookie($this->_auth2_nickname        , $userinfo['nickname'], time() + 3600 * 24);
        setcookie($this->_auth2_openid          , $from_user, time() + 3600 * 24);
        setcookie($this->_auth2_sex             , $userinfo['sex'], time() + 3600 * 24);
        return $userinfo;
    }

    public function getUserInfo($from_user, $ACCESS_TOKEN = '')
    {
        if ($ACCESS_TOKEN == '') {
            $ACCESS_TOKEN = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        } else {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        }

        $json = ihttp_get($url);
        $userInfo = @json_decode($json['content'], true);
        return $userInfo;
    }

    public function getAuthorizationCode($code)
    {
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            $oauth2_code = $this->createMobileUrl('waprestlist', array(), true);
            header("location:$oauth2_code");
//            echo '微信授权失败, 请稍后重试! 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit;
        }
        return $token;
    }

    public function getAccessToken()
    {
        global $_W;
        $account = $_W['account'];
        if ($this->_accountlevel < 4) {
            if (!empty($this->_account)) {
                $account = $this->_account;
            }
        }
        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($account['acid']);
        $access_token = $accObj->fetch_token();
        return $access_token;
    }

    public function getCode($url)
    {
        global $_W;
        $url = urlencode($url);
        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
        header("location:$oauth2_code");
    }

    public $actions_titles = array(
        'stores' => '返回门店管理',
        'order' => '订单管理',
        'category' => '商品类别',
        'goods' => '商品管理',
        'intelligent' => '智能选菜',
        'smssetting' => '短信设置',
        'emailsetting' => '邮件设置',
        'printsetting' => '打印机设置',
        'printorder' => '打印订单管理'
        //'storesetting' => '门店设置'
    );

    public $sms_status = array(
        '100' => '发送成功',
        '101' => '验证失败',
        '102' => '手机号码格式不正确',
        '103' => '会员级别不够',
        '104' => '内容未审核',
        '105' => '内容过多',
        '106' => '账户余额不足',
        '107' => 'Ip受限',
        '108' => '手机号码发送太频繁，请换号或隔天再发',
        '109' => '帐号被锁定',
        '110' => '手机号发送频率持续过高，黑名单屏蔽数日',
        '111' => '系统升级',
    );

    public function insert_default_nave($name, $type, $link)
    {
        global $_GPC, $_W;
        checklogin();

        $data = array(
            'weid' => $_W['uniacid'],
            'type' => $type,
            'name' => $name,
            'link' => $link,
            'displayorder' => 0,
            'status' => 1,
        );

        $nave = pdo_fetch("SELECT * FROM " . tablename($this->table_nave) . " WHERE name = :name AND weid=:weid", array(':name' => $name, ':weid' => $_W['uniacid']));

        if (empty($nave)) {
            pdo_insert($this->table_nave, $data);
        }
        return pdo_insertid();
    }


    public function getNaveMenu()
    {
        global $_W, $_GPC;
        $do = $_GPC['do'];
//        message($do);
        $navemenu = array();
        $navemenu[0] = array(
            'title' => '超级前台-点餐',
            'items' => array(
                0 => array('title' => '门店管理', 'url' => $do != 'stores' ? $this->createWebUrl('stores', array('op' => 'display')) : ''),
                1 => array('title' => '订单管理', 'url' => $do != 'order' ? $this->createWebUrl('order', array('op' => 'display')) : ''),
                2 => array('title' => '门店类型', 'url' => $do != 'type' ? $this->createWebUrl('type', array('op' => 'display')) : ''),
                3 => array('title' => '区域管理', 'url' => $do != 'area' ? $this->createWebUrl('area', array('op' => 'display')) : ''),
                4 => array('title' => '黑名单', 'url' => $do != 'blacklist' ? $this->createWebUrl('blacklist', array('op' => 'display')) : ''),
                5 => array('title' => '基本设置', 'url' => $do != 'setting' ? $this->createWebUrl('setting', array('op' => 'display')) : ''),
            )
        );


        return $navemenu;
    }


    protected function exportexcel($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);

            }
            echo implode("\n", $data);
        }
    }

    //设置订单积分
    public function setOrderCredit($orderid, $add = true)
    {
        $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id LIMIT 1", array(':id' => $orderid));
        if (empty($order)) {
            return false;
        }

        $ordergoods = pdo_fetchall("SELECT goodsid, total FROM " . tablename($this->table_order_goods) . " WHERE orderid = :orderid", array(':orderid' => $orderid));
        if (!empty($ordergoods)) {
            $credit = 0.00;
            $sql = 'SELECT `credit` FROM ' . tablename($this->table_goods) . ' WHERE `id` = :id';
            foreach ($ordergoods as $goods) {
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
                $uid = intval($fans['uid']);
                $remark = $add == true ? '微点餐积分奖励 订单ID:' . $orderid : '微点餐积分扣除 订单ID:' . $orderid;
                $log = array();
                $log[0] = $uid;
                $log[1] = $remark;
                mc_credit_update($uid, 'credit1', $credit, $log);
            }
        }
        return true;
    }

    /*
    ** 设置切换导航
    */
    public function set_tabbar($action, $storeid)
    {
        $actions_titles = $this->actions_titles;
        $html = '<ul class="nav nav-tabs">';
        foreach ($actions_titles as $key => $value) {
            $url = $this->createWebUrl($key, array('op' => 'display', 'storeid' => $storeid));
            $html .= '<li class="' . ($key == $action ? 'active' : '') . '"><a href="' . $url . '">' . $value . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }


    function uploadFile($file, $filetempname, $array)
    {
        //自己设置的上传文件存放路径
        $filePath = '../addons/superdesk_dish/upload/';

        //require_once '../addons/superdesk_dish/plugin/phpexcelreader/reader.php';
        include 'plugin/phpexcelreader/reader.php';

        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('utf-8');

        //$filepath = './source/modules/iteamlotteryv2/data_' . $weid . '.xls';
        //$tmp = $_FILES['fileexcel']['tmp_name'];

        //注意设置时区
        $time = date("y-m-d-H-i-s"); //去当前上传的时间
        $extend = strrchr($file, '.');
        //上传后的文件名
        $name = $time . $extend;
        $uploadfile = $filePath . $name; //上传后的文件名地址

        //$filetype = $_FILES['fileexcel']['type'];

        if (copy($filetempname, $uploadfile)) {
            if (!file_exists($filePath)) {
                echo '文件路径不存在.';
                return;
            }
            if (!is_readable($uploadfile)) {
                echo("文件为只读,请修改文件相关权限.");
                return;
            }
            $data->read($uploadfile);
            error_reporting(E_ALL ^ E_NOTICE);
            $count = 0;
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { //$=2 第二行开始
                //以下注释的for循环打印excel表数据
                for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
                    //echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
                }

                $row = $data->sheets[0]['cells'][$i];
                //message($data->sheets[0]['cells'][$i][1]);

                if ($array['ac'] == "category") {
                    $count = $count + $this->upload_category($row, TIMESTAMP, $array);
                } else if ($array['ac'] == "goods") {
                    $count = $count + $this->upload_goods($row, TIMESTAMP, $array);
                } else if ($array['ac'] == "store") {
                    $count = $count + $this->upload_store($row, TIMESTAMP, $array);
                }
            }
        }
        if ($count == 0) {
            $msg = "导入失败！";
        } else {
            $msg = 1;
        }

        return $msg;
    }

    private function checkUploadFileMIME($file)
    {
        // 1.through the file extension judgement 03 or 07
        $flag = 0;
        $file_array = explode(".", $file ["name"]);
        $file_extension = strtolower(array_pop($file_array));

        // 2.through the binary content to detect the file
        switch ($file_extension) {
            case "xls" :
                // 2003 excel
                $fh = fopen($file ["tmp_name"], "rb");
                $bin = fread($fh, 8);
                fclose($fh);
                $strinfo = @unpack("C8chars", $bin);
                $typecode = "";
                foreach ($strinfo as $num) {
                    $typecode .= dechex($num);
                }
                if ($typecode == "d0cf11e0a1b11ae1") {
                    $flag = 1;
                }
                break;
            case "xlsx" :
                // 2007 excel
                $fh = fopen($file ["tmp_name"], "rb");
                $bin = fread($fh, 4);
                fclose($fh);
                $strinfo = @unpack("C4chars", $bin);
                $typecode = "";
                foreach ($strinfo as $num) {
                    $typecode .= dechex($num);
                }
                echo $typecode . 'test';
                if ($typecode == "504b34") {
                    $flag = 1;
                }
                break;
        }

        // 3.return the flag
        return $flag;
    }

    function upload_goods($strs, $time, $array)
    {
        global $_W;
        $insert = array();

        //类别处理
        $category = pdo_fetch("SELECT id FROM " . tablename('superdesk_dish_category') . " WHERE name=:name AND weid=:weid AND storeid=:storeid", array(':name' => $strs[2], ':weid' => $_W['uniacid'], ':storeid' => $array['storeid']));
        $insert['pcate'] = empty($category) ? 0 : intval($category['id']);
        $insert['title'] = $strs[1];
        $insert['thumb'] = $strs[3];
        $insert['unitname'] = $strs[4];
        $insert['description'] = $strs[5];
        $insert['taste'] = $strs[6];
        $insert['isspecial'] = $strs[7];
        $insert['marketprice'] = $strs[8];
        $insert['productprice'] = $strs[9];
        $insert['subcount'] = $strs[10];
        $insert['credit'] = $strs[11];
        $insert['dateline'] = TIMESTAMP;
        $insert['status'] = 1;
        $insert['recommend'] = 0;
        $insert['ccate'] = 0;
        $insert['storeid'] = $array['storeid'];
        $insert['weid'] = $_W['uniacid'];

        $goods = pdo_fetch("SELECT * FROM " . tablename('superdesk_dish_goods') . " WHERE title=:title AND weid=:weid AND storeid=:storeid", array(':title' => $strs[1], ':weid' => $_W['uniacid'], ':storeid' => $array['storeid']));

        if (empty($goods)) {
            return pdo_insert('superdesk_dish_goods', $insert);
        } else {
            return 0;
        }
    }

    function upload_category($strs, $time, $array)
    {
        global $_W;
        $insert = array();
        $insert['name'] = $strs[1];
        $insert['parentid'] = 0;
        $insert['displayorder'] = 0;
        $insert['enabled'] = 1;
        $insert['storeid'] = $array['storeid'];
        $insert['weid'] = $_W['uniacid'];

        $category = pdo_fetch("SELECT * FROM " . tablename('superdesk_dish_category') . " WHERE name=:name AND weid=:weid AND storeid=:storeid", array(':name' => $strs[1], ':weid' => $_W['uniacid'], ':storeid' => $array['storeid']));

        if (empty($category)) {
            return pdo_insert('superdesk_dish_category', $insert);
        } else {
            return 0;
        }
    }

    function upload_store($strs, $time, $array)
    {
        global $_W;
        $insert = array();
        //类别处理
        $insert['weid'] = $_W['uniacid'];
        $insert['title'] = $strs[1];
        $insert['info'] = $strs[2];
        $insert['logo'] = $strs[3];
        $insert['content'] = $strs[4];
        $insert['tel'] = $strs[5];
        $insert['address'] = $strs[6];
        $insert['place'] = $strs[6];
        $insert['hours'] = $strs[7];
        $insert['location_p'] = $strs[8];
        $insert['location_c'] = $strs[9];
        $insert['location_a'] = $strs[10];
        $insert['password'] = '';
        $insert['recharging_password'] = '';
        $insert['is_show'] = 1;
        $insert['areaid'] = 0;
        $insert['lng'] = '0.000000000';
        $insert['lat'] = '0.000000000';
        $insert['enable_wifi'] = 1;
        $insert['enable_card'] = 1;
        $insert['enable_room'] = 1;
        $insert['enable_park'] = 1;
        $insert['updatetime'] = TIMESTAMP;
        $insert['dateline'] = TIMESTAMP;

        $store = pdo_fetch("SELECT * FROM " . tablename('superdesk_dish_stores') . " WHERE title=:title AND weid=:weid LIMIT 1", array(':title' => $strs[1], ':weid' => $_W['uniacid']));

        if (empty($store)) {
            return pdo_insert('superdesk_dish_stores', $insert);
        } else {
            return 0;
        }
    }


    public function message($error, $url = '', $errno = -1)
    {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }

    public function checkStoreHour($begintime, $endtime)
    {
        global $_W, $_GPC;

        $nowtime = intval(date("Hi"));
        $begintime = intval(str_replace(':', '', $begintime));
        $endtime = intval(str_replace(':', '', $endtime));

        if ($nowtime >= $begintime && $nowtime <= $endtime) {
            return 1;
        }
        return 0;
    }


    //----------------------以下是接口定义实现，第三方应用可根据具体情况直接修改----------------------------
    function sendFreeMessage($msg)
    {
        $msg['reqTime'] = number_format(1000 * time(), 0, '', '');
        $content = $msg['memberCode'] . $msg['msgDetail'] . $msg['deviceNo'] . $msg['msgNo'] . $msg['reqTime'] . $this->feyin_key;
        $msg['securityCode'] = md5($content);
        $msg['mode'] = 2;

        return $this->sendMessage($msg);
    }

    function sendFormatedMessage($msgInfo)
    {
        $msgInfo['reqTime'] = number_format(1000 * time(), 0, '', '');
        $content = $msgInfo['memberCode'] . $msgInfo['customerName'] . $msgInfo['customerPhone'] . $msgInfo['customerAddress'] . $msgInfo['customerMemo'] . $msgInfo['msgDetail'] . $msgInfo['deviceNo'] . $msgInfo['msgNo'] . $msgInfo['reqTime'] . $this->feyin_key;

        $msgInfo['securityCode'] = md5($content);
        $msgInfo['mode'] = 1;

        return $this->sendMessage($msgInfo);
    }


    function sendMessage($msgInfo)
    {
        $client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
        if (!$client->post('/api/sendMsg', $msgInfo)) { //提交失败
            return 'faild';
        } else {
            return $client->getContent();
        }
    }

    function queryState($msgNo)
    {
        $now = number_format(1000 * time(), 0, '', '');
        $client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
        if (!$client->get('/api/queryState?memberCode=' . $this->member_code . '&reqTime=' . $now . '&securityCode=' . md5($this->member_code . $now . $this->feyin_key . $msgNo) . '&msgNo=' . $msgNo)) { //请求失败
            return 'faild';
        } else {
            return $client->getContent();
        }
    }

    function listDevice()
    {
        $now = number_format(1000 * time(), 0, '', '');
        $client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
        if (!$client->get('/api/listDevice?memberCode=' . $this->member_code . '&reqTime=' . $now . '&securityCode=' . md5($this->member_code . $now . $this->feyin_key))) { //请求失败
            return 'faild';
        } else {
            /***************************************************
             * 解释返回的设备状态
             * 格式：
             * <device id="4600006007272080">
             * <address>广东**</address>
             * <since>2010-09-29</since>
             * <simCode>135600*****</simCode>
             * <lastConnected>2011-03-09  19:39:03</lastConnected>
             * <deviceStatus>离线 </deviceStatus>
             * <paperStatus></paperStatus>
             * </device>
             **************************************************/

            $xml = $client->getContent();
            $sxe = new SimpleXMLElement($xml);
            foreach ($sxe->device as $device) {
                $id = $device['id'];
                echo "设备编码：$id    ";

                $deviceStatus = $device->deviceStatus;
                echo "状态：$deviceStatus";
                echo '<br>';
            }
        }
    }

    function listException()
    {
        $now = number_format(1000 * time(), 0, '', '');
        $client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
        if (!$client->get('/api/listException?memberCode=' . MEMBER_CODE . '&reqTime=' . $now . '&securityCode=' . md5(MEMBER_CODE . $now . $this->feyin_key))) { //请求失败
            return 'faild';
        } else {
            return $client->getContent();
        }
    }

    function feiyinstatus($code)
    {
        switch ($code) {
            case 0:
                $text = "正常";
                break;
            case -1:
                $text = "IP地址不允许";
                break;
            case -2:
                $text = "关键参数为空或请求方式不对";
                break;
            case -3:
                $text = "客户编码不对";
                break;
            case -4:
                $text = "安全校验码不正确";
                break;
            case -5:
                $text = "请求时间失效";
                break;
            case -6:
                $text = "订单内容格式不对";
                break;
            case -7:
                $text = "重复的消息";
                break;
            case -8:
                $text = "消息模式不对";
                break;
            case -9:
                $text = "服务器错误";
                break;
            case -10:
                $text = "服务器内部错误";
                break;
            case -111:
                $text = "打印终端不属于该账户";
                break;
            default:
                $text = "未知";
                break;
        }
        return $text;
    }

    public $copyright = '110010 111000 110100 110100 110110 110001 110010 111000 110000';

    public function sv()
    {
        echo $this->copyright;
    }
}