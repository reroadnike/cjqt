TEST_POINT_core_model_order: {"id":"10791","ordersn":"ME20180530164343094303","price":"544.95","openid":"oX8KYwiNR5JrUFaIs5TZPDL9yrXI","dispatchtype":"0","addressid":"622","carrier":"a:0:{}","status":"0","isverify":"0","deductcredit2":"0.00","virtual":"0","isvirtual":"0","couponid":"0","isvirtualsend":"0","isparent":"0","paytype":"3","ismerch":"1","merchid":"8","agentid":"0","createtime":"1527669823","buyagainprice":"0.00"}
TEST POINT: 采购经理信息列表: [{"openid":"oX8KYwkxwNW6qzHF4cF-tGxYTcPg","mobile":"13422832499"},{"openid":"oX8KYwo9sdtTHoOiXLIvVy43rmGA","mobile":"13922202307"},{"openid":"oX8KYwpvX29K9w1E89SxAd6_CO3Q","mobile":"13699856059"}]



test_superdesk_shopv2_core_model_notice_superdeskcoresendnotice.inc

TEST POINT: notice->superdeskCoreSendNotice(params): {"mobile":"13422832499","tag":"examine_new","templateid":"pFFVbqq87XDv06SESF1iMsUwAAMaIDDOM_lXsHme9R0","default":{"first":{"value":"采购专员 安先生 提交了订单！","color":"#4a5077"},"keyword1":{"title":"订单编号","value":"ME20180530164343094303","color":"#4a5077"},"keyword2":{"title":"订单金额","value":"544.95","color":"#4a5077"},"keyword3":{"title":"客户名称","value":"安先生","color":"#4a5077"},"keyword4":{"title":"申请备注","value":"超级前台","color":"#4a5077"},"keyword5":{"title":"申请时间","value":"2018-05-30 16:43:49","color":"#4a5077"},"remark":{"value":"\r\n请及时审核!","color":"#4a5077"}},"url":"https:\/\/wxm.avic-s.com\/app\/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=examine.detail&userMobile=13422832499&orderid=10791","datas":[{"name":"头部标题","value":"采购专员 安先生 提交了订单！"},{"name":"单号","value":"ME20180530164343094303"},{"name":"金额","value":"544.95"},{"name":"采购人","value":"安先生"},{"name":"供应商","value":"超级前台"},{"name":"采购时间","value":"2018-05-30 16:43:49"},{"name":"尾部描述","value":"\r\n请及时审核!"}]}
TEST_POINT__notice__sendNotice__templateid: false
TEST POINT: notice->superdeskCoreSendNotice(params): 


{"mobile":"13922202307","tag":"examine_new","templateid":"pFFVbqq87XDv06SESF1iMsUwAAMaIDDOM_lXsHme9R0","default":{"first":{"value":"采购专员 安先生 提交了订单！","color":"#4a5077"},"keyword1":{"title":"订单编号","value":"ME20180530164343094303","color":"#4a5077"},"keyword2":{"title":"订单金额","value":"544.95","color":"#4a5077"},"keyword3":{"title":"客户名称","value":"安先生","color":"#4a5077"},"keyword4":{"title":"申请备注","value":"超级前台","color":"#4a5077"},"keyword5":{"title":"申请时间","value":"2018-05-30 16:43:49","color":"#4a5077"},"remark":{"value":"\r\n请及时审核!","color":"#4a5077"}},"url":"https:\/\/wxm.avic-s.com\/app\/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=examine.detail&userMobile=13922202307&orderid=10791","datas":[{"name":"头部标题","value":"采购专员 安先生 提交了订单！"},{"name":"单号","value":"ME20180530164343094303"},{"name":"金额","value":"544.95"},{"name":"采购人","value":"安先生"},{"name":"供应商","value":"超级前台"},{"name":"采购时间","value":"2018-05-30 16:43:49"},{"name":"尾部描述","value":"\r\n请及时审核!"}]}
TEST_POINT__notice__sendNotice__templateid: false
TEST POINT: notice->superdeskCoreSendNotice(params): {"mobile":"13699856059","tag":"examine_new","templateid":"pFFVbqq87XDv06SESF1iMsUwAAMaIDDOM_lXsHme9R0","default":{"first":{"value":"采购专员 安先生 提交了订单！","color":"#4a5077"},"keyword1":{"title":"订单编号","value":"ME20180530164343094303","color":"#4a5077"},"keyword2":{"title":"订单金额","value":"544.95","color":"#4a5077"},"keyword3":{"title":"客户名称","value":"安先生","color":"#4a5077"},"keyword4":{"title":"申请备注","value":"超级前台","color":"#4a5077"},"keyword5":{"title":"申请时间","value":"2018-05-30 16:43:49","color":"#4a5077"},"remark":{"value":"\r\n请及时审核!","color":"#4a5077"}},"url":"https:\/\/wxm.avic-s.com\/app\/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=examine.detail&userMobile=13699856059&orderid=10791","datas":[{"name":"头部标题","value":"采购专员 安先生 提交了订单！"},{"name":"单号","value":"ME20180530164343094303"},{"name":"金额","value":"544.95"},{"name":"采购人","value":"安先生"},{"name":"供应商","value":"超级前台"},{"name":"采购时间","value":"2018-05-30 16:43:49"},{"name":"尾部描述","value":"\r\n请及时审核!"}]}
TEST_POINT__notice__sendNotice__templateid: false




TEST_POINT__notice__superdeskCoreSendNotice__sql:  


select *  from `ims_superdesk_shop_member_message_template` where        template_id='pFFVbqq87XDv06SESF1iMsUwAAMaIDDOM_lXsHme9R0'        and uniacid=16  limit 1
