

bug_微信端商品以价格排序有问题

这个问题主要是计划任务在更新价格时,resultMessage 中提到的文本没处理,没有接口,
且商品价格排序不是以markprice(显示的价格)排序,是以minprice(用于商品规格)排序
所以会出现排序错乱


2676400不在您的商品池中
2676124不在您的商品池中
价格为null或者小于0时
为暂无报价

处理方式

批量价格更新时返回的resultMessage 

xxxxxx不在您的商品池中 , xxxxxx不在您的商品池中 , xxxxxx不在您的商品池中  , xxxxxx不在您的商品池中 

以中文,分隔,提取skuid ,为此sku商品在本商城 状态:下架 and 删除:true

记录日志

plog('goods.edit', '京东任务:不在您的商品池中 ID: ' . $sku['id'] . ' 商品名称: ' . $sku['title'] . ' 状态: ' . '下架');

结果验证

过24小时会,等计划任务执行完,再去看微信端商品以价格排序
程序修正已上线

查看状态地址
view-source:http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_04_sku_price_update_100

对于价格还存在的问题就是

2676400不在您的商品池中
2676124不在您的商品池中
价格为null或者小于0时
为暂无报价

对于京东返回resultMessage 中的
价格为null或者小于0时
为暂无报价
不能提取skuid 
此情况处理不了
会导致有的商品价格为0.00

现在排序变正常多了.但如果发现后边还有价格更低的就是有可能计划任务更新价格后,更新到比当前看的价格低的,当异步上拉时,就有可能加载到比当前看的价格低的商品