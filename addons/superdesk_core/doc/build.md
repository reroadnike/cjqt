
**简要描述：**

- 接口调用

**请求域名:**

- http://grandway020.com

**请求URL：**

<span class="default post">POST</span>/bc/app/index.php?i=15&c=entry&do=api_push_build&m=superdesk_core

**请求参数说明：**

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|name |YES  |varchar(20) |名称   |
|organizationId |YES  |int(11) |学校id   |
|vip |YES  |varchar(10) |vip标识：0 否，1 是   |
|remark |YES  |varchar(200) |备注信息   |
|address |YES  |varchar(200) |详细地址   |
|lng |YES  |decimal(20,4) |地图x坐标   |
|lat |YES  |decimal(20,4) |地图Y坐标   |
|createTime |YES  |datetime |创建时间   |
|creator |YES  |varchar(20) |创建人   |
|modifier |YES  |varchar(20) |修改人   |
|modifyTime |YES  |datetime |修改时间   |
|isEnabled |YES  |varchar(10) |是否可用或删除：0 禁用，1 可用   |
|createtime_ |NO  |int(11) |创建时间   |
|updatetime |NO  |int(11) |修改时间   |
|enabled |NO  |tinyint(4) |是否可用或删除   |
|uniacid |NO  |int(10) |uniacid   |


 **请求示例**

```
{
    "data": [
        {
            "name": "名称",
            "organizationId": "学校id",
            "vip": "vip标识：0 否，1 是",
            "remark": "备注信息",
            "address": "详细地址",
            "lng": "地图x坐标",
            "lat": "地图Y坐标",
            "createTime": "创建时间",
            "creator": "创建人",
            "modifier": "修改人",
            "modifyTime": "修改时间",
            "isEnabled": "是否可用或删除：0 禁用，1 可用",
            "createtime_": "创建时间",
            "updatetime": "修改时间",
            "enabled": "是否可用或删除",
            "uniacid": "uniacid"
        },
        {
            "name": "名称",
            "organizationId": "学校id",
            "vip": "vip标识：0 否，1 是",
            "remark": "备注信息",
            "address": "详细地址",
            "lng": "地图x坐标",
            "lat": "地图Y坐标",
            "createTime": "创建时间",
            "creator": "创建人",
            "modifier": "修改人",
            "modifyTime": "修改时间",
            "isEnabled": "是否可用或删除：0 禁用，1 可用",
            "createtime_": "创建时间",
            "updatetime": "修改时间",
            "enabled": "是否可用或删除",
            "uniacid": "uniacid"
        }
    ]
}

```

 **返回参数说明**

|参数名|类型|说明|
|:-----  |:-----|-----                           |
|code |int   |200  |
|msg |string   |信息  |
|code |struct   |数据结构随具体接口变化  |


**返回示例**

```
{
    "code": 200,
    "msg": "信息",
    "data": "数据结构随具体接口变化"
}


```

 **备注**

- 更多返回错误代码请看首页的错误代码描述



