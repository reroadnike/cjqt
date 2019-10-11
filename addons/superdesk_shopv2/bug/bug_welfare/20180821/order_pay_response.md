
request static/js/app/core.js:
url:./index.php?i=17&c=entry&m=superdesk_shopv2&do=mobile&r=order.pay.complete
type:post
data:{"id":2,"type":"credit"}
cache:false



response 
{"message":"

SQL: <br/> 

select COUNT(1)   from `ims_superdesk_shop_order_goods` og        inner join `ims_superdesk_shop_goods` g on og.goodsid = g.id '.\n            ' 
where og.uniacid=:uniacid  '.\n            '       and og.orderid =:orderid '.\n            '       
and g.type=5<hr/>Params: <br/>array (\n  ':uniacid' => 17,\n  ':orderid' => '2',\n)<hr/>

SQL Error: <br/>You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''.\n            
' where og.uniacid='17'  '.\n            '       and og.orderid ='' at line 1<hr/>Traces: 
<br/>file: /framework/class/db.class.php; line: 116; 
<br />file: /framework/function/pdo.func.php; line: 36; 
<br />file: /addons/superdesk_shopv2/core/model/order.php; line: 1498; 
<br />file: /addons/superdesk_shopv2/core/model/notice.php; line: 999; 
<br />file: /addons/superdesk_shopv2/core/model/order.php; line: 176; 
<br />file: /addons/superdesk_shopv2/core/mobile/order/pay.php; line: 605; 
<br />file: /addons/superdesk_shopv2/core/model/route.php; line: 169; 
<br />file: /addons/superdesk_shopv2/site.php; line: 26; 
<br />file: /app/source/entry/__init.php; line: 35; 
<br />file: /app/index.php; line: 85; <br />","redirect":"","type":"info"}