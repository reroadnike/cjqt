#!/bin/bash



log_date=`date +%Y%m%d`


curl --request GET \
  --url 'http://baidu.com' \
  --header 'cache-control: no-cache' \
  --header 'postman-token: b30eb453-e9ed-13d4-3246-0c8e72f5ae19' >> /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/$log_date.log
