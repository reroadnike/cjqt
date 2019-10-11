# 已记录PM


20180828_京东VOP优化_

20180828_京东VOP优化_1_商品分类

> 加字段 isJd 1 为京东 0 为商城 默认 0 

> 修正 level 目前 ims_superdesk_shop_category.level与京东的level差1


20180828_京东VOP优化_2_京东地址较验 


// 弃
> 先update ims_superdesk_jd_vop_area state = 0

> 接口较验后 update ims_superdesk_jd_vop_area state = 1 

> 检查地址接口 加入条件 state = 1 才输出

// 新
// TODO 

20180828_京东VOP优化_3_商品分类打包

> select id name thumb 

> SELECT * FROM `ims_core_attachment` WHERE attachment = 'images/16/2017/11/ELeYGOLgJYSYmEZhlyZqqjYPygeq7g.jpg'

> delete ims_core_attachment where attachment where uniacid = :uniacid and type = 1 and attachment = :thumb

> thumb > mv > images > shop_category > 0 > id.jpg|png > update table ims_superdesk_shop_category set thumb = : mv->thumb.path


