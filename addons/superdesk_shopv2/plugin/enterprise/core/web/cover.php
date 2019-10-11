<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Cover_SuperdeskShopV2Page extends PluginWebPage
{
    public function main()
    {
        if (cv('enterprise.cover.register')) {
            header('location: ' . webUrl('enterprise/cover/register'));
            return;
        }


        if (cv('enterprise.cover.enterprise_list')) {
            header('location: ' . webUrl('enterprise/cover/enterprise_list'));
        }

    }

    protected function _cover($key, $name, $url)
    {
        global $_W;
        global $_GPC;
        $rule    = pdo_fetch('select * from ' . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'cover', ':name' => 'superdesk_shopv2' . $name . '入口设置'));
        $keyword = false;
        $cover   = false;

        if (!empty($rule)) {
            $keyword = pdo_fetch('select * from ' . tablename('rule_keyword') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rule['id']));
            $cover   = pdo_fetch('select * from ' . tablename('cover_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rule['id']));
        }


        if ($_W['ispost']) {
            ca('enterprise.cover.edit');
            $data = ((is_array($_GPC['cover']) ? $_GPC['cover'] : array()));

            if (empty($data['keyword'])) {
                show_json(0, '请输入关键词!');
            }


            $keyword1 = m('common')->keyExist($data['keyword']);

            if (!empty($keyword1)) {
                if ($keyword1['name'] != 'superdesk_shopv2' . $name . '入口设置') {
                    show_json(0, '关键字已存在!');
                }

            }


            if (!empty($rule)) {
                pdo_delete('rule', array('id' => $rule['id'], 'uniacid' => $_W['uniacid']));
                pdo_delete('rule_keyword', array('rid' => $rule['id'], 'uniacid' => $_W['uniacid']));
                pdo_delete('cover_reply', array('rid' => $rule['id'], 'uniacid' => $_W['uniacid']));
            }


            $rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'superdesk_shopv2' . $name . '入口设置', 'module' => 'cover', 'displayorder' => 0, 'status' => intval($data['status']));
            pdo_insert('rule', $rule_data);
            $rid          = pdo_insertid();
            $keyword_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'cover', 'content' => trim($data['keyword']), 'type' => 1, 'displayorder' => 0, 'status' => intval($data['status']));
            pdo_insert('rule_keyword', $keyword_data);
            $cover_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'superdesk_shopv2', 'title' => trim($data['title']), 'description' => trim($data['desc']), 'thumb' => save_media($data['thumb']), 'url' => $url);
            pdo_insert('cover_reply', $cover_data);
            plog('enterprise.cover.' . $key . '.edit', '修改入口设置');
            show_json(1);
        }


        return array('rule' => $rule, 'cover' => $cover, 'keyword' => $keyword, 'url' => $_W['siteroot'] . 'app/' . substr($url, 2), 'name' => $name, 'key' => $key);
    }

    public function register()
    {
        global $_W;
        global $_GPC;
        $cover = $this->_cover('register', '入驻申请', mobileUrl('enterprise/register', array(), false));
        include $this->template('enterprise/cover');
    }

    public function app()
    {
        global $_W;
        global $_GPC;
        $cover = $this->_cover('app', '商家微信管理端', mobileUrl('enterprise', array(), false));
        include $this->template('enterprise/cover');
    }

    public function enterprise_list()
    {
        global $_W;
        global $_GPC;
        $cover = $this->_cover('app', '企业导航', mobileUrl('enterprise/list', array(), false));
        include $this->template('enterprise/cover');
    }

    public function enterprise_user()
    {
        global $_W;
        global $_GPC;
        $cover = $this->_cover('app', '企业导航(含定位距离)', mobileUrl('enterprise/list/enterprise_user', array(), false));
        include $this->template('enterprise/cover');
    }
}


?>