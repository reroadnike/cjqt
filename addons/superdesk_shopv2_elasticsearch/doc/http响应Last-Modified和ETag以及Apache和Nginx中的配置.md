基础知识
　　1) 什么是”Last-Modified”?
在浏览器第一次请求某一个URL时，服务器端的返回状态会是200，内容是你请求的资源，同时有一个Last-Modified的属性标记此文件在服务期端最后被修改的时间，格式类似这样：
Last-Modified: Fri, 12 May 2006 18:53:33 GMT
客户端第二次请求此URL时，根据 HTTP 协议的规定，浏览器会向服务器传送 If-Modified-Since 报头，询问该时间之后文件是否有被修改过：
If-Modified-Since: Fri, 12 May 2006 18:53:33 GMT
如果服务器端的资源没有变化，则自动返回 HTTP 304 （Not Changed.）状态码，内容为空，这样就节省了传输数据量。当服务器端代码发生改变或者重启服务器时，则重新发出资源，返回和第一次请求时类似。从而保证不向客户端重复发出资源，也保证当服务器有变化时，客户端能够得到最新的资源。
2) 什么是”Etag”?
HTTP 协议规格说明定义ETag为“被请求变量的实体值” （参见 —— 章节 14.19）。 另一种说法是，ETag是一个可以与Web资源关联的记号（token）。典型的Web资源可以一个Web页，但也可能是JSON或XML文档。服务器单独负责判断记号是什么及其含义，并在HTTP响应头中将其传送到客户端，以下是服务器端返回的格式：
ETag: "50b1c1d4f775c61:df3"
客户端的查询更新格式是这样的：
If-None-Match: W/"50b1c1d4f775c61:df3"
如果ETag没改变，则返回状态304然后不返回，这也和Last-Modified一样。本人测试Etag主要在断点下载时比较有用。
Last-Modified和Etags如何帮助提高性能?
聪明的开发者会把Last-Modified 和ETags请求的http报头一起使用，这样可利用客户端（例如浏览器）的缓存。因为服务器首先产生 Last-Modified/Etag标记，服务器可在稍后使用它来判断页面是否已经被修改。本质上，客户端通过将该记号传回服务器要求服务器验证其（客户端）缓存。

过程如下:
1,客户端请求一个页面（A）。
2,服务器返回页面A，并在给A加上一个Last-Modified/ETag。
3,客户端展现该页面，并将页面连同Last-Modified/ETag一起缓存。
4,客户再次请求页面A，并将上次请求时服务器返回的Last-Modified/ETag一起传递给服务器。
5,服务器检查该Last-Modified或ETag，并判断出该页面自上次客户端请求之后还未被修改，直接返回响应304和一个空的响应体。

------------------------------------------------------------------------------------------------
正确使用Etag和Expires标识处理，可以使得页面更加有效被Cache。
在客户端通过浏览器发出第一次请求某一个URL时，根据 HTTP 协议的规定，浏览器会向服务器传送报头(Http Request Header)，服务器端响应同时记录相关属性标记(Http Reponse Header)，服务器端的返回状态会是200，格式类似如下：
HTTP/1.1 200 OK
Date: Tue, 03 Mar 2009 04:58:40 GMT
Content-Type: image/jpeg
Content-Length: 83185
Last-Modified: Mon, 22 Nov 2010 16:29:24 GMT
Cache-Control: max-age=2592000
Expires: Thu, 02 Apr 2009 05:14:08 GMT
Etag: "xok.la-961AA72-4CEA99B4415628″客户端第二次请求此URL时，根据 HTTP 协议的规定，浏览器会向服务器传送报头(Http Request Header)，服务器端响应并记录相关记录属性标记文件没有发生改动,服务器端返回304，直接从缓存中读取：
HTTP/1.x 304 Not Modified
Date: Tue, 03 Mar 2009 05:03:56 GMT
Content-Type: image/jpeg
Content-Length: 83185
Last-Modified: Mon, 22 Nov 2010 16:29:24 GMT
Cache-Control: max-age=2592000
Expires: Thu, 02 Apr 2009 05:14:08 GMT
Etag: "xok.la-961AA72-4CEA99B4415628″其中Last-Modified、Expires和Etag是标记页面缓存标识

