


<input type="search" class="search" name="keywords" placeholder="输入关键字...">

<input type="search" id="search" placeholder="输入关键字..." value="">


使手机端软键盘变成搜索二字



<form action="#">
    <input type="search" />
</form>

关键是有的form是没写action 的,是不行的

注意事项
如果<form></form>表单中只有一个<input type="text"/>，则使文本框获取焦点，并单击回车，form会自动提交。

提交路径为action属性拼接到当前路径（action属性默认为空字符串，如果form没有action属性，则提交路径与当前路径相同）。
浏览器表现：Chrome（桌布版、移动版）会出现此问题，Android手机会出现。Safari（桌面版、移动版）不会出现。
解决方法：禁止表单提交。
1 设置成<form onsubmit="return false;">
2 增加一个无name属性的隐藏文本框 <input type="text" style="display:none;/>
3 监听input的keydown事件。

input.addEventListener('keydown', function(e){
    var keywd = e.target.value;
    if(event.keyCode == 13 && keywd) { 
        e.preventDefault();
    } 
});

bug_微信端 搜索 软键盘统一为'搜索' 已解决