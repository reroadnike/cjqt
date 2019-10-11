<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/10/17
 * Time: 9:59 AM
 * @url http://192.168.1.124/superdesk/app/index.php?i=15&c=entry&m=superdesk_boardroom_4school&do=test_array
 */

/*
合并数组

array_merge()函数将数组合并到一起，返回一个联合的数组。所得到的数组以第一个输入数组参数开始，按后面数组参数出现的顺序依次迫加。其形式为：

array array_merge (array array1 array2…,arrayN)

这个函数将一个或多个数组的单元合并起来，一个数组中的值附加在前一个数组的后面。返回作为结果的数组。
如果输入的数组中有相同的字符串键名，则该键名后面的值将覆盖前一个值。然而，如果数组包含数字键名，后面的值将不会覆盖原来的值，而是附加到后面。
如果只给了一个数组并且该数组是数字索引的，则键名会以连续方式重新索引。
*/

//$fruits = array("apple","banana","pear");
//$numbered = array("1","2","3");
//$cards = array_merge($fruits, $numbered);
//shuffle($cards);
//print_r($cards);

//Array ( [0] => 1 [1] => 3 [2] => 2 [3] => apple [4] => pear [5] => banana )


/*
追加数组

array_merge_recursive()函数与array_merge()相同，可以将两个或多个数组合并在一起，形成一个联合的数组．两者之间的区别在于，当某个输入数组中的某个键己经存在于结果数组中时该函数会采取不同的处理方式．array_merge()会覆盖前面存在的键/值对，替换为当前输入数组中的键/值对，而array_merge_recursive()将把两个值合并在一起，形成一个新的数组，并以原有的键作为数组名。还有一个数组合并的形式，就是递归追加数组。其形式为：

array array_merge_recursive(array array1,array array2[…,array arrayN])

程序实例如下：
*/

$fruit1 = array("apple" => "red", "banana" => "yellow");
$fruit2 = array("pear" => "yellow", "apple" => "green");
$result = array_merge_recursive($fruit1, $fruit2);
print_r($result);

// output
/*
Array
(
    [apple] => Array
    (
        [0] => red
        [1] => green
    )
    [banana] => yellow
    [pear] => yellow
)
*/

//现在键 apple 指向一个数组，这个数组由两个颜色值组成的索引数组。


/*
连接数组

array_combine()函数会得到一个新数组，它由一组提交的键和对应的值组成。其形式为：

array array_combine(array keys,array values)
注意，两个输入数组必须大小相同，不能为空。示例如下：
*/

//$name = array("apple", "banana", "orange");
//$color = array("red", "yellow", "orange");
//$fruit = array_combine($name, $color);
//print_r($fruit);

// output
// Array ( [apple] => red [banana] => yellow [orange] => orange )
?>


