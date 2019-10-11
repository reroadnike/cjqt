#!/bin/bash

log_date=`date +%Y%m%d`

curl --request GET \
  --url 'https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_100_sync_jd_balance_detail' \
  --header 'cache-control: no-cache' \
  --header 'postman-token: b30eb453-e9ed-13d4-3246-0c8e72f5ae19' >> /mnt/task_logs/_cron_handle_task_100_sync_jd_balance_detail.$log_date.log
