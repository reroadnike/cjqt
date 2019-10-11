
**简要描述：**

- 接口调用

**请求域名:**

- http://grandway020.com

**请求URL：**

<span class="default post">POST</span>/bc/app/index.php?i=15&c=entry&do=api_push_virtualarchitecture&m=superdesk_core

**请求参数说明：**

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|name |YES  |varchar(50) |学院名称   |
|organizationId |YES  |int(11) |项目ID   |
|type |YES  |varchar(2) |类型(1-学院、2-专业)   |
|code |YES  |varchar(50) |当前节点Code   |
|parentCode |YES  |varchar(50) |父节点Code   |
|remark |YES  |varchar(200) |备注   |
|codeNumber |YES  |varchar(40) |唯一编码   |
|customerNumber |YES  |varchar(40) |客户签约编号   |
|phone |YES  |varchar(20) |联系电话   |
|address |YES  |varchar(400) |企业详细地址   |
|contacts |YES  |varchar(40) |联系人   |
|employees |YES  |int(11) |企业人数   |
|reserveBalance |YES  |decimal(10,2) |预存款余额   |
|customerType |YES  |varchar(2) |客户类型（1-VIP客户，0-普通客户）   |
|contractStatus |YES  |varchar(2) |签约状态（1-已签约，0-未签约）   |
|status |YES  |varchar(2) |企业状态（0-待审核，1-通过，2-不通过）   |
|reviewRemark |YES  |varchar(200) |审核信息说明   |
|wxUserId |YES  |int(11) |申请人的微信信息ID   |
|creator |YES  |varchar(20) |创建者   |
|createTime |YES  |datetime |创建时间   |
|modifier |YES  |varchar(20) |修改人   |
|modifyTime |YES  |datetime |修改时间   |
|isEnabled |YES  |varchar(2) |是否可用或者删除1可用，0不可用   |
|createtime_ |NO  |int(11) |创建时间   |
|updatetime |NO  |int(11) |修改时间   |
|enabled |NO  |tinyint(4) |是否可用或删除   |
|uniacid |NO  |int(10) |uniacid   |


 **请求示例**

```
{
    "data": [
        {
            "name": "学院名称",
            "organizationId": "项目ID",
            "type": "类型(1-学院、2-专业)",
            "code": "当前节点Code",
            "parentCode": "父节点Code",
            "remark": "备注",
            "codeNumber": "唯一编码",
            "customerNumber": "客户签约编号",
            "phone": "联系电话",
            "address": "企业详细地址",
            "contacts": "联系人",
            "employees": "企业人数",
            "reserveBalance": "预存款余额",
            "customerType": "客户类型（1-VIP客户，0-普通客户）",
            "contractStatus": "签约状态（1-已签约，0-未签约）",
            "status": "企业状态（0-待审核，1-通过，2-不通过）",
            "reviewRemark": "审核信息说明",
            "wxUserId": "申请人的微信信息ID",
            "creator": "创建者",
            "createTime": "创建时间",
            "modifier": "修改人",
            "modifyTime": "修改时间",
            "isEnabled": "是否可用或者删除1可用，0不可用",
            "createtime_": "创建时间",
            "updatetime": "修改时间",
            "enabled": "是否可用或删除",
            "uniacid": "uniacid"
        },
        {
            "name": "学院名称",
            "organizationId": "项目ID",
            "type": "类型(1-学院、2-专业)",
            "code": "当前节点Code",
            "parentCode": "父节点Code",
            "remark": "备注",
            "codeNumber": "唯一编码",
            "customerNumber": "客户签约编号",
            "phone": "联系电话",
            "address": "企业详细地址",
            "contacts": "联系人",
            "employees": "企业人数",
            "reserveBalance": "预存款余额",
            "customerType": "客户类型（1-VIP客户，0-普通客户）",
            "contractStatus": "签约状态（1-已签约，0-未签约）",
            "status": "企业状态（0-待审核，1-通过，2-不通过）",
            "reviewRemark": "审核信息说明",
            "wxUserId": "申请人的微信信息ID",
            "creator": "创建者",
            "createTime": "创建时间",
            "modifier": "修改人",
            "modifyTime": "修改时间",
            "isEnabled": "是否可用或者删除1可用，0不可用",
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



