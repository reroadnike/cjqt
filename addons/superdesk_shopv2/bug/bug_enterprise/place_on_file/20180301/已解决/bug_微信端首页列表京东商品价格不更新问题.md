
bug_微信端首页列表京东商品价格不更新问题

这个问题两看

为您推荐

相当于缓存形式,不会去更新价格,这个也是为了首页打开快的原因,如果加上更新价格处理,会有速度上影响,每次大概会有300毫秒的差

处理
重构 addons/superdesk_jd_vop/service/PriceService.class.php

/**
 * @param array $list
 * 数据结构为 $select_fields =
 * " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku ";
 *
 * @return array
 */
public function businessProcessingUpdateJdVopPriceForShopList($list = array())


店铺推荐

走的是查询列表接口,会去更新价格



{
    "id": "59749", 
    "title": "老A(LAOA)激光测距仪 红外线测量仪70米LA514070", 
    "thumb": "http://img13.360buyimg.com/n0/jfs/t901/94/197627495/72387/4ec214a1/55091616N96b60e6a.jpg", 
    "marketprice": 404, 
    "productprice": 449, 
    "minprice": 404, 
    "maxprice": 404, 
    "isdiscount": "0", 
    "isdiscount_time": "0", 
    "isdiscount_discounts": "", 
    "sales": "1", 
    "total": "99", 
    "description": "", 
    "bargain": "0", 
    "jd_vop_sku": "1397793", 
    "costprice": 299
}
{
    "id": "168149", 
    "title": "乔安 JOOAN  全铜75-3视频线 SYV-75-3同轴64编视频线 监控摄像头配件摄像机专用 按米卖", 
    "thumb": "http://img13.360buyimg.com/n0/jfs/t17548/334/247346875/165567/65de489/5a668bf8N4622b7bb.jpg", 
    "marketprice": 1.58, 
    "productprice": 1.58, 
    "minprice": 1.58, 
    "maxprice": 1.58, 
    "isdiscount": "0", 
    "isdiscount_time": "0", 
    "isdiscount_discounts": "", 
    "sales": "0", 
    "total": "99", 
    "description": "", 
    "bargain": "0", 
    "jd_vop_sku": "6370561", 
    "costprice": 1.5
}

商城首页

request static/js/app/core.js:
url:./index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=get_recommand&mid=86&page=1&merchid=0
type:get
data:undefined
cache:false


