


SELECT * FROM ims_superdesk_shop_goods WHERE tcate = 1671 and status = 1


UPDATE ims_superdesk_shop_goods set updatetime = UNIX_TIMESTAMP() WHERE deleted = 0 AND status = 1 AND tcate = 1671