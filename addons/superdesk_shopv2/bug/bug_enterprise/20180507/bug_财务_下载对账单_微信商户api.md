bug_财务_下载对账单_微信商户api.md


每日9:00前完成数据更新，当前数据更新至 2018-05-07
微信在次日9点启动生成前一天的对账单，建议商户10点后再获取；
对账单中涉及金额的字段单位为“元”。
下载账单接口为单日期接口，请尽量保持账单时间段不要过长。


问题地址

```
https://wxm.avic-s.com/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=finance.downloadbill
```