
# 购物车是会检查商品是否下架

```javascript
// 在购物车页更新商品数量时调用
modal.update = function(cartid, num, optionid) {
    core.json('member/cart/update', {
            id: cartid,
            total: num,
            optionid: optionid
        },
        function(ret) {
            if (ret.status == 0) {
                FoxUI.toast.show(ret.result.message);
            }
        },
        true, true);
};
```


request static/js/app/core.js:
url:./index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=member.cart.update
type:post
data:{"id":9,"total":1,"optionid":0}
cache:false

request static/js/app/core.js:
url:./index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=member.cart.update
type:post
data:{"id":8,"total":2,"optionid":0}
cache:false

response 
{"status":0,"result":{"message":"商品未找到或已经下架"}}

response 
{"status":1,"result":{"url":"http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=member.cart"}}