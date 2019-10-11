
<?php

/*

http://php.net/manual/en/function.strtotime.php

1、获取当前时间方法date()

很简单，这就是获取时间的方法，格式为：date($format, $timestamp)，format为格式、timestamp为时间戳–可填参数。

2、获取时间戳方法time()、strtotime()
这两个方法，都可以获取php中unix时间戳，time()为直接获取得到，strtotime($time, $now)为将时间格式转为时间戳，$time为必填。清楚了这个，想了解更多，请继续往下看。

3、 date($format)用法
比如：
echo date(‘Y-m-d’) ，输出结果：2012-03-22
echo  date(‘Y-m-d H:i:s’)，输出结果：2012-03-22 23:00:00
echo  date(‘Y-m-d’, time())，输出结果：2012-03-22 23:00:00（结果同上，只是多了一个时间戳参数）（时间戳转换为日期格式的方法）
echo  date(‘Y’).’年’.date(‘m’).’月’.date(‘d’).’日’，输出结果：2012年3月22日
举例就这几个，只是格式的变通而已，下面是格式中各个字母的含义：

***********格式中可使用字母的含义*********

a – "am" 或是 "pm"
A – "AM" 或是 "PM"
d – 几日，二位数字，若不足二位则前面补零; 如: "01" 至 "31"
D – 星期几，三个英文字母; 如: "Fri"
F – 月份，英文全名; 如: "January"
h – 12 小时制的小时; 如: "01" 至 "12"
H – 24 小时制的小时; 如: "00" 至 "23"
g – 12 小时制的小时，不足二位不补零; 如: "1" 至 12"
G – 24 小时制的小时，不足二位不补零; 如: "0" 至 "23"
i – 分钟; 如: "00" 至 "59"
j – 几日，二位数字，若不足二位不补零; 如: "1" 至 "31"
l – 星期几，英文全名; 如: "Friday"
m – 月份，二位数字，若不足二位则在前面补零; 如: "01" 至 "12"
n – 月份，二位数字，若不足二位则不补零; 如: "1" 至 "12"
M – 月份，三个英文字母; 如: "Jan"
s – 秒; 如: "00" 至 "59"
S – 字尾加英文序数，二个英文字母; 如: "th"，"nd"
t – 指定月份的天数; 如: "28" 至 "31"
U – 总秒数
w – 数字型的星期几，如: "0" (星期日) 至 "6" (星期六)
Y – 年，四位数字; 如: "1999"
y – 年，二位数字; 如: "99"
z – 一年中的第几天; 如: "0" 至 "365"


4、strtotime($time)用法
比如：
echo strtotime(’2012-03-22′)，输出结果：1332427715（此处结果为随便写的，仅作说明使用）
echo strtotime(date(‘Y-d-m’))，输出结果：（结合date()，结果同上）（时间日期转换为时间戳）
strtotime()还有个很强大的用法，参数可加入对于数字的操作、年月日周英文字符，示例如下：
echo date(‘Y-m-d H:i:s’,strtotime(‘+1 day’))，输出结果：2012-03-23 23:30:33（会发现输出明天此时的时间）
echo date(‘Y-m-d H:i:s’,strtotime(‘-1 day’))，输出结果：2012-03-21 23:30:33（昨天此时的时间）
echo date(‘Y-m-d H:i:s’,strtotime(‘+1 week’))，输出结果：2012-03-29 23:30:33（下个星期此时的时间）
echo date(‘Y-m-d H:i:s’,strtotime(‘next Thursday’))，输出结果：2012-03-29 00:00:00（下个星期四此时的时间）
echo date(‘Y-m-d H:i:s’,strtotime(‘last Thursday’))，输出结果：2012-03-15 00:00:00（上个星期四此时的时间）
等等，自己去变通研究吧，strtotime()方法可以通过英文文本的控制Unix时间戳的显示，而得到需要的时间日期格式。

5、php获取当前时间的毫秒数
php本身没有提供返回毫秒数的函数，但提供了microtime()方法，它会返回一个Array，包含两个元素：一个是秒数、一个是小数表示的毫秒数，我们可以通过此方法获取返回毫秒数，方法如下：
function getMillisecond() {
list($s1, $s2) = explode(' ', microtime());
return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

6、获取当前时间相差6小时解决方法
有些朋友，获取的时间与当前系统时间相差6个小时，这是因为时区设置问题，只要将之设为上海时间即可。方法如下：

1.在php.ini中找到date.timezone，将它的值改成 Asia/Shanghai，即 date.timezone = Asia/Shanghai
2.在程序开始时添加 date_default_timezone_set(‘Asia/Shanghai’)即可。
详细设置解读见：PHP通过date()函数取得时间错误
*/
?>

<?php
//echo strtotime("now"), "<br/>";
//echo strtotime("10 September 2000"), "<br/>";
//echo strtotime("+1 day"), "<br/>";
//echo strtotime("+1 week"), "<br/>";
//echo strtotime("+1 week 2 days 4 hours 1 minutes 2 seconds"), "<br/>";
//echo strtotime("next Thursday"), "<br/>";
//echo strtotime("last Monday"), "<br/>";

//http://192.168.1.124/superdesk/app/index.php?i=15&c=entry&m=superdesk_boardroom&do=____select_time
?>
<?php

global $_GPC, $_W;

$now = time();


//echo date('Y-m-d H:i:s', $now);

//'timestamp'
//'enable'
//'lable'

