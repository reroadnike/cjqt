由于订单表中的数据已经删除了.所以订单财务跟踪表的数据也随之变成了脏数据.特此删除..以下为删除的财务跟踪表数据id


# 验证
select ofi.* ,FROM_UNIXTIME(ofi.createtime)
FROM ims_superdesk_shop_order_finance as ofi
	left join `ims_superdesk_shop_order` as o on o.id = ofi.orderid
where ofi.uniacid = 16 AND o.ordersn is null
ORDER BY createtime ASC

# 删除 delete sql:
DELETE ofi FROM ims_superdesk_shop_order_finance as ofi
left join `ims_superdesk_shop_order` as o on o.id = ofi.orderid
where ofi.uniacid = 16 AND o.ordersn is null

删除的行:
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
    [3] => 4
    [4] => 5
    [5] => 6
    [6] => 7
    [7] => 8
    [8] => 9
    [9] => 10
    [10] => 11
    [11] => 12
    [12] => 13
    [13] => 14
    [14] => 15
    [15] => 16
    [16] => 17
    [17] => 18
    [18] => 19
    [19] => 20
    [20] => 21
    [21] => 22
    [22] => 23
    [23] => 24
    [24] => 25
    [25] => 26
    [26] => 27
    [27] => 28
    [28] => 30
    [29] => 32
    [30] => 33
    [31] => 38
    [32] => 67
    [33] => 73
    [34] => 74
    [35] => 75
    [36] => 83
    [37] => 84
)



Array
(
    [0] => Array
        (
            [id] => 11
            [orderid] => 1759
        )

    [1] => Array
        (
            [id] => 27
            [orderid] => 1713
        )

    [2] => Array
        (
            [id] => 12
            [orderid] => 1758
        )

    [3] => Array
        (
            [id] => 28
            [orderid] => 1838
        )

    [4] => Array
        (
            [id] => 13
            [orderid] => 1757
        )

    [5] => Array
        (
            [id] => 30
            [orderid] => 1822
        )

    [6] => Array
        (
            [id] => 14
            [orderid] => 1770
        )

    [7] => Array
        (
            [id] => 32
            [orderid] => 1767
        )

    [8] => Array
        (
            [id] => 15
            [orderid] => 1771
        )

    [9] => Array
        (
            [id] => 33
            [orderid] => 1806
        )

    [10] => Array
        (
            [id] => 16
            [orderid] => 1787
        )

    [11] => Array
        (
            [id] => 38
            [orderid] => 1877
        )

    [12] => Array
        (
            [id] => 1
            [orderid] => 1736
        )

    [13] => Array
        (
            [id] => 17
            [orderid] => 1793
        )

    [14] => Array
        (
            [id] => 67
            [orderid] => 1952
        )

    [15] => Array
        (
            [id] => 2
            [orderid] => 1738
        )

    [16] => Array
        (
            [id] => 18
            [orderid] => 1765
        )

    [17] => Array
        (
            [id] => 73
            [orderid] => 1898
        )

    [18] => Array
        (
            [id] => 3
            [orderid] => 1739
        )

    [19] => Array
        (
            [id] => 19
            [orderid] => 1814
        )

    [20] => Array
        (
            [id] => 74
            [orderid] => 1871
        )

    [21] => Array
        (
            [id] => 4
            [orderid] => 1747
        )

    [22] => Array
        (
            [id] => 20
            [orderid] => 1821
        )

    [23] => Array
        (
            [id] => 75
            [orderid] => 1854
        )

    [24] => Array
        (
            [id] => 5
            [orderid] => 1712
        )

    [25] => Array
        (
            [id] => 21
            [orderid] => 1832
        )

    [26] => Array
        (
            [id] => 83
            [orderid] => 1997
        )

    [27] => Array
        (
            [id] => 6
            [orderid] => 1625
        )

    [28] => Array
        (
            [id] => 22
            [orderid] => 1834
        )

    [29] => Array
        (
            [id] => 84
            [orderid] => 1991
        )

    [30] => Array
        (
            [id] => 7
            [orderid] => 1714
        )

    [31] => Array
        (
            [id] => 23
            [orderid] => 1831
        )

    [32] => Array
        (
            [id] => 8
            [orderid] => 1667
        )

    [33] => Array
        (
            [id] => 24
            [orderid] => 1719
        )

    [34] => Array
        (
            [id] => 9
            [orderid] => 1760
        )

    [35] => Array
        (
            [id] => 25
            [orderid] => 1722
        )

    [36] => Array
        (
            [id] => 10
            [orderid] => 1761
        )

    [37] => Array
        (
            [id] => 26
            [orderid] => 1840
        )

)
