

date "+%Y-%m-%d %H:%M:%S"


# 每五分钟执行
*/5 * * * *

# 每小时执行
0 * * * *
crontab_0_x_x_x_x

# 每天执行(每天0点)
0 0 * * *
crontab_0_0_x_x_x

# 每周执行
0 0 * * 0
crontab_0_0_x_x_0

# 每月执行
0 0 1 * *
crontab_0_0_1_x_x

# 每年执行
0 0 1 1 *
crontab_0_0_1_1_x