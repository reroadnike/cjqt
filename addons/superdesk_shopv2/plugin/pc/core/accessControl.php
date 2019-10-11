<?php

// TODO 添加白名单



header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

//if ($_SERVER['HTTP_ORIGIN'] == 'http://localhost') {
//
//    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
//
//} else {
//
//    header('Access-Control-Allow-Origin: http://0.0.0.0:23333');
//
//}


header('Access-Control-Allow-Credentials: true');
