


//        socket_log('mark :: framework/model/mc.mod.php');
//        socket_log(json_encode($userinfo,JSON_UNESCAPED_UNICODE));



// TODO 132132问题

SELECT avatar FROM `ims_superdesk_shop_member` WHERE `avatar` LIKE '%/132132'

UPDATE ims_superdesk_shop_member set avatar = REPLACE(avatar,'/132132','/132')








                
SELECT logintime,ims_superdesk_shop_member.avatar as ims_superdesk_shop_member_avatar ,ims_mc_members.avatar as ims_mc_members_avatar

FROM ims_superdesk_shop_member 

LEFT JOIN ims_mc_members on ims_superdesk_shop_member.uid = ims_mc_members.uid 

WHERE ims_superdesk_shop_member.avatar LIKE '%/132132' or ims_mc_members.avatar LIKE '%/132132'