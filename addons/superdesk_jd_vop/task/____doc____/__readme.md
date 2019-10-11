su root
cd /var/spool/cron/
ll
[root@localhost cron]# ll
total 12
-rw-------  1 linjinyu linjinyu 6152 Dec 26 16:08 linjinyu
-rw-------. 1 root     root       57 Sep 19  2016 root
-rw-------  1 www      www         0 Dec 25 21:58 www

gedit linjinyu

* * * * * /home/linjinyu/cron/test_cron.sh
* * * * * sleep 5; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/test_cron.sh



* * * * * /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 5; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 10; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 15; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 20; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 25; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 30; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 35; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 40; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 45; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 50; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh
* * * * * sleep 55; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_00.sh



* * * * * /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 5; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 10; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 15; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 20; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 25; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 30; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 35; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 40; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 45; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 50; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh
* * * * * sleep 55; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_01_page_num.sh



* * * * * /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 5; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 10; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 15; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 20; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 25; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 30; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 35; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 40; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 45; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 50; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh
* * * * * sleep 55; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_02_sku_4_page_num.sh



* * * * * /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 5; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 10; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 15; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 20; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 25; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 30; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 35; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 40; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 45; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 50; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
* * * * * sleep 55; /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/task/_cron_handle_task_03_sku_detail.sh
