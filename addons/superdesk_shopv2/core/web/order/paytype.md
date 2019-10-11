

```
if ($row['paytype'] == 1) {
    $row['paytype'] = '余额支付';
}
 else if ($row['paytype'] == 11) {
    $row['paytype'] = '后台付款';
}
 else if ($row['paytype'] == 21) {
    $row['paytype'] = '微信支付';
}
 else if ($row['paytype'] == 22) {
    $row['paytype'] = '支付宝支付';
}
 else if ($row['paytype'] == 23) {
    $row['paytype'] = '银联支付';
}
 else if ($row['paytype'] == 3) {
    $row['paytype'] = '企业月结';
}
```