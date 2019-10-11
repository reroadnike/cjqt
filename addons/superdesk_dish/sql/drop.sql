DROP TABLE
`ims_weisrc_dish_address`,
`ims_weisrc_dish_area`,
`ims_weisrc_dish_blacklist`,
`ims_weisrc_dish_cart`,
`ims_weisrc_dish_category`,
`ims_weisrc_dish_collection`,
`ims_weisrc_dish_email_setting`,
`ims_weisrc_dish_goods`,
`ims_weisrc_dish_intelligent`,
`ims_weisrc_dish_mealtime`,
`ims_weisrc_dish_nave`,
`ims_weisrc_dish_order`,
`ims_weisrc_dish_order_goods`,
`ims_weisrc_dish_print_order`,
`ims_weisrc_dish_print_setting`,
`ims_weisrc_dish_reply`,
`ims_weisrc_dish_setting`,
`ims_weisrc_dish_sms_checkcode`,
`ims_weisrc_dish_sms_setting`,
`ims_weisrc_dish_stores`,
`ims_weisrc_dish_store_setting`,
`ims_weisrc_dish_type`;

RENAME TABLE current_db.tbl_name TO other_db.tbl_name;


RENAME TABLE `ims_weisrc_dish_address`		  TO	`ims_superdesk_dish_address`			;
RENAME TABLE `ims_weisrc_dish_area`			    TO	`ims_superdesk_dish_area`;
RENAME TABLE `ims_weisrc_dish_blacklist`	  TO	`ims_superdesk_dish_blacklist`;
RENAME TABLE `ims_weisrc_dish_cart`			    TO	`ims_superdesk_dish_cart`;
RENAME TABLE `ims_weisrc_dish_category`		  TO	`ims_superdesk_dish_category`;
RENAME TABLE `ims_weisrc_dish_collection`	  TO	`ims_superdesk_dish_collection`;
RENAME TABLE `ims_weisrc_dish_email_setting` TO	`ims_superdesk_dish_email_setting`;
RENAME TABLE `ims_weisrc_dish_goods`		 TO	`ims_superdesk_dish_goods`;
RENAME TABLE `ims_weisrc_dish_intelligent`	 TO	`ims_superdesk_dish_intelligent`;
RENAME TABLE `ims_weisrc_dish_mealtime`		 TO	`ims_superdesk_dish_mealtime`;
RENAME TABLE `ims_weisrc_dish_nave`			 TO	`ims_superdesk_dish_nave`;
RENAME TABLE `ims_weisrc_dish_order`		 TO	`ims_superdesk_dish_order`;
RENAME TABLE `ims_weisrc_dish_order_goods`	 TO	`ims_superdesk_dish_order_goods`;
RENAME TABLE `ims_weisrc_dish_print_order`	 TO	`ims_superdesk_dish_print_order`;
RENAME TABLE `ims_weisrc_dish_print_setting` TO	`ims_superdesk_dish_print_setting`;
RENAME TABLE `ims_weisrc_dish_reply`		 TO	`ims_superdesk_dish_reply`;
RENAME TABLE `ims_weisrc_dish_setting`		 TO	`ims_superdesk_dish_setting`;
RENAME TABLE `ims_weisrc_dish_sms_checkcode` TO	`ims_superdesk_dish_sms_checkcode`;
RENAME TABLE `ims_weisrc_dish_sms_setting`	 TO	`ims_superdesk_dish_sms_setting`;
RENAME TABLE `ims_weisrc_dish_stores`		 TO	`ims_superdesk_dish_stores` ;
RENAME TABLE `ims_weisrc_dish_store_setting` TO	`ims_superdesk_dish_store_setting`;
RENAME TABLE `ims_weisrc_dish_type`;		 TO	`ims_superdesk_dish_type`;RENAME TABLE ;