一、Last-Modified、Expires和Etag相关工作原理
1、Last-Modified
在浏览器第一次请求某一个URL时，服务器端的返回状态会是200，内容是你请求的资源，同时有一个Last-Modified的属性标记 (Http Reponse Header)此文件在服务期端最后被修改的时间，格式类似这样：
Last-Modified: Mon, 22 Nov 2010 16:29:24 GMT客户端第二次请求此URL时，根据 HTTP 协议的规定，浏览器会向服务器传送 If-Modified-Since 报头(Http Request Header)，询问该时间之后文件是否有被修改过：
If-Modified-Since: Mon, 22 Nov 2010 16:29:24 GMT如果服务器端的资源没有变化，则自动返回 HTTP 304 （NotChanged.）状态码，内容为空，这样就节省了传输数据量。当服务器端代码发生改变或者重启服务器时，则重新发出资源，返回和第一次请求时类 似。从而保证不向客户端重复发出资源，也保证当服务器有变化时，客户端能够得到最新的资源。
注：如果If-Modified-Since的时间比服务器当前时间(当前的请求时间request_time)还晚，会认为是个非法请求
2、Etag工作原理
HTTP 协议规格说明定义ETag为”被请求变量的实体标记” （参见14.19）。简单点即服务器响应时给请求URL标记，并在HTTP响应头中将其传送到客户端，类似服务器端返回的格式：
Etag: "xok.la-961AA72-4CEA99B4415628″客户端的查询更新格式是这样的：
If-None-Match: "xok.la-961AA72-4CEA99B4415628″如果ETag没改变，则返回状态304。
即:在客户端发出请求 后，Http Reponse Header中包含 Etag: “xok.la-961AA72-4CEA99B4415628″
标识，等于告诉Client端，你拿到的这个的资源有表示 ID：xok.la-961AA72-4CEA99B4415628。当下次需要发Request索要同一个 URI的时候，浏览器同时发出一个If-None-Match报头( Http RequestHeader)此时包头中信息包含上次访问得到的Etag: “xok.la-961AA72-4CEA99B4415628″标识。
If-None-Match: "xok.la-961AA72-4CEA99B4415628",这样，Client端等于Cache了两份，服务器端就会比对2者的etag。如果 If- None-Match为False，不返回200，返回304 (Not Modified) Response。
3、Expires
给出的 日期/时间后，被响应认为是过时。如Expires: Thu, 02 Apr 2009 05:14:08 GMT
需和Last-Modified结合使用。用于控制请求文件的有效时间，当请求数据在有效期内时客 户端浏览器从缓存请求数据而不是服务器端. 当缓存中数据失效或过期，才决定从服务器更新数据。
4、Last-Modified和Expires
Last- Modified标识能够节省一点带宽，但是还是逃不掉发一个HTTP请求出去，而且要和Expires一起用。而Expires标识却使得浏览器干脆连 HTTP请求都不用发，比如当用户F5或者点击Refresh按钮的时候就算对于有Expires的URI，一样也会发一个HTTP请求出去，所 以，Last-Modified还是要用的，而 且要和Expires一起用。
5、 Etag和Expires
如果服务器端同时设置了 Etag和Expires 时，Etag原理同样，即与Last-Modified/Etag对应的HttpRequest Header:If-Modified-Since和If-None-Match。我们可以看到这两个Header的值和WebServer发出的 Last-Modified,Etag值完全一样；在完全匹配If-Modified-Since和If-None-Match即检查完修改时间和 Etag之后，服务器才能返回304.
6、Last-Modified和Etag
Last-Modified 和ETags请求的http报头一起使用，服务器首先产生 Last-Modified/Etag标记，服务器可在稍后使用它来判断页面是否已经被修改，来决定文件是否继续缓存
过程如下:
1. 客户端请求一个页面（A）。
2. 服务器返回页面A，并在给A加上一个Last-Modified/ETag。
3. 客户端展现该页面，并将页面连同Last-Modified/ETag一起缓存。
4. 客户再次请求页面A，并将上次请求时服务器返回的Last-Modified/ETag一起传递给服务器。
5. 服务器检查该Last-Modified或ETag，并判断出该页面自上次客户端请求之后还未被修改，直接返回响应304和一个空的响应体。
注：
1、Last- Modified和Etag头都是由Web Server发出的Http Reponse Header，Web Server应该同时支持这两种头。
2、Web Server发送完Last-Modified/Etag头给客户端后，客户端会缓存这些头；
3、客户端再次发起相同页面的请求时，将分别发送与Last-Modified/Etag对应的Http RequestHeader:If-Modified-Since和If-None-Match。我们可以看到这两个Header的值和 WebServer发出的Last-Modified,Etag值完全一样；
4、 通过上述值到服务器端检查，判断文件是否继续缓存；

二、Apache、 Lighttpd和Nginx中针配置Etag和Expires，有效缓存纯静态如css/js/pic/页面/流媒体等文件。
A、Expires
A.1、 Apache Etag
使用Apache的mod_expires 模块来设置，这包括控制应答时的Expires头内容和Cache-Control头的max-age指令
ExpiresActive On
ExpiresByType image/gif "access plus 1 month"
ExpiresByType image/jpg "access plus 1 month"
ExpiresByType image/jpeg "access plus 1 month"
ExpiresByType image/x-icon "access plus 1 month"
ExpiresByType image/bmp "access plus 1 month"
ExpiresByType image/png "access plus 1 month"
ExpiresByType text/html "access plus 30 minutes"
ExpiresByType text/css  "access plus 30 minutes"
ExpiresByType text/txt  "access plus 30 minutes"
ExpiresByType text/js   "access plus 30 minutes"
ExpiresByType application/x-javascript   "access plus 30 minutes"
ExpiresByType application/x-shockwave-flash     "access plus 30 minutes"或
<ifmodule mod_expires.c>
    <filesmatch ".(jpg|gif|png|css|js)$">
    ExpiresActive on
    ExpiresDefault "access plus 1 year"
    </filesmatch>
