shop_category_image_move

一次性接口.. 用来打包所有分类图片...	查询条件为 1.图片非空. 2.图片不包含shop_category 3.uniacid

1. 获取所有带图片的分类.	select id,thumb from superdesk_shop_category where thumb != ""  and thumb not like "%shop_category%" and uniacid=:uniacid

2. 遍历数组..

3. old_file_path = 列表中的旧图片路径
   new_file_path = 新的保存路径

4. file_move 从旧路径到新路径
   判断是否移动成功,移动成功更新数据库.

5. 保存整个修改过图片的数组JSON到日志中


----------------------------------------------------------------------------------------------------------------------------------------------------------


/api/area/checkArea
/api/area/getCity
/api/area/getCounty
/api/area/getTown


/api/message/del
/api/message/get


/api/order/cancel
/api/order/confirmOrder
/api/order/getFreight
/api/order/orderTrack
/api/order/selectJdOrder
/api/order/submitOrder

/api/price/getBalance
/api/price/getBalanceDetail
/api/price/getPrice


/api/product/getCategory
/api/product/getDetail
/api/product/getSimilarSku
/api/product/getSkuByPage
/api/product/skuImage
/api/product/skuState


/api/search/search

/api/stock/getNewStockById
/api/stock/getStockById


/oauth2/accessToken
/oauth2/refreshToken