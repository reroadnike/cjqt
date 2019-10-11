<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Info_SuperdeskShopV2Page extends PcMobileLoginPage
{
    protected $member;

    public function __construct()
    {
        global $_W;
        global $_GPC;
        parent::__construct();
        $this->member = m('member')->getInfo($_W['openid'], $_W['core_user']);
    }

    protected function diyformData()
    {
        $template_flag = 0;

        $diyform_plugin = p('diyform');

        if ($diyform_plugin) {

            $set_config = $diyform_plugin->getSet();

            $user_diyform_open = $set_config['user_diyform_open'];

            if ($user_diyform_open == 1) {

                $template_flag = 1;

                $diyform_id = $set_config['user_diyform'];

                if (!(empty($diyform_id))) {

                    $formInfo = $diyform_plugin->getDiyformInfo($diyform_id);

                    $fields = $formInfo['fields'];

                    $diyform_data = iunserializer($this->member['diymemberdata']);

                    $f_data = $diyform_plugin->getDiyformData($diyform_data, $fields, $this->member);
                }
            }
        }
        return array(
            'template_flag'  => $template_flag,
            'set_config'     => $set_config,
            'diyform_plugin' => $diyform_plugin,
            'formInfo'       => $formInfo,
            'diyform_id'     => $diyform_id,
            'diyform_data'   => $diyform_data,
            'fields'         => $fields,
            'f_data'         => $f_data
        );
    }

    /**
     * vue的pc端这个没用上
     */
    public function main()
    {
        global $_W;
        global $_GPC;

        //$diyform_data = $this->diyformData();
        //extract($diyform_data);

        $returnurl = urldecode(trim($_GPC['returnurl']));

        $member = $this->member;

        // 只返回这个东西
        $member = [
            'mobile'   => $member['mobile'],
            'realname' => $member['realname'],
            'weixin'   => $member['weixin']
        ];

        $wapset = m('common')->getSysset('wap');

        $ice_menu_array = array(array(
            'menu_key'  => 'index',
            'menu_name' => '账户信息',
            'menu_url'  => mobileUrl('pc.member.info')
        ));

        $nav_link_list = array(
            array('link' => mobileUrl('pc'), 'title' => '首页'),
            array('link' => mobileUrl('pc.member'), 'title' => '我的商城'),
            array('title' => '账户信息')
        );

        show_json(1, compact('member', 'returnurl'));
    }

    public function submit()
    {
        global $_W;
        global $_GPC;

        $diyform_data = $this->diyformData();
        extract($diyform_data);

        $memberdata = $_GPC['memberdata'];

        if ($template_flag == 1) {

            $data    = array();
            $m_data  = array();
            $mc_data = array();

            $insert_data = $diyform_plugin->getInsertData($fields, $memberdata);

            $data    = $insert_data['data'];
            $m_data  = $insert_data['m_data'];
            $mc_data = $insert_data['mc_data'];

            $m_data['diymemberid']     = $diyform_id;
            $m_data['diymemberfields'] = iserializer($fields);
            $m_data['diymemberdata']   = $data;

            pdo_update(
                "superdesk_shop_member",
                $m_data,
                array(
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                    'uniacid'   => $_W['uniacid']
                )
            );

            if (!(empty($this->member['uid']))) {

                if (!(empty($mc_data))) {

                    m('member')->mc_update($this->member['uid'], $mc_data);
                }
            }

        } else {

            pdo_update(
                "superdesk_shop_member",
                $memberdata,
                array(
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                    'uniacid'   => $_W['uniacid']
                )
            );

            if (!(empty($this->member['uid']))) {

                $mcdata = $_GPC['mcdata'];

                m("member")->mc_update($this->member['uid'], $mcdata);
            }
        }

        show_json(1);
    }

    public function setMemberAvatar()
    {
        global $_W, $_GPC;

        // 来源 上传图片的接口在
        // /data/wwwroot/default/superdesk/addons/superdesk_shopv2/plugin/pc/core/mobile/api/index.php uploadFile
        $avatar_url = $_GPC['avatar_url'];

        if (empty($avatar_url)) {
            show_json(0, '请选择图片');
        }

        $data = array('avatar' => $avatar_url);

        pdo_update(
            "superdesk_shop_member",
            $data,
            array(
                'openid' => $_W['openid'],
                'uniacid' => $_W['uniacid']
            )
        );

        show_json(1);
    }
}