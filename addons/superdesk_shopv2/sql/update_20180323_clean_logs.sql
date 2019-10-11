## 已更新到服务器

UPDATE `ims_superdesk_shop_goods` SET displayorder = 0 WHERE displayorder > 0


UPDATE `ims_superdesk_shop_goods` SET displayorder = 0 WHERE displayorder > 0
-- 影响了 412040 行。 (查询花费 58.4341 秒。)


DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/area/getTown'


DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/price/getPrice'


DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/price/getPrice' AND success = 1

-- 参数不能为空
DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/price/getPrice' AND success = 0 AND resultCode = '1001'
-- 删除了 3341 行。 (查询花费 3.0506 秒。)

DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/price/getPrice' AND success = 0 AND resultCode = 'exit0'
-- 删除了 77 行。 (查询花费 0.0406 秒。)

DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/product/getDetail' AND success = 1


DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/product/skuImage' AND success = 1 AND resultCode = '0000'
-- 删除了 422178 行。 (查询花费 105.2486 秒。)



DELETE FROM `ims_superdesk_jd_vop_logs` WHERE resultCode = 'exit0'

-- 删除20180301 00:00:00 的成功记录
DELETE FROM `ims_superdesk_jd_vop_logs` WHERE success = 1 AND createtime < 1519833600


-- pageNum不存在
DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/product/getSku' AND success = 0 AND resultCode = '0010'
--  删除了 15 行。 (查询花费 0.0862 秒。)

-- pageNum不存在
DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/product/getSkuByPage' AND success = 0 AND resultCode = '0010'
-- 删除了 974 行。 (查询花费 0.2170 秒。)


-- 暂无新消息
DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/message/get' AND success = 0 AND resultCode = '0010'
-- 删除了 152812 行。 (查询花费 30.6278 秒。)

-- 删除成功删除消息
DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/message/del' AND success = 1 AND resultCode = '0000'

-- 订单号不能为空
DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/order/orderTrack' AND success = 0 AND resultCode = '1001'
-- 删除了 150 行。 (查询花费 0.1209 秒。)


DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/message/get' AND success = 1 AND resultCode = '0000'
-- 删除了 89548 行。 (查询花费 12.3935 秒。)




DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/message/get' AND success = 0 AND resultCode = '0010' AND resultMessage = '暂无新消息'
-- 删除了 89548 行。 (查询花费 12.3935 秒。)






2018/3/1 00:00:00
1519833600

2018/3/31 23:59:59
1522511999

DELETE
FROM `ims_superdesk_jd_vop_logs`
WHERE api = '/api/message/get'
	AND createtime < 1522511999
    AND createtime > 1519833600
	AND success = 0
    AND resultCode = '0010'
    AND resultMessage = '暂无新消息'
    ;




SELECT *
FROM `ims_superdesk_jd_vop_logs`
WHERE api = '/api/message/get'
	AND createtime < 1522511999
    AND createtime > 1519833600
	AND success = 0
    AND resultCode = '0010'
    AND resultMessage = '暂无新消息'
    ;