<?php

/**
 * 返件信息实体，即商品如何返回客户手中
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 10/31/17
 * Time: 9:02 PM
 */
class AfterSaleReturnwareDto
{
参数名 | 含义 | 参数类型 | 其他
returnwareType | 返件方式 | Integer | 自营配送(10),第三方配送(20);换、修这两种情况必填（默认值）
returnwareProvince | 返件省 | Integer | 换、修这两种情况必填
returnwareCity | 返件市 | Integer |
returnwareCounty | 返件县 | Integer |
returnwareVillage | 返件乡镇 | Integer |
returnwareAddress | 返件街道地址 | String | 最多500字符，换、修这两种情况必填

}