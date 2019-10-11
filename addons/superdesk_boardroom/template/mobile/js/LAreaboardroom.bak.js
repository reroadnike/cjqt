/* 
* @Author: anchen
* @Date:   2017-07-24 15:08:19
* @Last Modified by:   anchen
* @Last Modified time: 2017-07-24 15:08:32
*/

$(document).ready(function(){
    //初始化type=2时，参考下列数据源
        var provs_data = [{
            "text": "江西省",
            "value": "1"
        }, {
            "text": "广东省",
            "value": "2"
        }];
        var citys_data = {
            "1": [{
                "text": "鹰潭市",
                "value": "11"
            }, {
                "text": "南昌市",
                "value": "12"
            }],
            "2": [{
                "text": "深圳市",
                "value": "13"
            }, {
                "text": "广州市",
                "value": "14"
            }]
        };
        var dists_data = {
            "11": [{
                "text": "余江县",
                "value": "11"
            }, {
                "text": "月湖区",
                "value": "11"
            }],
            "12": [{
                "text": "南昌县",
                "value": "211"
            }, {
                "text": "新建县",
                "value": "22"
            }],
            "13": [{
                "text": "罗湖区",
                "value": "211"
            }, {
                "text": "南山区",
                "value": "22"
            }],
            "14": [{
                "text": "白云区",
                "value": "211"
            }, {
                "text": "蓝天区",
                "value": "22"
            }]
        };


    

        var area = new LArea();
        area.init({
            'trigger': '#lareaAddress',//触发选择控件的文本框，同时选择完毕后name属性输出到该位置
             'valueTo':'#value1',//选择完毕后id属性输出到该位置
             'keys':{id:'value',name:'text'},//绑定数据源相关字段 id对应valueTo的value属性输出 name对应trigger的value属性输出
             'type':2,//数据源类型
             'data':[provs_data,citys_data, dists_data]//数据源
        });
});