</ifmodule>当设置了expires后，会自动输出Cache-Control 的max-age 信息
具体关于 Expires 详细内容可以查看Apache官方文档。
在这个时间段里，该文件的请求都将直接通过缓存服务器获取，
当然如果需要忽略浏览器的刷新请求（F5)，缓存服务器squid还需要使用 refresh_pattern 选项来忽略该请求
refresh_pattern -i .gif$ 1440 100% 28800 ignore-reload
refresh_pattern -i .jpg$ 1440 100% 28800 ignore-reload
refresh_pattern -i .jpeg$ 1440 100% 28800 ignore-reload
refresh_pattern -i .png$ 1440 100% 28800 ignore-reload
refresh_pattern -i .bmp$ 1440 100% 28800 ignore-reload
refresh_pattern -i .htm$ 60 100% 100 ignore-reload
refresh_pattern -i .html$ 1440 50% 28800 ignore-reload
refresh_pattern -i .xml$ 1440 50% 28800 ignore-reload
refresh_pattern -i .txt$ 1440 50% 28800 ignore-reload
refresh_pattern -i .css$ 1440 50% 28800 reload-into-ims
refresh_pattern -i .js$ 60 50% 100 reload-into-ims
refresh_pattern . 10 50% 60有关Squid中Expires的说明，请参考Squid官方中refresh_pattern介 绍。
A.2、Lighttpd Expires
和Apache一样Lighttpd设置expire也要先查看是否支持了mod_expire模 块，
下面的设置是让URI中所有images目录下的文件1小时后过期；
expire.url = ( "/images/" => "access 1 hours" )下面是让作用于images目录及其子目录的文件；
$HTTP["url"] =~ "^/images/" {

expire.url = ( "" => "access 1 hours" )

}也可以指定文件的类型；
$HTTP["url"] =~ ".(jpg|gif|png|css|js)$" {

expire.url = ( "" => "access 1 hours" )

}具体参考Lighttpd官方Expires解释
A.3、Nginx中Expireslocation ~ .*.(gif|jpg|jpeg|png|bmp|swf)$
{
expires 30d;
}
location ~ .*.(js|css)?$
{
expires 1h;
} 这类文件并不常修改，通过 expires 指令来控制其在浏览器的缓存，以减少不必要的请求。 expires 指令可以控制 HTTP 应答中的” Expires “和” Cache-Control “的头标（起到控制页面缓存的作用）。其他请参考Nginx中Expires
B.1、Apache中Etag设置在Apache中设置Etag的支持比较简单，只用在含有静态文件的目录中建立一个文件.htaccess, 里面加入：
FileETag MTime Size这样就行了，详细的可以参考Apache的FileEtag文档页
B.2、 Lighttpd Etag在Lighttpd中设置Etag支持：
etag.use-inode: 是否使用inode作为Etag
etag.use-mtime: 是否使用文件修改时间作为Etag
etag.use-size: 是否使用文件大小作为Etag
static-file.etags: 是否启用Etag的功能
第四个参数肯定是要enable的， 前面三个就看实际的需要来选吧，推荐使用修改时间
B.3、 Nginx Etag
Nginx中默认没有添加对Etag标识.Igor Sysoev的观点”在对静态文件处理上看不出如何Etag好于Last-Modified标识。”
Note:
Yes, it's addition,and it's easy to add, however, I do not see howETag is better than Last-Modified for static files. -Igor Sysoev
A nice short description is here:
http://www.mnot.net/cache_docs/#WORK
It looks to me that it makes some caches out there to cache theresponse from the origin server more reliable as in rfc2616(ftp://ftp.rfc-editor.org/in-notes/rfc2616.txt) is written.3.11 Entity Tags 13.3.2 Entity Tag Cache Validators 14.19 ETag
当然也有第三方nginx- static-etags 模块了，请参考
https://github.com/mikewest/nginx-static-etags
三、对于非实时交互动态页面中Expires和Etag处理
对数据更新并不频繁、如tag分类归档等等，可以考虑对其cache。简单点就是在非实时交互的动 态程序中输出expires和etag标识，让其缓存。但需要注意关闭session，防止http response时http header包含session id标识；
3.1、Expires
如expires.php
<?php

header('Cache-Control: max-age=86400,must-revalidate');

header('Last-Modified: ' .gmdate('D, d M Y H:i:s') . ' GMT' );

header("Expires: " .gmdate ('D, d M Y H:i:s', time() + '86400′ ). ' GMT');

?>以上信息表示该文件自请求后24小时后过期。
其他需要处理的动态页面直接调用即可。
3.2、Etag
根据Http返回状态来处理。当返回304直接从缓 存中读取
如etag.php
>
cache();
echo date("Y-m-d H:i:s");
function cache()
{
$etag = "http://xok.la";
if ($_SERVER['HTTP_IF_NONE_MATCH'] == $etag)
{
header('Etag:'.$etag,true,304);
exit;
}
else header('Etag:'.$etag);
}
?>

client                                          server
HTTP_IF_NONE_MATCH          <==> etag
HTTP_IF_MODIFIED_SINCE      <==> Last-Modified