response 
{
    "status": 1, 
    "result": {
        "list": [
            {
                "id": "210", 
                "title": "澳洲小黄鱼葡萄酒", 
                "thumb": "http://192.168.1.124/superdesk/attachment/images/16/merch/4/JwSj6ZPozj3HjLiILHl6oHHvLt31U1.png", 
                "marketprice": "168.00", 
                "productprice": "399.00", 
                "minprice": "168.00", 
                "maxprice": "168.00", 
                "isdiscount": "0", 
                "isdiscount_time": "1505721180", 
                "isdiscount_discounts": "{\"type\":0,\"default\":{\"option0\":\"\"},\"level1\":{\"option0\":\"\"},\"level2\":{\"option0\":\"\"},\"level3\":{\"option0\":\"\"},\"merch\":{\"option0\":\"\"}}", 
                "sales": "356", 
                "total": "100066", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "0"
            }, 
            {
                "id": "59749", 
                "title": "老A(LAOA)激光测距仪 红外线测量仪70米LA514070", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t901/94/197627495/72387/4ec214a1/55091616N96b60e6a.jpg", 
                "marketprice": 404, 
                "productprice": 449, 
                "minprice": 404, 
                "maxprice": 404, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "1", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "1397793", 
                "costprice": 299
            }, 
            {
                "id": "59745", 
                "title": "力易得(Endura) T8005 塑壳卷尺5mx190mm", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t2812/300/3677375437/313979/18a919da/5795a989Nbd7525a5.jpg", 
                "marketprice": 9.56, 
                "productprice": 9.8, 
                "minprice": 9.56, 
                "maxprice": 9.56, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "1", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "1236162", 
                "costprice": 9
            }, 
            {
                "id": "47505", 
                "title": "ESCASE Type-C转USB3.0转接头 安卓数据线U盘 手机OTG 适用华为P10Mate/荣耀V9/小米6/乐视等送挂绳魔力黑", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t13993/109/1303589399/278194/7c9db50f/5a444a33N2ff70691.jpg", 
                "marketprice": 20.9, 
                "productprice": 20.9, 
                "minprice": 20.9, 
                "maxprice": 20.9, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "3", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "5983094", 
                "costprice": 19.9
            }, 
            {
                "id": "47499", 
                "title": "ESCASE Type-C转USB3.0转接头安卓数据线U盘手机 OTG 适用华为MacBook/P10/荣耀V9/小米6等送挂绳星空灰", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t14266/64/982120420/229369/56ada348/5a40be0cN9445152b.jpg", 
                "marketprice": 20.9, 
                "productprice": 20.9, 
                "minprice": 20.9, 
                "maxprice": 20.9, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "5983092", 
                "costprice": 19.9
            }, 
            {
                "id": "47492", 
                "title": "Snowkids Type-C转HDMI转换器 苹果笔记本电脑新MacBook转HDMI投影仪 USB-C接口电视视频高清连接线 银色", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t14848/191/1404618899/320873/c03a265f/5a4dddceN1f5dcbc1.jpg", 
                "marketprice": 113.4, 
                "productprice": 113.4, 
                "minprice": 113.4, 
                "maxprice": 113.4, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "5975621", 
                "costprice": 108
            }
        ], 
        "pagesize": 6, 
        "total": "30", 
        "page": 1, 
        "url": "http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&mid=86"
    }
}



request static/js/app/core.js:
url:./index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=goods.get_list&mid=86&keywords=&isrecommand=&ishot=&isnew=&isdiscount=&issendfree=&istime=&cate=&order=&by=&merchid=&page=1&frommyshop=0
type:get
data:undefined
cache:false


