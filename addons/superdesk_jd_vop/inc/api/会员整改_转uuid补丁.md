step.1 添加项目id
-> update_20181221_用户表添加项目id.sql

step.2 填充用户表中的项目id
-> patch_superdesk_shop_member_insert_organization.inc.php
-> 用户表连接企业表,将企业表中的项目id更新到用户表中..
-> 其中where m.core_organization = 0 即已更新了项目id
-> 使用了join 即没有找到企业.则不更新此人
-> return 本次更新的所有用户

step.3 找出所有表中有openid字段的表.加入一个uuid
-> patch_all_table_alter_shop_member_uuid.inc.php
-> 遍历查找..插入..假如已经加入了uuid就不再插入
-> return 本次更新的所有表

step.4 用户表插入uuid
-> 遍历所有用户
-> 生成uuid.查询uuid是否存在
-> 不存在插入用户表中.
-> 存在重新生成.
-> return 本次更新的所有用户

step.5 找出所有表中有openid字段的表.将用户表中的uuid插入到其他有openid字段的表
-> 找出有openid字段的表
-> 遍历所有uuid不为空的用户
-> 遍历更新所有表的uuid
-> return 本次更新的所有用户,本次更新的所有表