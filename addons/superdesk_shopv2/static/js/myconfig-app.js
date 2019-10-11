var version = +new Date();
require.config({
    urlArgs    : 'v=' + version,
    baseUrl    : '../addons/superdesk_shopv2/static/js/app',
    paths      : {
        'css'                       : '../css',
        'jquery'                    : '../dist/jquery/jquery-2.0.3.min',//jquery-2.0.3.min jquery-1.11.1.min
        'jquery.gcjs'               : '../dist/jquery/jquery.gcjs',
        'tpl'                       : '../dist/tmodjs',
        'foxui'                     : '../dist/foxui/js/foxui.min',
        'foxui.picker'              : '../dist/foxui/js/foxui.picker',
        'foxui.citydata'            : '../dist/foxui/js/foxui.citydata',
        'city-picker'               : '../dist/city-picker/docs/js/city-picker.my',
        'city-picker.data'          : '../dist/city-picker/docs/js/city-picker.data',
        'jquery.qrcode'             : '../dist/jquery/jquery.qrcode.min',
        'jquery.jd_vop_area_cascade': '../dist/jd_vop_area/jquery.jd_vop_area_cascade',
        'ydb'                       : '../dist/Ydb/YdbOnline'


    },
    shim       : {
        'foxui'       : {
            deps: ['jquery']
        },
        'foxui.picker': {
            exports: "foxui",
            deps   : ['foxui', 'foxui.citydata']
        },
        'jquery.jd_vop_area_cascade' : {
            deps: ['jquery']
        },
        'city-picker' : {
            deps: [
                'jquery',
                'city-picker.data',
                'css!../dist/city-picker/docs/css/city-picker.my.css'
            ]
        },
        'jquery.gcjs' : {
            deps: ['jquery']
        }
    },
    waitSeconds: 0
});
