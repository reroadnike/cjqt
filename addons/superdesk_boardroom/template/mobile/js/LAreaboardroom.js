/* 
* @Author: anchen
* @Date:   2017-07-24 15:08:19
* @Last Modified by:   anchen
* @Last Modified time: 2017-07-24 15:08:32
*/

$(document).ready(function(){
    //初始化type=2时，参考下列数据源
        var provs_data = [{
            "text": "天津市",
            "value": "1"
        }];
        var citys_data = {
            "1": [{
                "text": "天津市",
                "value": "11"
            }]
        };
        var dists_data = {
            "11": [{
                "text": "南开区",
                "value": "11"
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