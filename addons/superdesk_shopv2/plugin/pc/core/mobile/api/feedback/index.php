<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Index_SuperdeskShopV2Page extends PcMobileLoginPage
{
    public function main()
    {
        global $_GPC, $_W;

        $from_user = $_W['openid'];

        $member = m('member')->getInfo($_W['openid'], $_W['core_user']);

        $content    = trim($_GPC['content']);
        $issue_type = trim($_GPC['issue_type']);

        $username   = $member['realname'];
        $nickname   = $member['nickname'];
        $headimgurl = $member['avatar'];
        $telphone   = $member['mobile'];

        if (empty($content)) {
            show_json(0, '请输入反馈信息!');
        }

        $setting = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_feedback_setting') .
            ' where 1 ' .
            '       and uniacid =:uniacid ' .
            ' LIMIT 1',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($setting)) {
            $status = 0;
        } else {
            $status = intval($setting['ischeck']) == 1 ? 0 : 1;
        }

        $data = array(
            'uniacid'    => $_W['uniacid'],
            'from_user'  => $from_user,
            'parentid'   => 0,
            'username'   => $username,
            'nickname'   => $nickname,
            'headimgurl' => $headimgurl,
            'status'     => $status,
            'telphone'   => $telphone,
            'issue_type' => $issue_type,
            'content'    => $content,
            'dateline'   => TIMESTAMP
        );

        pdo_insert('superdesk_feedback_feedback', $data);

        show_json(1);
    }
}
