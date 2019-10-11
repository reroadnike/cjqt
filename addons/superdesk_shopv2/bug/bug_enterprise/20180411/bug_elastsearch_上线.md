
<br />
<b>Warning</b>:  Use of undefined constant HTTP_HOST - assumed 'HTTP_HOST' (this will throw an Error in a future version of PHP) in <b>/data/wwwroot/default/plugins/app/common/bootstrap.app.inc.php</b> on line <b>118</b><br />
<br />
<b>Warning</b>:  Use of undefined constant REQUEST_URI - assumed 'REQUEST_URI' (this will throw an Error in a future version of PHP) in <b>/data/wwwroot/default/plugins/app/common/bootstrap.app.inc.php</b> on line <b>118</b><br />
<br />
<b>Deprecated</b>:  Function create_function() is deprecated in <b>/data/wwwroot/default/plugins/framework/library/socket_log/slog.function.php</b> on line <b>9</b><br />
<br />
<b>Deprecated</b>:  Function create_function() is deprecated in <b>/data/wwwroot/default/plugins/framework/library/socket_log/slog.function.php</b> on line <b>9</b><br />
<br />
<b>Deprecated</b>:  Function create_function() is deprecated in <b>/data/wwwroot/default/plugins/framework/library/socket_log/slog.function.php</b> on line <b>9</b><br />
<br />
<b>Deprecated</b>:  Function create_function() is deprecated in <b>/data/wwwroot/default/plugins/framework/library/socket_log/slog.function.php</b> on line <b>9</b><br />
<br />







<b>Fatal error</b>:  Uncaught Error: Call to undefined function mysql_escape_string() in /data/wwwroot/default/plugins/addons/superdesk_shopv2_elasticsearch/third_party/library/PDO_elasticsearch.class.php:43
Stack trace:
#0 /data/wwwroot/default/plugins/addons/superdesk_shopv2_elasticsearch/model/goods/ES_shop_goodsModel.class.php(58): PDO_elasticsearch-&gt;execute(' SELECT  id,tit...', Array)
#1 /data/wwwroot/default/plugins/addons/superdesk_shopv2/core/model/goods.php(348): ES_shop_goodsModel-&gt;fetchAll(' SELECT  id,tit...', Array)
#2 /data/wwwroot/default/plugins/addons/superdesk_shopv2/core/mobile/goods/index.php(138): Goods_SuperdeskShopV2Model-&gt;getList(Array)
#3 /data/wwwroot/default/plugins/addons/superdesk_shopv2/core/mobile/goods/index.php(100): Index_SuperdeskShopV2Page-&gt;_condition(Array)
#4 /data/wwwroot/default/plugins/addons/superdesk_shopv2/core/model/route.php(158): Index_SuperdeskShopV2Page-&gt;get_list()
#5 /data/wwwroot/default/plugins/addons/superdesk_shopv2/site.php(26): Route_SuperdeskShopV2Model-&gt;run(false)
#6 /dat in <b>/data/wwwroot/default/plugins/addons/superdesk_shopv2_elasticsearch/third_party/library/PDO_elasticsearch.class.php</b> on line <b>43</b><br />
