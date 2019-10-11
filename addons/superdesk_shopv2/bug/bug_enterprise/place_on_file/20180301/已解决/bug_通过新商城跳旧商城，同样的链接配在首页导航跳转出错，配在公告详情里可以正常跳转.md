


通过新商城跳旧商城，同样的链接配在首页导航跳转出错，配在公告详情里可以正常跳转

<a href="" target="_self" style="color: rgb(255, 0, 0); text-decoration: underline;"><span style="color: rgb(255, 0, 0);">旧版商城传送门》》</span></a>

https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8f5a07e8746f85e3&amp;redirect_uri=http%3a%2f%2fwww.avic-s.com%2fsuper_reception%2fwechat%2ffrontSecurity%2fauth%3fredirectUrl%3d%2fwechat%2fsikpMall%2fsikpMallManager%26urlKey%3dshopindex&amp;response_type=code&amp;scope=snsapi_base&amp;state=1#wechat_redirect




ALTER TABLE `ims_superdesk_shop_nav` CHANGE `url` `url` VARCHAR(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;