patch_superdesk_shop_all_tables_change_core_user_by_one_member

线上
view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_all_tables_change_core_user_by_one_member&new_core_user=10042&old_core_user=4896

接受指定的两个参数
old_core_user 旧的
new_core_user 新的
其中一个为空跳出

两个查表
其中一个查不到跳出

循环所有有core_user的表.更新旧的core_user为新的