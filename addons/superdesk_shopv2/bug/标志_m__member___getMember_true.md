

TODO 楼宇之窗 对接BUG

// TODO 调用错误 CORE_USER ADD
// TODO 分割BUG





//UPDATE `ims_superdesk_shop_member` SET core_user = 0;
//UPDATE `ims_superdesk_shop_member_address` SET core_user = 0;
//UPDATE `ims_superdesk_shop_member_cart` SET core_user = 0; // 未全部标记 老PC端还没有改
//UPDATE `ims_superdesk_shop_member_credit_log` SET core_user = 0;
//UPDATE `ims_superdesk_shop_member_favorite` SET core_user = 0; // 已全部标记 已好
//UPDATE `ims_superdesk_shop_member_history` SET core_user = 0; // 已全部标记 /data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/web/system/data/index.php 待处理
//UPDATE `ims_superdesk_shop_member_invoice` SET core_user = 0; // 已全部标记 少量 待处理
//UPDATE `ims_superdesk_shop_member_log` SET core_user = 0; // 已全部标记 一般 待处理


// TODO 标志 m('member')->getMember true


m('member')->getMember($_W['openid']);

m('member')->getMember($_W['openid'], $_W['core_user']);


有bug
/data/wwwroot/default/superdesk/addons/superdesk_shopv2/plugin/pc/core/mobile/api/order/pay.php

# shop_member

// TODO 标志 楼宇之窗返回多 shop_member 待处理

// TODO 标志 楼宇之窗 openid shop_member 待处理

// TODO 以openid维度




# superdesk_shop_member_address

// TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
// TODO 标志 楼宇之窗 openid superdesk_shop_member_address 待处理 1

# superdesk_shop_member_cart

// TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理


# superdesk_shop_member_favorite

// TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理



# superdesk_shop_member_history

// TODO 标志 楼宇之窗 openid superdesk_shop_member_history 已处理



# superdesk_shop_member_invoice

// TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 待处理
// TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理



# superdesk_shop_member_log

// TODO 标志 楼宇之窗 openid superdesk_shop_member_log 待处理
















