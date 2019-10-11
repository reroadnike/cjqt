

bug_修正商户可见潜在的问题_弃用_cookie_转_W_superdesk_shop_member_2_merch_ids


```
cookie 2 _W['superdesk_shop_member_2_merch_ids']
```

/data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/model/goods.php

```
//否则就是开启了多商户但是没商户id的,添加默认全局可见的商户
$condition .= ' and checked = 0';

//获取全局可见商户
$merch_ids = $_W['superdesk_shop_member_2_merch_ids'];

if (!empty($merch_ids)) {
    $condition .= ' and merchid in ( ' . $merch_ids . ')';
}
```

/data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/model/account.php

```
/**
     * 此方法 修正
     * cookie 2 _W['superdesk_shop_member_2_merch_ids']
     * @param $member
     */
    public function setFilterMerch($member)
    {
        global $_W;
        global $_GPC;

        $superdesk_shop_member_2_merch_ids = $this->_plugin_merchService->getMerchByEnterpriseId($member['core_enterprise']);
        $_W['superdesk_shop_member_2_merch_ids'] = $superdesk_shop_member_2_merch_ids;
        socket_log("account.setFilterMerch::" . "商户ids" . "::" . $superdesk_shop_member_2_merch_ids . ";企业id" . "::" . $member['core_enterprise']);

        // 此方式 弃用
//        $_cookie_key = '__superdesk_shopv2_merchids_session_' . $_W['uniacid'];
//        isetcookie($_cookie_key, $merchids);


    }
```