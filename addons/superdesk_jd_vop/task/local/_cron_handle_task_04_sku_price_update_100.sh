#!/bin/bash
curl --request GET \
  --url 'http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_04_sku_price_update_100' \
  --header 'cache-control: no-cache' \
  --header 'postman-token: b30eb453-e9ed-13d4-3246-0c8e72f5ae19' >> /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_04_sku_price_update_100.log
