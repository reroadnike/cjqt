


bug_总后端_商户端_财务_开票导出

修正时间: 20180521 

商品编码已修正(之前是没有的)

bug_总后端_商户端_财务_开票导出

新问题:

导出的 "货物名称" 要清除空格

--------------------------------------------------------------------------------

ElasticSearch数据_添加词_热更新_搜索分词优化

主要是在elasticsearch不重启的情况下,能让管理员在后端添加词以达到分词热加载

```
"title": {
  "type": "text",
  "fields": {
    "keyword": {
      "type": "text",
      "index": "true",
      "boost": "5",
      "analyzer": "ik_smart",
      "search_analyzer": "ik_smart"
    }
  }
}
```

大概的做法参考

[Elasticsearch之中文分词器插件es-ik的自定义热更新词库](https://www.cnblogs.com/zlslch/p/6441315.html?utm_source=itdadao&utm_medium=referral)
[http响应Last-Modified和ETag以及Apache和Nginx中的配置](http://www.3lian.com/edu/2013/11-25/109909.html)

--------------------------------------------------------------------------------

问题来了

后加添加了词
"通用电脑音箱"
以下样例中,能正常分词的了.

http://localhost:9200/_analyze?pretty

```
{
  "analyzer": "ik_smart",
  "text":"联想(Lenovo) C1535 台式机笔记本通用电脑音箱 2.1低音炮USB供电脑木质小音响 黑色"
}
```


