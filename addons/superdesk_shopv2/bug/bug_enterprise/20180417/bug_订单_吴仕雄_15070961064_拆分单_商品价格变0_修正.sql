update `ims_superdesk_shop_order_goods` 
set createtime = 1523435948 
where orderid  = 1335
		or orderid  = 1378
		or orderid  = 1372
		or orderid  = 1377
		or orderid  = 1371 
    or orderid  = 1376
		or orderid  = 1373
		or orderid  = 1374
		or orderid  = 1375


SELECT *
FROM `ims_superdesk_shop_order_goods`
where orderid  = 1335
		or orderid  = 1378
		or orderid  = 1372
		or orderid  = 1377
		or orderid  = 1371
    or orderid  = 1376
		or orderid  = 1373
		or orderid  = 1374
		or orderid  = 1375
order by orderid asc ,id asc
;


delete FROM `ims_superdesk_shop_order_goods`
where price = 0.00 and (orderid  = 1335
		or orderid  = 1378
		or orderid  = 1372
		or orderid  = 1377
		or orderid  = 1371
    or orderid  = 1376
		or orderid  = 1373
		or orderid  = 1374
		or orderid  = 1375)



[
            {
                "pOrder": 72915601854,
                "orderState": 1,
                "jdOrderId": 72957466266,
                "state": 0,
                "freight": 0,
                "submitState": 1,
                "orderPrice": 36,
                "orderNakedPrice": 30.77,
                "type": 2,
                "sku": [
                    {
                        "category": 12079,
                        "num": 1,
                        "price": 36,
                        "tax": 17,
                        "oid": 0,
                        "name": "斯图sitoo 泡沫广告泡绵家用双面胶白色海绵双面胶带 三米长 3.6cm 8个装",
                        "taxPrice": 5.23,
                        "skuId": 6890028,
                        "nakedPrice": 30.77,
                        "type": 0
                    }
                ],
                "orderTaxPrice": 5.23
            },
            {
                "pOrder": 72915601854,
                "orderState": 1,
                "jdOrderId": 72952805073,
                "state": 1,
                "freight": 0,
                "submitState": 1,
                "orderPrice": 3758.59,
                "orderNakedPrice": 3212.48,
                "type": 2,
                "sku": [
                    {
                        "category": 4839,
                        "num": 1,
                        "price": 198.72,
                        "tax": 17,
                        "oid": 0,
                        "name": "得力(deli)3894 适用A3时尚专业静音型塑封机\/过塑机 银灰",
                        "taxPrice": 28.87,
                        "skuId": 569509,
                        "nakedPrice": 169.85,
                        "type": 0
                    },
                    {
                        "category": 724,
                        "num": 1,
                        "price": 260.87,
                        "tax": 17,
                        "oid": 0,
                        "name": "中控智慧 （ZKTeco） UF200指纹人脸混合识别考勤机 人脸签到打卡机",
                        "taxPrice": 37.9,
                        "skuId": 4247042,
                        "nakedPrice": 222.97,
                        "type": 0
                    },
                    {
                        "category": 673,
                        "num": 1,
                        "price": 3299,
                        "tax": 17,
                        "oid": 0,
                        "name": "戴尔(DELL)成就商用台式电脑整机(奔腾G4560 4G 1T 三年上门 硬盘保留 Win10 窄边框)23.6英寸",
                        "taxPrice": 479.34,
                        "skuId": 6451128,
                        "nakedPrice": 2819.66,
                        "type": 0
                    },
                    {
                        "category": 673,
                        "num": 1,
                        "price": 0,
                        "tax": 17,
                        "oid": 6451128,
                        "name": "戴尔(DELL)SE2417HG 23.6英寸液晶显示器",
                        "taxPrice": 0,
                        "skuId": 3137713,
                        "nakedPrice": 0,
                        "type": 1
                    }
                ],
                "orderTaxPrice": 546.11
            },
            {
                "pOrder": 72915601854,
                "orderState": 1,
                "jdOrderId": 72952552850,
                "state": 1,
                "freight": 0,
                "submitState": 1,
                "orderPrice": 3899,
                "orderNakedPrice": 3332.48,
                "type": 2,
                "sku": [
                    {
                        "category": 672,
                        "num": 1,
                        "price": 3899,
                        "tax": 17,
                        "oid": 0,
                        "name": "惠普（HP）小欧 HP15q-bu100TX 15.6英寸笔记本电脑（i5-8250U 4G 500G 2G独显 FHD Win10）灰色",
                        "taxPrice": 566.52,
                        "skuId": 4820563,
                        "nakedPrice": 3332.48,
                        "type": 0
                    }
                ],
                "orderTaxPrice": 566.52
            },
            {
                "pOrder": 72915601854,
                "orderState": 1,
                "jdOrderId": 72952611831,
                "state": 0,
                "freight": 0,
                "submitState": 1,
                "orderPrice": 160,
                "orderNakedPrice": 136.75,
                "type": 2,
                "sku": [
                    {
                        "category": 12079,
                        "num": 1,
                        "price": 160,
                        "tax": 17,
                        "oid": 0,
                        "name": "鸿泰 A4复印纸 70g  500张一包 8包一箱",
                        "taxPrice": 23.25,
                        "skuId": 7004287,
                        "nakedPrice": 136.75,
                        "type": 0
                    }
                ],
                "orderTaxPrice": 23.25
            },
            {
                "pOrder": 72915601854,
                "orderState": 1,
                "jdOrderId": 72952652319,
                "state": 0,
                "freight": 0,
                "submitState": 1,
                "orderPrice": 29,
                "orderNakedPrice": 24.79,
                "type": 2,
                "sku": [
                    {
                        "category": 12079,
                        "num": 1,
                        "price": 29,
                        "tax": 17,
                        "oid": 0,
                        "name": "斯图 sitoo黄油双面胶带 薄双面胶 黄油双面胶 0.9cm*9m 24个装\/筒",
                        "taxPrice": 4.21,
                        "skuId": 7099963,
                        "nakedPrice": 24.79,
                        "type": 0
                    }
                ],
                "orderTaxPrice": 4.21
            },
            {
                "pOrder": 72915601854,
                "orderState": 1,
                "jdOrderId": 72952549584,
                "state": 1,
                "freight": 0,
                "submitState": 1,
                "orderPrice": 106.8,
                "orderNakedPrice": 91.2,
                "type": 2,
                "sku": [
                    {
                        "category": 4840,
                        "num": 20,
                        "price": 5.34,
                        "tax": 17,
                        "oid": 0,
                        "name": "广博(GuangBo)平夹型木质A4书写板夹\/文件夹板\/办公用品A26116",
                        "taxPrice": 0.78,
                        "skuId": 1260929,
                        "nakedPrice": 4.56,
                        "type": 0
                    }
                ],
                "orderTaxPrice": 15.6
            },
            {
                "pOrder": 72915601854,
                "orderState": 1,
                "jdOrderId": 72952727574,
                "state": 1,
                "freight": 0,
                "submitState": 1,
                "orderPrice": 368.49,
                "orderNakedPrice": 314.96,
                "type": 2,
                "sku": [
                    {
                        "category": 4839,
                        "num": 1,
                        "price": 96.14,
                        "tax": 17,
                        "oid": 0,
                        "name": "得力(deli) 8004 木质切纸机\/切纸刀\/裁纸刀\/裁纸机 300mm*250mm",
                        "taxPrice": 13.97,
                        "skuId": 587287,
                        "nakedPrice": 82.17,
                        "type": 0
                    },
                    {
                        "category": 4837,
                        "num": 1,
                        "price": 89,
                        "tax": 17,
                        "oid": 0,
                        "name": "广博(GuangBo)100张省力重型厚层订书机\/订书器办公用品DSJ7544",
                        "taxPrice": 12.93,
                        "skuId": 5171304,
                        "nakedPrice": 76.07,
                        "type": 0
                    },
                    {
                        "category": 7371,
                        "num": 2,
                        "price": 10,
                        "tax": 17,
                        "oid": 0,
                        "name": "3M 报事贴 便条纸\/便利贴\/便签纸\/便签本 合宜系列656B-4P 彩色套装 4本装",
                        "taxPrice": 1.45,
                        "skuId": 5051913,
                        "nakedPrice": 8.55,
                        "type": 0
                    },
                    {
                        "category": 4840,
                        "num": 2,
                        "price": 75.71,
                        "tax": 17,
                        "oid": 0,
                        "name": "探戈(TANGO)A4\/55mmPP粘扣档案盒\/文件资料收纳盒\/文件盒\/资料盒 10只装",
                        "taxPrice": 11,
                        "skuId": 2992678,
                        "nakedPrice": 64.71,
                        "type": 0
                    },
                    {
                        "category": 4837,
                        "num": 1,
                        "price": 11.93,
                        "tax": 17,
                        "oid": 0,
                        "name": "齐心（COMIX）1000枚\/盒高强度钢厚层订书钉(23\/23)可订200页 办公文具B3056 ",
                        "taxPrice": 1.73,
                        "skuId": 1514069,
                        "nakedPrice": 10.2,
                        "type": 0
                    }
                ],
                "orderTaxPrice": 53.53
            },
            {
                "pOrder": 72915601854,
                "orderState": 1,
                "jdOrderId": 72958472952,
                "state": 1,
                "freight": 0,
                "submitState": 1,
                "orderPrice": 2258.01,
                "orderNakedPrice": 1929.98,
                "type": 2,
                "sku": [
                    {
                        "category": 4840,
                        "num": 2,
                        "price": 8.75,
                        "tax": 17,
                        "oid": 0,
                        "name": "齐心(COMIX)增值税发票盒\/票据夹\/文件盒 A1245 蓝",
                        "taxPrice": 1.27,
                        "skuId": 4551903,
                        "nakedPrice": 7.48,
                        "type": 0
                    },
                    {
                        "category": 720,
                        "num": 1,
                        "price": 1355.31,
                        "tax": 17,
                        "oid": 0,
                        "name": "爱普生（EPSON）L4158墨仓式经济款 彩色无线多功能一体机（打印 复印 扫描 wifi）",
                        "taxPrice": 196.92,
                        "skuId": 5806368,
                        "nakedPrice": 1158.39,
                        "type": 0
                    },
                    {
                        "category": 12094,
                        "num": 2,
                        "price": 35,
                        "tax": 17,
                        "oid": 0,
                        "name": "康巴丝（COMPAS）挂钟 创意时尚时钟 静音石英客厅卧室简约钟c2855 黑色",
                        "taxPrice": 5.08,
                        "skuId": 6974164,
                        "nakedPrice": 29.92,
                        "type": 0
                    },
                    {
                        "category": 4837,
                        "num": 1,
                        "price": 12,
                        "tax": 17,
                        "oid": 0,
                        "name": "得力（deli）33392 镀镍回形针套装 银色29mm回形针600枚+彩色29mm回形针160枚",
                        "taxPrice": 1.74,
                        "skuId": 5463695,
                        "nakedPrice": 10.26,
                        "type": 0
                    },
                    {
                        "category": 2603,
                        "num": 2,
                        "price": 9.8,
                        "tax": 17,
                        "oid": 0,
                        "name": "齐心(Comix) GP6600 12支0.5mm黑色装商务中性笔\/水笔\/签字笔 办公文具",
                        "taxPrice": 1.42,
                        "skuId": 5424262,
                        "nakedPrice": 8.38,
                        "type": 0
                    },
                    {
                        "category": 4839,
                        "num": 5,
                        "price": 20,
                        "tax": 17,
                        "oid": 0,
                        "name": "千帆（YIDU SAILS）A4 220x310MMx5.5C 透明高清加厚塑封膜 照片护卡膜 过塑膜 100张\/包",
                        "taxPrice": 2.91,
                        "skuId": 1576011,
                        "nakedPrice": 17.09,
                        "type": 0
                    },
                    {
                        "category": 700,
                        "num": 1,
                        "price": 139,
                        "tax": 17,
                        "oid": 0,
                        "name": "腾达AC7 1200M 无线路由器 穿墙增强 5G双频 家用智能路由（光纤宽带大户型 五天线新品）",
                        "taxPrice": 20.2,
                        "skuId": 6655821,
                        "nakedPrice": 118.8,
                        "type": 0
                    },
                    {
                        "category": 2603,
                        "num": 5,
                        "price": 8.74,
                        "tax": 17,
                        "oid": 0,
                        "name": "得力(deli)0.5mm半针管黑色中性笔笔芯 水笔签字笔替芯 20支\/盒6901",
                        "taxPrice": 1.27,
                        "skuId": 569186,
                        "nakedPrice": 7.47,
                        "type": 0
                    },
                    {
                        "category": 7371,
                        "num": 10,
                        "price": 18,
                        "tax": 17,
                        "oid": 0,
                        "name": "广博(GuangBo)3本装80张A5商务PU皮面记事本子\/文具笔记本\/日记本GBP25444",
                        "taxPrice": 2.61,
                        "skuId": 4779532,
                        "nakedPrice": 15.39,
                        "type": 0
                    },
                    {
                        "category": 728,
                        "num": 5,
                        "price": 28.42,
                        "tax": 17,
                        "oid": 0,
                        "name": "得力(deli)双电源宽屏办公桌面计算器 财务计算机 银灰色1654",
                        "taxPrice": 4.13,
                        "skuId": 569554,
                        "nakedPrice": 24.29,
                        "type": 0
                    },
                    {
                        "category": 1047,
                        "num": 2,
                        "price": 89.4,
                        "tax": 17,
                        "oid": 0,
                        "name": "公牛（BULL）GN-B208U 新国标带USB插座 插线板\/插排\/排插\/接线板\/拖线板 8位总控全长3米",
                        "taxPrice": 12.99,
                        "skuId": 4932556,
                        "nakedPrice": 76.41,
                        "type": 0
                    }
                ],
                "orderTaxPrice": 328.03
            }
        ]