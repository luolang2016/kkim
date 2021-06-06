# kkim

#### 介绍
基于Swoole、WebSocket的在线聊天系统，功能完整，使用了Mysql数据库连接池，Redis连接池，Layui等技术栈，是学习的不二选择。

#### 软件架构

技术栈：

1.  TP6
2.  Swoole
3.  WebSocket
4.  Mysql(samir连接池)
5.  Redis(samir连接池)
6.  Layui
7.  Javascript

#### 安装教程

1.  在宝塔上创建好站点
2.  上传文件到站点根目录
3.  将db目录内的kkim.sql文件导入数据库
4.  修改.env文件，将数据库用户名密码修改正确
5.  终端进入/chat目录，运行: php run.php，即可运行Swoole WebSocket服务。
6.  浏览器打开：http://你的域名/chat
7.  后台：http://你的域名/admin，默认账号：admin，admin888
8.  注册新用户，体验聊天吧

#### 截图展示

1.  运行Swoole
    ![image text](https://gitee.com/lin6699/kkim/raw/master/public/show/1.png)
2.  登录
    ![image text](https://gitee.com/lin6699/kkim/raw/master/public/show/2.png)
3.  注册
    ![image text](https://gitee.com/lin6699/kkim/raw/master/public/show/3.png)
4.  聊天
    ![image text](https://gitee.com/lin6699/kkim/raw/master/public/show/4.png)
5.  后台
    ![image text](https://gitee.com/lin6699/kkim/raw/master/public/show/5.png)

#### 使用说明

1.  本代码仅限学习使用，代码清晰易懂，请勿用于其他用途
2.  有疑问可联系作者，QQ：641612700
3.  前端注册登录后可直接跟其他人聊天，开源版不提供添加好友功能
4.  后端提供展示用户管理列表及聊天记录列表

#### 特技

1.  使用 Readme\_XXX.md 来支持不同的语言，例如 Readme\_en.md, Readme\_zh.md
