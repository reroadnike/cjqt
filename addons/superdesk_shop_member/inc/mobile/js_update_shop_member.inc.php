<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/15/18
 * Time: 3:03 PM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shop_member&do=js_update_shop_member
 *
 * DELETE FROM `ims_superdesk_shop_member_invoice` WHERE id > 4 569
 * DELETE FROM `ims_superdesk_shop_member_address` WHERE id > 7 1771
 * DELETE FROM `ims_superdesk_shop_member` WHERE id > 2 2008
 */


global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_import_member.class.php');
$_zc_import_memberModel = new zc_import_memberModel();
include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_address.class.php');
$_sll_addressModel = new sll_addressModel();
include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_invoice.class.php');
$_zc_invoiceModel = new zc_invoiceModel();


include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member.class.php');
$_memberModel = new memberModel();
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_address.class.php');
$_member_addressModel = new member_addressModel();
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_invoice.class.php');
$_member_invoiceModel = new member_invoiceModel();


$page = $_GPC['page'];
echo $page;
$page_size = 2000;

$result    = $_zc_import_memberModel->queryAll(array(), $page, $page_size);
$total     = $result['total'];
$page      = $result['page'];
$page_size = $result['page_size'];
$list      = $result['data'];


//$mobile     = trim($_GPC['mobile']);
//$verifycode = trim($_GPC['verifycode']);
//$pwd        = trim($_GPC['pwd']);
//
//if (empty($mobile)) {
//    show_json(0, '请输入正确的手机号');
//}
//
//if (empty($verifycode)) {
//    show_json(0, '请输入验证码');
//}
//
//if (empty($pwd)) {
//    show_json(0, '请输入密码');
//}
//
//$key = '__superdesk_shop_member_verifycodesession_' . $_W['uniacid'] . '_' . $mobile;


// 0:明细 1:电脑配件  2:耗材  3: 办公用品 备注:若增值发票则只能选0 明细
// 转换
// 1:明细 3：电脑配件 19:耗材 22：办公用品 备注:若增值发票则只能选1 明细
$transform = array(
    0 => 1,
    1 => 3,
    2 => 19,
    3 => 22,
);


