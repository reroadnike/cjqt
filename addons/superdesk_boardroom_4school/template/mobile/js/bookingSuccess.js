/* 
* @Author: anchen
* @Date:   2017-07-25 11:29:38
* @Last Modified by:   anchen
* @Last Modified time: 2017-07-25 11:36:43
*/

$(document).ready(function(){
    $(".js_cancelorder").click(function(){
        $(".popupmask").show();
    });
    $(".js_cancel").click(function(){
        $(".popupmask").hide();
    });
    $(".js_confirm").click(function(){
        $(".popupmask").hide();
        $.alert("已取消订单", 1000);
    })
});