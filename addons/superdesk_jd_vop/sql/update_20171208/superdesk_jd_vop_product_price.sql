CREATE TABLE `ims_superdesk_jd_vop_product_price` (
`skuId` INT(11) NOT NULL COMMENT 'skuId' ,
`productprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '京东价格' ,
`marketprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '客户购买价格' ,
`costprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '协议价格' ,
`createtime` INT(11) NOT NULL COMMENT 'createtime' ,
`updatetime` INT(11) NOT NULL COMMENT 'updatetime'
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `ims_superdesk_jd_vop_product_price` ADD PRIMARY KEY(`skuId`);