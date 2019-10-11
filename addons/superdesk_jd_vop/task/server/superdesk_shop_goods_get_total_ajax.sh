#!/bin/bash

log_date=`date +%Y%m%d`

curl --request GET \
  --url 'https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=superdesk_shop_goods_get_total_ajax' \
  --header 'cache-control: no-cache' \
  --header 'postman-token: b30eb453-e9ed-13d4-3246-0c8e72f5ae19' >> /mnt/task_logs/superdesk_shop_goods_get_total_ajax.$log_date.log
