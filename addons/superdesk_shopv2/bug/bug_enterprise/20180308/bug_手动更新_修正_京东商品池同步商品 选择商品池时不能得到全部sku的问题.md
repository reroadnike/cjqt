

bug_手动更新_修正_京东商品池同步商品 选择商品池时不能得到全部sku的问题

通过JD技术支持赵二伟 , 得到最新 JD-API对接【实物】帮助文档 2018-01 

修正了 京东商品池同步商品 选择商品池时不能得到全部sku的问题

之前只能拿最多2000的sku,现在可以说是全部了

从而缓解商品数量不足的问题


不成功时要构制的数据结构

{
    "status": 1,
    "result": {
        "success": true,
        "resultMessage": "",
        "resultCode": "0000",
        "result": {
            "sku": 5375259,
            "name": "英特尔（Intel）NUC迷你电脑主机 内置赛扬J3455处理器 支持win10操作系统（NUC6CAYHL）",
            "page_num": "678"
        },
        "code": 200,
        "url": "http:\/\/www.avic-s.com\/plugins\/web\/index.php?c=site&a=entry&eid=1493"
    }
}