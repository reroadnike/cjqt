


表 商品 多分类问题

id cates (varchar)     pcate(int)  ccate(int)  tcate(int)   pcates(varchar)   ccates(varchar)    tcates(varchar)
1  , (999,998)         ,1          ,100        ,998         , (1,2)          ,(100,101)          ,(999,998)


假定我要查分类为100的商品

select * from goods where FIND_IN_SET(100,cates) <>0

就可以找到分类100的商品


{
    "terms": {
        "cates": [
            "100"
        ]
    }
},