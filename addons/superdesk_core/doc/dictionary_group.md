
**简要描述：**

- 接口调用

**请求域名:**

- http://grandway020.com

**请求URL：**

<span class="default post">POST</span>/bc/app/index.php?i=15&c=entry&do=api_push_dictionary_group&m=superdesk_core

**请求参数说明：**

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|groupcode |NO  |varchar(30) |   |
|groupname |NO  |varchar(30) |   |
|isenabled |YES  |char(1) |   |
|oprateversion |YES  |bigint(20) |   |
|opratetype |YES  |char(1) |   |
|createby |YES  |varchar(30) |   |
|createtime |YES  |datetime |   |
|lastupdateby |YES  |varchar(30) |   |
|lastupdatetime |YES  |datetime |   |
|createtime_ |NO  |int(11) |创建时间   |
|updatetime |NO  |int(11) |修改时间   |
|enabled |NO  |tinyint(4) |是否可用或删除   |
|uniacid |NO  |int(10) |uniacid   |


 **请求示例**

```
{
    "data": [
        {
            "groupcode": "",
            "groupname": "",
            "isenabled": "",
            "oprateversion": "",
            "opratetype": "",
            "createby": "",
            "createtime": "",
            "lastupdateby": "",
            "lastupdatetime": "",
            "createtime_": "创建时间",
            "updatetime": "修改时间",
            "enabled": "是否可用或删除",
            "uniacid": "uniacid"
        },
        {
            "groupcode": "",
            "groupname": "",
            "isenabled": "",
            "oprateversion": "",
            "opratetype": "",
            "createby": "",
            "createtime": "",
            "lastupdateby": "",
            "lastupdatetime": "",
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



