

# 为陈文礼加的需要的补刀

> #task_tb_usr_数据_2_shop_member_条件_1_企业信息完整_Y_导入_N_丢弃_条件_2_认证状态_已认证_条件_3_可用状态_可用

https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=pc.api.order.pay.complete

// TODO BUG_20180716


TEST_POINT_core_model_order: {"id":"12447","ordersn":"ME20180716121845628820","price":"342.30","openid":"oX8KYwkxwNW6qzHF4cF-tGxYTcPg","dispatchtype":"0","addressid":"931","carrier":"a:0:{}","status":"0","isverify":"0","deductcredit2":"0.00","virtual":"0","isvirtual":"0","couponid":"0","isvirtualsend":"0","isparent":"0","paytype":"3","ismerch":"1","merchid":"8","agentid":"0","createtime":"1531714725","buyagainprice":"0.00"}


[
{"openid":"oX8KYwkxwNW6qzHF4cF-tGxYTcPg","mobile":"13422832499"},
{"openid":"oX8KYwpvX29K9w1E89SxAd6_CO3Q","mobile":"13699856059"},
{"openid":"wap_user_16_13800138001","mobile":"13800138001"}
]


> /data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/model/examine.php
```
public function addExamine($order){
    ... 
    // 推送
    foreach ($manager_arr as $auditor) {

//            openid,core_user,core_enterprise,core_organization,mobile

        m('notice')->sendExamineCreateNotice(
            $auditor['openid'],
            $auditor['core_user'],
            $auditor['core_enterprise'],
            $auditor['core_organization'],
            $auditor['mobile'],
            $member['realname'], $order['ordersn'], $order['price'], $order['id']);
    }
}
```

> /data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/model/notice.php

```
public function superdeskCoreSendTplNotice(
   $toUserMobile /* 这里传个手机号 */,
   $template_id,
   $post_data,
   $url = '',
   $topcolor = '#FF683F'
)
{

   $socket_log_data = array(
       'toUserMobile' => $toUserMobile,
       'template_id'  => $template_id,
       'post_data'    => $post_data,
       'url'          => $url,
       'topcolor'     => $topcolor
   );

   socket_log("TEST_POINT_superdeskCoreSendTplNotice: " . json_encode($socket_log_data, JSON_UNESCAPED_UNICODE));

   if (empty($toUserMobile)) {
       return error(-1, '参数错误,粉丝手机号不能为空');
   }

   if (empty($template_id)) {
       return error(-1, '参数错误,模板标示不能为空');
   }

   if (empty($post_data) || !is_array($post_data)) {
       return error(-1, '参数错误,请根据模板规则完善消息内容');
   }


   $_userInfoService = new UserInfoService();

   // Mark FIX 
   $superdesk_core_data = $_userInfoService->getOneByUserMobile($toUserMobile);


   $superdesk_openid = $superdesk_core_data['open_id'];
   $access_token     = $superdesk_core_data['access_token'];


   // Mark ADD 
   if (empty($superdesk_openid)) {
       return true;
   }

   $data                = array();
   $data['touser']      = $superdesk_openid;
   $data['template_id'] = trim($template_id);
   $data['url']         = trim($url);
   $data['topcolor']    = trim($topcolor);
   $data['data']        = $post_data;
   $data                = json_encode($data);
   $post_url            = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
   $response            = ihttp_request($post_url, $data);

   socket_log("超级前台:" . json_encode($response, JSON_UNESCAPED_UNICODE));


   if (is_error($response)) {
       return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
   }

   $result = @json_decode($response['content'], true);

   if (empty($result)) {

       return error(-1, "接口调用失败, 元数据: {$response['meta']}");

   } elseif (!empty($result['errcode'])) {

       return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");

   }
   return true;
}

```