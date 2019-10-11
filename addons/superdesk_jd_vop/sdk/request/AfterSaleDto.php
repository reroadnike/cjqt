<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 10/31/17
 * Time: 9:00 PM
 */


class AfterSaleDto
{


参数名 | 含义 | 参数类型 | 其他
jdOrderId | 订单号 | Long | 必填
customerExpect | 客户预期 | Integer | 必填，退货(10)、换货(20)、维修(30)
questionDesc | 产品问题描述 | String | 最多1000字符
isNeedDetectionReport | 是否需要检测报告 | Boolean |
questionPic | 问题描述图片 | String | 最多2000字符 支持多张图片，用逗号分隔（英文逗号）
isHasPackage | 是否有包装 | Boolean |
packageDesc | 包装描述 | Integer | 0 无包装 10 包装完整 20 包装破损
asCustomerDto | 客户信息实体 | AfterSaleCustomerDto | 必填
asPickwareDto | 取件信息实体 | AfterSalePickwareDto | 必填 |
asReturnwareDto | 返件信息实体 | AfterSaleReturnwareDto | 必填
asDetailDto | 申请单明细 | AfterSaleDetailDto | 必填

}