//'Y-m-d H:i:s'
$Ymd = date('Y-m-d', $now);
//$Ymd            = $_GPC['day'];

//$automated_date   = $_GPC['automated_date'];
//$booking_num_wechat = intval($_GPC['booking_num_wechat']);
//$automated_id = $_GPC['automated_id'];
//$doctor_sn = $_GPC['doctor_sn'];


//echo $Ymd;
//echo "<br/>";
//echo $automated_date;
//echo "<br/>";
//echo $booking_num_wechat;
//echo "<br/>";

//$automated_array = explode("-",$automated_date);




//$automated_start = strtotime($Ymd . " ".$automated_array[0]);//.":00"
//$automated_end = strtotime($Ymd . " ".$automated_array[1]);//.":00"
//echo date('Y-m-d H:i:s', $automated_start);
//echo "<br/>";
//echo date('Y-m-d H:i:s', $automated_end);
//echo "<br/>";


//echo $Ymd;
$_init_am_start = strtotime($Ymd . " 00:00:00");
$_init_am_end = strtotime($Ymd . " 11:59:59");

$_init_pm_start = strtotime($Ymd . " 12:00:00");
$_init_pm_end = strtotime($Ymd . " 23:59:59");
//$_init_pm_end = strtotime("+1 seconds" ,strtotime($Ymd . " 23:59:59"));

$time_am = array();
$time_pm = array();

$time_am[] = $_init_am_start;
$time_pm[] = $_init_pm_start;

$result_all = array();

$i_am = 0;

while ($time_am[$i_am] < $_init_am_end) {


    $time_am[]    = strtotime("+30 minutes", $time_am[$i_am]);
    $result_am = array();


    $result_am['timestamp'] = strtotime("+30 minutes", $time_am[$i_am]);


    // 如果显示时间 比当前时间大 则为可约
    $result_am['enable'] = $result_am['timestamp'] > $now ? 1 : 0; // 1为可约，0为过期


    // 如果显示时间 比排班时间大 则为可约 上午
    if($result_am['enable'] ==  1){
        $result_am['enable'] = $result_am['timestamp'] > $automated_start ? 1 : 2; // 1为可约，2为休息
    }




//    $result_am['lable']  = $this->dataEchoH_i($time_am[$i_am]) . "-" . $this->dataEchoH_i($time_am[$i_am+1]);

    $result_all[] = $result_am;

    $i_am = $i_am + 1;
}

$i_pm = 0;

while ($time_pm[$i_pm] < $_init_pm_end) {

    $time_pm[]    = strtotime("+30 minutes", $time_pm[$i_pm]);
    $result_pm = array();

    $result_pm['timestamp'] = strtotime("+30 minutes", $time_pm[$i_pm]);

    // 如果显示时间 比当前时间大 则为可约
    $result_pm['enable'] = $result_pm['timestamp'] > $now ? 1 : 0; // 1为可约，0为过期


    // 如果显示时间 比排班时间小 则为可约 下午
    if($result_pm['enable']== 1){
        $result_pm['enable'] = $result_pm['timestamp'] <= $automated_end ? 1 : 2; // 1为可约，2为休息
    }




//    $result_pm['lable'] = $this->dataEchoH_i($time_pm[$i_pm]) . "-" . $this->dataEchoH_i($time_pm[$i_pm+1]);

    $result_all[] = $result_pm;

    $i_pm = $i_pm + 1;
}

echo "<br/>";echo "<br/>";echo "<br/>";echo "检查am";echo "<br/>";echo "<br/>";echo "<br/>";
foreach ($time_am as $index => $_time){
    echo $index . " ". date('Y-m-d H:i:s', $_time);
    echo "<br/>";
}
echo "<br/>";echo "<br/>";echo "<br/>";echo "检查pm";echo "<br/>";echo "<br/>";echo "<br/>";
foreach ($time_pm as $index => $_time){
    echo $index . " ". date('Y-m-d H:i:s', $_time);
    echo "<br/>";
}
//echo "<br/>";echo "<br/>";echo "<br/>";echo "检查am";echo "<br/>";echo "<br/>";echo "<br/>";
//foreach ($result_am as $index => $_time){
//    echo  $_time;
//    echo "<br/>";
//}
//foreach ($result_pm as $index => $_time){
//    echo  $_time;
//    echo "<br/>";
//}
//var_dump($time_array);

/******************************************************* redis *********************************************************/
include_once(MODULE_ROOT . '/model/common.func.php');
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();

$key = 'business_dongyuantang_' . 'appointment' . '_' . $_W['uniacid'];
$kkey = $doctor_sn . "_" . $automated_id . "_" . $Ymd;

//echo $key;
//echo "<br/>";
//echo $kkey;
//echo "<br/>";
//
//echo $_redis->ishExists($key, $kkey);
//echo "<br/>";

$tmp_value = 0;
if ($_redis->ishExists($key, $kkey) == 1) {
    $tmp_value = $_redis->hget($key, $kkey);
    // TODO
//    $tmp_value = intval($tmp_value) + 1;
//    $_redis->hset($key, $kkey, $tmp_value);
} else {
    $_redis->hset($key, $kkey, $tmp_value);
}

if ($tmp_value > $booking_num_wechat) {
    foreach ($result_all as $index => &$item){
        if($item['enable'] == 1){
            $item['enable'] = 3;
        }
    }
}

/******************************************************* redis *********************************************************/


//include $this->template('____select_time');
?>
