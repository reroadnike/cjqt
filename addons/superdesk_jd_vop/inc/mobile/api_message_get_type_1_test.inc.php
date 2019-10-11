<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/11/18
 * Time: 9:15 PM
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_message_get_type_1_test
 *
 * 福利内购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=api_message_get_type_1_test
 *
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_message_get_type_1_test
 */

//--1代表订单拆分变更{"id":推送id, "result" : {"pOrder" :父订单id} , "type": 1, "time":推送时间},注意：京东订单可能会被多次拆单； 例如：订单1 首先被拆成订单2、订单3；然后订单2有继续被拆成订单4、订单5；最终订单1的子单是订单3、订单4、订单5；每拆一次单我们都会发送一次拆单消息，但父订单号只会传递订单1（原始单），需要通过查询接口获取到最新所有子单，进行相关更新；

global $_W, $_GPC;

//include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/MessageService.class.php');
//$_messageService = new MessageService();
//$_messageService->messageType_1_Get();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();

// debug
//$_orderService->businessProcessingSelectJdOrder(73529866606);

//bug_订单_吴仕雄_15070961064_拆分单_商品价格变0.md
//$_orderService->businessProcessingSelectJdOrder(72915601854);

//bug_订单_林向冰_13924076023_拆分单_商品价格变0.md
//$_orderService->businessProcessingSelectJdOrder(72973756535);

//bug_订单_张萍_15956518656_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(73248004624);

//bug_订单_刘天桢_15628923209_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(73766516231);

//bug_订单_肖冠鸿_13164736799_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(72656976316);

//bug_订单_李小姐_13824386124_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(73730855436);

//bug_订单_李蓉_13786115753_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(73767314144);

//bug_订单_慎菲_18202201627_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(72741156282);

//bug_订单_蔡锐彬_13760402401_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(72741774068);

//bug_订单_刘素金_15079127043_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(73934190089);

//bug_订单_蔡瑞红_13693386870_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(73150168851);

//bug_订单_李佩雯_13707093380_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(73183176637);

//bug_订单_葛东晓_15801523086_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(72859523157);

//bug_订单_赵群娜_13212278933_拆分单_商品价格变0
//$_orderService->businessProcessingSelectJdOrder(73384421399);

//bug_订单_纪莉娜_13861772027_拆分单_商品少了
//$_orderService->businessProcessingSelectJdOrder(72537513755);

//bug_订单_丘少媚_15220600155_拆分单_某拆分单deleted为1的错.md
//$_orderService->businessProcessingSelectJdOrder(73771129623);


//bug_订单_余小姐_15011727798_拆单后子单总价错
//$_orderService->businessProcessingSelectJdOrder(78860957377);

//$_orderService->businessProcessingSelectJdOrder(77816723929);



//订单编号 : ME20190222162504745491 京东单号 : 88070537734  ---- 21499
//|----订单编号:ME20190222163702545456 京东单号:88070713422 ---- 21503
//|----订单编号:ME20190222163702884909 京东单号:88071194694 ---- 21504
//$_orderService->businessProcessingSelectJdOrder(88070537734)


//订单编号 : ME20190222181357820422 京东单号 : 88076891213
//|----订单编号:ME20190222182504687497 京东单号:88076310151
//|----订单编号:ME20190222182505811979 京东单号:88075405090
//$_orderService->businessProcessingSelectJdOrder(88076891213);


//订单编号 : ME20190222182259686818 京东单号 : 88076506447
//|----订单编号:ME20190222182604406222 京东单号:84153647418
//|----订单编号:ME20190222182604388620 京东单号:84155229976
//$_orderService->businessProcessingSelectJdOrder(88076506447);

//2019年7月11日 14:38:11 zjh
//订单编号:ME20190708155731254566 京东单号:99208168042

//2019年7月11日 14:38:11 zjh
//订单编号:ME20190708155731254566 京东单号:100888573987



$_orderService->businessProcessingSelectJdOrder(100888573987);