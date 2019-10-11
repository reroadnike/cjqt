/* 
 * @Author: anchen
 * @Date:   2017-07-14 14:22:07
 * @Last Modified by:   anchen
 * @Last Modified time: 2017-07-14 14:22:41
 */
/*
 *提示弹窗
 * @param: msg 弹窗所要显示的文本字符串
 * @param： time 弹窗消失的时间-数字，可填可不填
 * @demo: $.alert("提示", 4000)
 */
$.alert = function (msg, time) {
    $(".toast").remove();
    // 样式
    var _css = "'display: none; position: fixed; top: 50%; left: 50%; height: 50px; line-height: 50px; min-width: 140px; padding: 0 10px; background-color: rgba(0, 0, 0, 0.8); color: #fff; font-size: 18px; text-align: center; margin-top: -25px; border-radius: 4px;'"
    //插入元素
    var _html = ("<div class='toast' style=" + _css + ">" + msg + "</div>")
    $("body").append(_html);
    $(".toast").fadeIn(200);
    // 计算marginLeft的值使元素居中
    $(".toast")[0].style.marginLeft = -Math.round(parseInt(window.getComputedStyle($(".toast")[0]).width) / 2) + 'px';
    //控制消失的时间
    setTimeout(function () {
        $(".toast").fadeOut(200, function () {
            $(this).remove()
        });
    }, time || 2000);
}