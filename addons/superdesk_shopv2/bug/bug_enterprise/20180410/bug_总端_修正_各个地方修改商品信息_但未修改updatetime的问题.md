


bug_总端_修正_各个地方修改商品信息_但未修改updatetime的问题

_cron_handle_task_1002_message_get_type_2
_cron_handle_task_1002_message_get_type_2.inc.php
商品价格变更{"id":推送id, "result":{"skuId" : 商品编号 }, "type": 2, "time":推送时间},


-- 已修正






_cron_handle_task_1004_message_get_type_4
_cron_handle_task_1004_message_get_type_4.inc.php
上下架变更消息{"id":推送id, "result":{"skuId" : 商品编号 }, "type": 4 "time":推送时间} 处理::本地库不存在且下架,消息删除,本地库不存在且上架,加入同步队列

-- 已修正



sql_last_value



_cron_handle_task_1016_message_get_type_16
_cron_handle_task_1016_message_get_type_16.inc.php
商品介绍及规格参数变更消息{"id":推送id, "result":{"skuId" : 商品编号 } "type" : 16, "time":推送时间}}


-- 转去强制更新队列



-- 强制更新队列
_cron_handle_task_03_sku_detail
_cron_handle_task_03_sku_detail.inc.php
从队列中取skuId，call京东VOP api，同步sku详细信息_图片_价格   


-- 已修正

待补充




SELECT count(*) AS `count` FROM (
    SELECT * FROM `ims_superdesk_shop_goods` where updatetime > unix_timestamp('2018-04-10 08:17:00')
) AS `t1` LIMIT 1

SELECT * FROM (
    SELECT * FROM `ims_superdesk_shop_goods` where updatetime > unix_timestamp('2018-04-10 08:17:00')
) AS `t1` LIMIT 50000 OFFSET 0
