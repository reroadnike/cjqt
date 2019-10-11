
**简要描述：**

- 接口调用

**请求域名:**

- http://grandway020.com

**请求URL：**

<span class="default post">POST</span>/bc/app/index.php?i=15&c=entry&do=api_push_organization&m=superdesk_core

**请求参数说明：**

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|ID |NO  |int(11) |   |
|name |YES  |varchar(64) |名称   |
|code |YES  |varchar(128) |编码   |
|type |YES  |varchar(20) |项目类型（高校校园、小区住宅、CBD写字楼）   |
|telephone |YES  |varchar(32) |服务热线   |
|provinceCode |YES  |varchar(32) |所在省份编码   |
|provinceName |YES  |varchar(40) |所在省份名称   |
|cityCode |YES  |varchar(32) |所在城市编码   |
|cityName |YES  |varchar(40) |所在城市名称   |
|address |YES  |varchar(256) |详细地址   |
|lng |YES  |decimal(20,4) |经度   |
|lat |YES  |decimal(20,4) |纬度   |
|status |YES  |varchar(2) |项目状态（0-待审核，1-通过，2-不通过）   |
|applicantName |YES  |varchar(40) |申请人姓名   |
|applicantPhone |YES  |varchar(12) |申请人电话   |
|reviewRemark |YES  |varchar(200) |审核信息说明   |
|applicantIdentity |YES  |varchar(40) |申请人身份   |
|wxUserId |YES  |int(11) |申请人的微信信息ID   |
|createTime |YES  |datetime |创建时间   |
|creator |YES  |varchar(20) |创建者   |
|modifyTime |YES  |datetime |修改时间   |
|modifier |YES  |varchar(20) |修改人   |
|isEnabled |YES  |varchar(1) |是否可用或删除   |
|createtime_ |NO  |int(11) |创建时间   |
|updatetime |NO  |int(11) |修改时间   |
|enabled |NO  |tinyint(4) |是否可用或删除   |
|uniacid |NO  |int(10) |uniacid   |


 **请求示例**

```
{
    "data": [
        {
            "ID": "",
            "name": "名称",
            "code": "编码",
            "type": "项目类型（高校校园、小区住宅、CBD写字楼）",
            "telephone": "服务热线",
            "provinceCode": "所在省份编码",
            "provinceName": "所在省份名称",
            "cityCode": "所在城市编码",
            "cityName": "所在城市名称",
            "address": "详细地址",
            "lng": "经度",
            "lat": "纬度",
            "status": "项目状态（0-待审核，1-通过，2-不通过）",
            "applicantName": "申请人姓名",
            "applicantPhone": "申请人电话",
            "reviewRemark": "审核信息说明",
            "applicantIdentity": "申请人身份",
            "wxUserId": "申请人的微信信息ID",
            "createTime": "创建时间",
            "creator": "创建者",
            "modifyTime": "修改时间",
            "modifier": "修改人",
            "isEnabled": "是否可用或删除",
            "createtime_": "创建时间",
            "updatetime": "修改时间",
            "enabled": "是否可用或删除",
            "uniacid": "uniacid"
        },
        {
            "ID": "",
            "name": "名称",
            "code": "编码",
            "type": "项目类型（高校校园、小区住宅、CBD写字楼）",
            "telephone": "服务热线",
            "provinceCode": "所在省份编码",
            "provinceName": "所在省份名称",
            "cityCode": "所在城市编码",
            "cityName": "所在城市名称",
            "address": "详细地址",
            "lng": "经度",
            "lat": "纬度",
            "status": "项目状态（0-待审核，1-通过，2-不通过）",
            "applicantName": "申请人姓名",
            "applicantPhone": "申请人电话",
            "reviewRemark": "审核信息说明",
            "applicantIdentity": "申请人身份",
            "wxUserId": "申请人的微信信息ID",
            "createTime": "创建时间",
            "creator": "创建者",
            "modifyTime": "修改时间",
            "modifier": "修改人",
            "isEnabled": "是否可用或删除",
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



