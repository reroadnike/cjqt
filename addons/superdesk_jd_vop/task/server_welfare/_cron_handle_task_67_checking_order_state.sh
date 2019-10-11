#!/bin/bash

log_date=`date +%Y%m%d`

curl --request GET \
  --url 'https://wxn.avic-s.com/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_67_checking_order_state' \
  --header 'cache-control: no-cache' \
  --header 'postman-token: b30eb453-e9ed-13d4-3246-0c8e72f5ae19' >> /data/task_logs/_cron_handle_task_67_checking_order_state.$log_date.log
