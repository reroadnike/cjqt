

bug_请在超级前台注册登陆


修正代码

// $diemsg = '请在超级前台注册登陆';
// $this->error($diemsg);

header('location: ' . 'http://www.avic-s.com/super_reception/wechat/appUser/bindphone');
exit();

地址为彩娇提供,如果地址有问题,请联系再作修正