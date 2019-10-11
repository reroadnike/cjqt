<?php

/**
 * 取件信息实体，即原商品如何返回京东或者卖家，如果不为取件方式，默认设置订单中省市县镇信息
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 10/31/17
 * Time: 9:02 PM
 */
class AfterSalePickwareDto
{
参数名 | 含义 | 参数类型 | 其他
pickwareType | 取件方式 | Integer | 必填 4 上门取件 7 客户送货 40客户发货
pickwareProvince | 取件省 | Integer | 必填
pickwareCity | 取件市 | Integer | 必填
pickwareCounty | 取件县 | Integer | 必填
pickwareVillage | 取件乡镇 | Integer | 必填
pickwareAddress | 取件街道地址 | String | 最多500字符, 必填

}