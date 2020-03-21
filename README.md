# MetInfo-API-UniAPP
<<<<<<< HEAD
通过UniAPP实现米拓MetInfo的前端，MetInfo是一个企业建站的项目（非本人项目），在没有改变原来代码的基础上，把API独立出来给前端使用，该API只支持metinfo7.0及以上版本。
=======
通过UniAPP实现MetInfo的前端，MetInfo是一个企业建站的项目（非本人项目），在没有改变原来代码的基础上，把API独立出来给前端使用。API仅支持metinfo7.0版本，metinfo的5和6版本有sql漏洞。
>>>>>>> 872239802e8699125db4f716bed9cb2c0c5ef158

该服务端的代码请在本人的网站www.joymo.cc 或 https://github.com/ccfleaf/MetInfo-API-UniAPP 中下载。

项目中MetInfo-Api目录为服务端代码，把内容复制到你的MetInfo建站根目录下，然后登录MetInfo后台，在浏览器中输入
http://xxxx/admin/#/app/custform/?c=install&a=dosql
添加一个“自定义表单”插件，用于保存公司相关的信息内容，提供给API使用。

项目中met-uni目录是uniapp代码，在common/api.js中，把var host = "http://www.test.com" 中的域名换成实际米拓网站的网址。

1、[米拓Metinfo的API安装](http://www.joymo.cc/html/2020/metinfo_0308/2.html)  
2、[生成和部署H5网站](http://www.joymo.cc/html/2020/metinfo_0308/3.html)  
3、[Android虚拟机调试Uniapp](http://www.joymo.cc/html/2020/metinfo_0308/4.html)  
4、[前端界面显示与后台对应内容](http://www.joymo.cc/html/2020/metinfo_0321/6.html)  
5、[API接口说明](http://www.joymo.cc/html/2020/metinfo_0321/7.html)  
