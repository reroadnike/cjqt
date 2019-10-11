<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require_once IA_ROOT . '/addons/superdesk_shopv2/version.php';
require_once IA_ROOT . '/addons/superdesk_shopv2/defines.php';

require_once SUPERDESK_SHOPV2_INC . 'functions.php';

class Superdesk_ShopV2Module extends WeModule
{
    public function welcomeDisplay()
    {
        header('location: ' . webUrl());
        exit();
    }


    public function fieldsFormDisplay($rid = 0)
    {
        //要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
    }

//    public function fieldsFormDisplay($rid = 0)
//    {
//        global $_W;
//        $setting = $_W['account']['modules'][$this->_saveing_params['mid']]['config'];
//        include $this->template('rule');
//    }

    public function fieldsFormValidate($rid = 0)
    {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function fieldsFormSubmit($rid)
    {
        //规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
    }

    public function ruleDeleted($rid)
    {
        //删除规则时调用，这里 $rid 为对应的规则编号
    }


    public function settingsDisplay($settings)
    {
        global $_GPC, $_W;


        if (checksubmit()) {
            $cfg = array(
                'jd_vop_order_submit_bNeedGift' => $_GPC['jd_vop_order_submit_bNeedGift'] ? intval($_GPC['jd_vop_order_submit_bNeedGift']) : 0, //   表示是否需要增品，默认不给增品，默认值为：false，如果需要增品bNeedGift请给true,建议该参数都给true,但如果实在不需要增品可以给false;
            );
            $this->saveSettings($cfg);
            message('设置成功!');
        }

        require_once(IA_ROOT . '/addons/superdesk_shopv2/service/util/setting/SettingService.class.php');
        $SettingService = new SettingService();
        $settings       = $SettingService->getSetting();

//        require_once(IA_ROOT . '/addons/superdesk_shopv2/service/util/setting/SettingService.class.php');
//        $SettingService = new SettingService();
//        $bNeedGift = $SettingService->getSettingColumn();
//        var_dump($bNeedGift);die;

        load()->func('tpl');

        include $this->template('setting');
    }
}