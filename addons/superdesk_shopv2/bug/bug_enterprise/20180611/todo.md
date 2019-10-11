


吴仕雄
15070961064	普通会员 
无分组	2018-06-11
16:35:17	2018-01-16
14:16:45


bug_数据同步有问题_客户已经是转企业了_吴仕雄_15070961064_111_513_九江经开区综合服务中心项目部

日志

```
同步 ModifyTime > 2018-06-11 18:11:52
同步 total > 1
同步 page > 1
同步 pageSize > 1000
{"ID":"4173","userName":"青章银","nickName":"青章银","userMobile":"17761066165","userType":null,"userSex":null,"userCardNo":null,"birthday":null,"userPhotoUrl":"http:\/\/wx.qlogo.cn\/mmopen\/ULeXT2mr66QSUvJdQqOyRUvNp0J997ibSCoMsc4CspZ9jCxjlIibZ4rib3FMTQUFtQ57OElLYjDf6W6h82b5rlVSJ289fWgdo0C\/0","password":null,"status":"1","suggestion":"","address":null,"imageUrl01":null,"imageUrl02":null,"imageUrl03":null,"organizationId":"10","virtualArchId":"56","userNumber":null,"enteringTime":null,"positionName":null,"departmentId":null,"facePlusUserId":"817894","roleType":"2","noticePower":"0","creator":null,"createTime":"2017-11-23 12:23:53","modifier":"","modifyTime":"2018-06-11 18:13:28","isEnabled":"1","isSyncNeigou":"1","isSyncSpaceHome":null}

同步 ModifyTime > 2018-06-11 18:13:28
同步 total > 1
同步 page > 1
同步 pageSize > 1000
{"ID":"3208","userName":"吴仕雄","nickName":null,"userMobile":"15070961064","userType":null,"userSex":null,"userCardNo":null,"birthday":null,"userPhotoUrl":null,"password":null,"status":"1","suggestion":null,"address":null,"imageUrl01":"","imageUrl02":"","imageUrl03":"","organizationId":"474","virtualArchId":"1619","userNumber":"","enteringTime":null,"positionName":"南昌公司>>九江经开区综合服务中心","departmentId":null,"facePlusUserId":null,"roleType":"2","noticePower":"0","creator":null,"createTime":"2017-08-15 00:00:00","modifier":null,"modifyTime":"2018-06-11 18:28:25","isEnabled":"1","isSyncNeigou":"0","isSyncSpaceHome":null}
耗时0.2359秒
```

问题

```
/data/wwwroot/default/superdesk/addons/superdesk_core/service/TbuserService.class.php
```

``` sql
UPDATE `super_reception_real`.`tb_user` SET `modifyTime`='2018-06-12 19:05:25' WHERE `ID`='3208';
```



bug_订单_ME20180508143603288224_商户后台显示已完成_但是实际上这个订单是已取消订单_慧采核实了_是已经取消了
bug_转订后详情显示商品单价_对不上_问题



