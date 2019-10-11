
**简要描述：**

- 接口调用

**请求域名:**

- http://grandway020.com

**请求URL：**

<span class="default post">POST</span>/bc/app/index.php?i=15&c=entry&do=api_push_provincecity&m=superdesk_core

**请求参数说明：**

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|ID |NO  |int(11) |ID   |
|type |YES  |varchar(2) |类型(1-代表省份，2-代表城市)   |
|name |YES  |varchar(200) |省份/城市名称   |
|provinceCode |YES  |varchar(200) |省份编码   |
|cityCode |YES  |varchar(200) |城市编码   |
|description |YES  |varchar(50) |名称首字母   |
|creator |YES  |varchar(40) |创建人   |
|createTime |YES  |datetime |创建时间   |
|modifier |YES  |varchar(40) |修改人   |
|modifyTime |YES  |datetime |修改时间   |
|isEnabled |YES  |varchar(2) |是否可用   |
|createtime_ |NO  |int(11) |创建时间   |
|updatetime |NO  |int(11) |修改时间   |
|enabled |NO  |tinyint(4) |是否可用或删除   |
|uniacid |NO  |int(10) |uniacid   |


 **请求示例**

```
{
    "data": [
        {
            "ID": "ID",
            "type": "类型(1-代表省份，2-代表城市)",
            "name": "省份\/城市名称",
            "provinceCode": "省份编码",
            "cityCode": "城市编码",
            "description": "名称首字母",
            "creator": "创建人",
            "createTime": "创建时间",
            "modifier": "修改人",
            "modifyTime": "修改时间",
            "isEnabled": "是否可用",
            "createtime_": "创建时间",
            "updatetime": "修改时间",
            "enabled": "是否可用或删除",
            "uniacid": "uniacid"
        },
        {
            "ID": "ID",
            "type": "类型(1-代表省份，2-代表城市)",
            "name": "省份\/城市名称",
            "provinceCode": "省份编码",
            "cityCode": "城市编码",
            "description": "名称首字母",
            "creator": "创建人",
            "createTime": "创建时间",
            "modifier": "修改人",
            "modifyTime": "修改时间",
            "isEnabled": "是否可用",
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