response 
{
    "status": 1, 
    "result": {
        "list": [
            {
                "id": "210", 
                "title": "澳洲小黄鱼葡萄酒", 
                "thumb": "http://192.168.1.124/superdesk/attachment/images/16/merch/4/JwSj6ZPozj3HjLiILHl6oHHvLt31U1.png", 
                "marketprice": "168.00", 
                "productprice": "399.00", 
                "minprice": "168.00", 
                "maxprice": "168.00", 
                "isdiscount": "0", 
                "isdiscount_time": "1505721180", 
                "isdiscount_discounts": "{\"type\":0,\"default\":{\"option0\":\"\"},\"level1\":{\"option0\":\"\"},\"level2\":{\"option0\":\"\"},\"level3\":{\"option0\":\"\"},\"merch\":{\"option0\":\"\"}}", 
                "sales": "356", 
                "total": "100066", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "0"
            }, 
            {
                "id": "168156", 
                "title": "智如易 ROOME MINI+智能小夜灯 床头氛围灯 自动开关人体感应灯 宝宝喂奶灯起夜灯 新年礼盒版", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t16492/62/1896198959/387304/b570d304/5a6ab385N8e23f502.jpg", 
                "marketprice": 208.95, 
                "productprice": 208.95, 
                "minprice": 208.95, 
                "maxprice": 208.95, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "6372959", 
                "costprice": 199
            }, 
            {
                "id": "168157", 
                "title": "朗空FAIRAIR 朗空·熊猫Pro 朗空空气净化器KJFA500C增强版  纯物理复合净化 针对严重污染设计", 
                "thumb": "http://img13.360buyimg.com/n12/jfs/t14551/231/2096947512/115653/2072bf7d/5a7198abNd1dcb2ea.png", 
                "marketprice": 10287.9, 
                "productprice": 10287.9, 
                "minprice": 10287.9, 
                "maxprice": 10287.9, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "6454777", 
                "costprice": 9798
            }, 
            {
                "id": "168154", 
                "title": "乔安 JOOAN 半球网络摄像头 200万手机远程家用红外夜视百万高清1080p监控器737ERC-T-6MM", 
                "thumb": "http://img13.360buyimg.com/n12/jfs/t16402/105/1811017677/58723/7b06472c/5a6687dcN1968c461.jpg", 
                "marketprice": 174.3, 
                "productprice": 174.3, 
                "minprice": 174.3, 
                "maxprice": 174.3, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "6370599", 
                "costprice": 166
            }, 
            {
                "id": "168152", 
                "title": "乔安 JOOAN 200万AHD高清摄像头 1080p室内红外夜视防雾 同轴半球监控器 437DRB-T-6", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t19207/213/250754259/142971/26e96f05/5a668ab2Nee886fce.jpg", 
                "marketprice": 81.9, 
                "productprice": 81.9, 
                "minprice": 81.9, 
                "maxprice": 81.9, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "6370589", 
                "costprice": 78
            }, 
            {
                "id": "168153", 
                "title": "乔安 JOOAN 100万AHD高清摄像头 720p室内红外夜视防雾 同轴半球监控器437ARB-T-3.6", 
                "thumb": "http://img13.360buyimg.com/n12/jfs/t17308/134/241640915/142971/26e96f05/5a668a61N7c3670b3.jpg", 
                "marketprice": 71.4, 
                "productprice": 71.4, 
                "minprice": 71.4, 
                "maxprice": 71.4, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "6370591", 
                "costprice": 68
            }, 
            {
                "id": "168150", 
                "title": "乔安100万高清网络摄像头 家用夜视720p广角室内半球监控器 738KRB-T-3.6MM", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t18973/34/247424463/108075/a1f1c101/5a668811Nc891f82b.jpg", 
                "marketprice": 93.45, 
                "productprice": 93.45, 
                "minprice": 93.45, 
                "maxprice": 93.45, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "6370573", 
                "costprice": 89
            }, 
            {
                "id": "168151", 
                "title": "乔安 JOOAN 半球网络摄像头 200万手机远程家用红外夜视百万高清1080p监控器 737ERC-T-3.6MM", 
                "thumb": "http://img13.360buyimg.com/n12/jfs/t14332/347/2031220888/58723/7b06472c/5a6687c9N34b42f43.jpg", 
                "marketprice": 174.3, 
                "productprice": 174.3, 
                "minprice": 174.3, 
                "maxprice": 174.3, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "6370575", 
                "costprice": 166
            }, 
            {
                "id": "168148", 
                "title": "乔安 JOOAN 半球网络摄像头 130万手机远程家用红外夜视百万高清960p监控器737NRC-T-3.6MM", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t15922/204/1891928682/276030/e3c18f88/5a668777N6f5ae964.jpg", 
                "marketprice": 131.25, 
                "productprice": 131.25, 
                "minprice": 131.25, 
                "maxprice": 131.25, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "6370557", 
                "costprice": 125
            }, 
            {
                "id": "168149", 
                "title": "乔安 JOOAN  全铜75-3视频线 SYV-75-3同轴64编视频线 监控摄像头配件摄像机专用 按米卖", 
                "thumb": "http://img13.360buyimg.com/n0/jfs/t17548/334/247346875/165567/65de489/5a668bf8N4622b7bb.jpg", 
                "marketprice": 1.58, 
                "productprice": 1.58, 
                "minprice": 1.58, 
                "maxprice": 1.58, 
                "isdiscount": "0", 
                "isdiscount_time": "0", 
                "isdiscount_discounts": "", 
                "sales": "0", 
                "total": "99", 
                "description": "", 
                "bargain": "0", 
                "jd_vop_sku": "6370561", 
                "costprice": 1.5
            }
        ], 
        "total": "164675", 
        "pagesize": 10, 
        "url": "http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=goods&mid=86"
    }
}