<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/23/17
 * Time: 4:58 PM
 * 
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_core&do=test_superdesk_core_sdk
 */

//include_once(MODULE_ROOT . '/sdk/superdesk_core_sdk.php');
//$sdk = new SuperDeskCoreSDK();
//
//$response = $sdk->userInfo('dddddd');
//die(json_encode(json_decode($response,true),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

include_once(IA_ROOT . '/addons/superdesk_core/service/UserInfoService.class.php');
$_userInfoService           = new UserInfoService();
//$superdesk_core_data = $_userInfoService->getOneByUserMobile('13422832499');// 正常
//$superdesk_core_data = $_userInfoService->getOneByUserMobile('13800138001');// 未登陆过 have telplone no openid
$superdesk_core_data = $_userInfoService->getOneByUserMobile('ddd');
die(json_encode($superdesk_core_data,JSON_UNESCAPED_UNICODE));


//{
//    "result": "1",
//    "resultCode": "200",
//    "resultDesc": "success",
//    "returnDTO": {
//        "openId": "o31ne0SRcstvVOjbTUfoEGGcZJyk",
//        "organizationCode": "SZ-HKDS",
//        "organizationName": "航空大厦",
//        "status": "1",
//        "userMobile": "13422832499",
//        "userName": "雨",
//        "userPhotoUrl": null,
//        "virtualCode": "CJQT",
//        "virtualName": "前海超级前台（深圳）信息技术有限公司"
//    },
//    "code": 200
//}

//{
//    "result": "1",
//    "resultCode": "200",
//    "resultDesc": "success",
//    "returnDTO": {
//        "openId": "",
//        "organizationCode": "SZ-HKDS",
//        "organizationName": "航空大厦",
//        "status": "1",
//        "userMobile": "13800138001",
//        "userName": "测试同步（后台——商城）",
//        "userPhotoUrl": null,
//        "virtualCode": "CJQT",
//        "virtualName": "前海超级前台（深圳）信息技术有限公司"
//    },
//    "code": 200
//}

//{
//    "result": "1",
//    "resultCode": "101",
//    "resultDesc": "暂无用户数据",
//    "returnDTO": null,
//    "code": 200
//}


//$response = $sdk->accessToken();
//die(json_encode(json_decode($response,true),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));


//{
//    "result": "1",
//    "resultCode": "200",
//    "resultDesc": null,
//    "returnDTO": {
//    "accessToken": "10_CWdC7XlwvjI7C1Y0k_rFQiZYpoB_n1Cg9gIkn7GUGgysBuLfkkTx-Cux-Fdzwf2VVnrsR3RTAIeZBoqnMbGpo_H_8E8RF-DD9o09Ixr5itLB6Bw6GK68fh0DUffe6Zr8DlMsBffEH1x-Geq-GTRcADANAY"
//    },
//    "code": 200
//}