foreach ($list as $index => $source_member) {

//    if (empty($source_member['m_e_id'])) {
//        //有两个脏数据
////        '1187', '被微营销的刘文锦', '18575689350', '', '1', '2017-07-14 17:03:06', '18575689350', '626', NULL, '4710', NULL, '6', '40', '', NULL, '', NULL, NULL, '', '', NULL
////        '2235', '小旋', '15017906272', '', '1', '2017-11-16 14:34:39', '15017906272', '3854', NULL, '5606', NULL, '12', '1470', '', NULL, '', NULL, NULL, '', '', NULL
//
//        continue;
//    }

    $wx_fans_id = $source_member['m_fansId'];
    $mobile     = $source_member['m_account'];
    $pwd        = "123456";

    echo "<=====================================================================================================================================================================================================>";
    echo "<br/>";
    echo $mobile;
    echo "<br/>";

    $_member  = pdo_fetch(
        ' select id,openid,mobile,pwd,salt ' .
        ' from ' . tablename('superdesk_shop_member') .
        ' where mobile=:mobile ' .
        '       and mobileverify=1 ' .
        '       and uniacid=:uniacid ' .
        ' limit 1',
        array(
            ':mobile'  => $mobile,
            ':uniacid' => $_W['uniacid']
        )
    );
    $_address = $_sll_addressModel->queryByColumn(array(
        'fansid' => $wx_fans_id
    ));
    $_invoice = $_zc_invoiceModel->queryByColumn(array(
        'zi_fansid' => $wx_fans_id
    ));


    if (!(empty($_member))) {

        echo('手机号 '.$mobile.' 已注册,默认123456,请直接登录');
        echo "<br/>";
    }

    $salt = ((empty($_member) ? '' : $_member['salt']));

    if (empty($salt)) {
        $salt = m('account')->getSalt();
    }

    $openid   = ((empty($_member) ? '' : $_member['openid']));
    $nickname = ((empty($_member) ? '' : $_member['nickname']));

    if (empty($openid)) {
        $openid   = 'wap_user_' . $_W['uniacid'] . '_' . $mobile;
        $nickname = substr($mobile, 0, 3) . 'xxxx' . substr($mobile, 7, 4);
    }

    $data_shop_member = array(
        'uniacid' => $_W['uniacid'],
        'openid'  => $openid,

        'nickname' => $nickname,

        'pwd'          => md5($pwd . $salt),
        'salt'         => $salt,
        'createtime'   => time(),
        'mobileverify' => 1,
        'comefrom'     => 'mobile',


        'realname'        => $source_member['m_name'],
        'mobile'          => $mobile,
        'core_enterprise' => $source_member['m_e_id']
    );

    $data_shop_address = array();
    foreach ($_address as $index => $_address_item) {


        $data_shop_address_tmp = array(
            'uniacid' => $_W['uniacid'],
            'openid'  => $openid,

            'realname'  => $_address_item['user_name'],
            'mobile'    => $_address_item['phone'],
            'address'   => $_address_item['address_name'],
            'deleted'   => $_address_item['address_state'],
            'isdefault' => $_address_item['address_default'],

            'province' => $_address_item['province'],
            'city'     => $_address_item['city'],
            'area'     => $_address_item['country'],
            'town'     => $_address_item['street'],//'四级地址'

            'jd_vop_province_code' => $_address_item['provinceCode'],//'京东一级province_code'
            'jd_vop_city_code'     => $_address_item['citycode'],//'京东二级city_code'
            'jd_vop_county_code'   => $_address_item['countryCode'],//'京东三级county_code'
            'jd_vop_town_code'     => $_address_item['streetCode'],//'京东四级town_code'

            'jd_vop_area' => $_address_item['provinceCode'] . '_' . $_address_item['citycode'] . '_' . $_address_item['countryCode'] . (empty($_address_item['streetCode']) ? '' : '_' . $_address_item['streetCode'])//'用于查库存与下单 格式：1_0_0 (分别代表1、2、3级地址)'

        );

        $_member_addressModel->saveOrUpdateByColumn($data_shop_address_tmp,$data_shop_address_tmp);

        $data_shop_address[] = $data_shop_address_tmp;
    }

    $data_shop_invoice = array();
    foreach ($_invoice as $index => $_invoice_item) {
        $data_shop_invoice_tmp = array(
            'uniacid' => $_W['uniacid'],
            'openid'  => $openid,

            'invoiceType'          => $_invoice_item['zi_invoiceType'],//1:普通发票2:增值税发票
            'selectedInvoiceTitle' => empty($_invoice_item['zi_selectedInvoiceTitle']) ? $_invoice_item['zi_selectedInvoiceTitle'] : intval($_invoice_item['zi_selectedInvoiceTitle']) + 3,//1:个人2:单位 // 转换 发票类型：4个人，5单位
            'companyName'          => $_invoice_item['zi_companyName'],//发票抬头
            'taxpayersIDcode'      => $_invoice_item['zi_taxpayer_identification_number'],//纳税人识别码
            'invoiceContent'       => empty($_invoice_item['zi_invoiceContent']) ? $_invoice_item['zi_invoiceContent'] : $transform[$_invoice_item['zi_invoiceContent']],//0:明细1:电脑配件2:耗材3:办公用品 // 转换 1:明细，3：电脑配件，19:耗材，22：办公用品 备注:若增值发票则只能选1 明细
            'invoiceAddress'       => $_invoice_item['zi_invoiceAddress'],//增值票注册地址
            'invoiceName'          => $_invoice_item['zi_invoiceName'],//增值票收票人姓名
            'invoicePhone'         => $_invoice_item['zi_invoicePhone'],//增值票注册电话
            'invoiceBank'          => $_invoice_item['zi_invoiceBank'],//增值票开户银行
            'invoiceAccount'       => $_invoice_item['zi_invoiceAccount'],//增值票开户帐号
            'isdefault'            => 1
        );

        $_member_invoiceModel->saveOrUpdateByColumn($data_shop_invoice_tmp,$data_shop_invoice_tmp);

        $data_shop_invoice[] = $data_shop_invoice_tmp;
    }


    echo "shop_member";
    echo '<br>';
    echo json_encode($data_shop_member,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    echo '<br>';

    echo "shop_address";
    echo '<br>';
    echo json_encode($data_shop_address,JSON_UNESCAPED_UNICODE);
    echo '<br>';

    echo "shop_invoice";
    echo '<br>';
    echo json_encode($data_shop_invoice,JSON_UNESCAPED_UNICODE);
    echo '<br>';


    echo "<=====================================================================================================================================================================================================>";
    echo '<br>';
    echo '<br>';
    echo '<br>';

    if (empty($_member)) {
        $_memberModel->insert($data_shop_member);
    } else {


        unset($data_shop_member['uniacid']);
        unset($data_shop_member['openid']);
        unset($data_shop_member['nickname']);
        unset($data_shop_member['createtime']);
        unset($data_shop_member['mobile']);


        $_memberModel->update($data_shop_member, $_member['id']);
    }

//if (p("commission")) {
//    p("commission")->checkAgent($openid);
//}

}


