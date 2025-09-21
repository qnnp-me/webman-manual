# webman是什么
Webman是一款基于Workerman构建的高性能服务框架，集成了HTTP、WebSocket、TCP、UDP等多种模块。通过常驻内存、协程、连接池等先进技术，Webman不仅突破了传统PHP的性能瓶颈，还极大地扩展了其应用场景。

此外，Webman还提供了强大的插件机制，使开发者能够快速集成和复用其他开发者开发的功能模块。无论是构建网站、开发HTTP接口、实现即时通讯、搭建物联网系统，还是开发游戏、TCP/UDP服务、Unix Socket服务等，Webman都能轻松应对，展现出卓越的性能和灵活性。

> **注意**
> 当前文档为`webman v2`版本，如果你使用的是v1版本，请查看[webman v1文档](https://www.workerman.net/doc/webman-v1/)

# webman理念
**以最小内核提供最大的扩展性与最强的性能。**

webman仅提供最核心的功能(路由、中间件、session、自定义进程接口)。其余功能全部复用composer生态，这意味着你可以在webman里使用最熟悉的功能组件，例如在数据库方面开发者可以选择使用Laravel的[illuminate/database](./db/tutorial.md)，也可以是ThinkPHP的[ThinkORM](./db/thinkorm.md)，还可以是其它组件如`Medoo`。在webman里集成他们是非常容易的事情。

# webman具有以下特点

1、高稳定性。webman基于workerman开发，workerman一直是业界bug极少的高稳定性socket框架。

2、超高性能。webman性能高于传统php-fpm框架10-100倍左右，比go的gin echo等框架性能高1倍左右。

3、高复用。无需修改，可以复用现有composer生态。

4、高扩展性。支持自定义进程，可以做workerman能做的任何事情。

5、超级简单易用，学习成本极低，代码书写与传统框架没有区别。

6、支持[二进制打包](./others/bin.md)，无需PHP环境即可直接运行。

7、使用最为宽松友好的MIT开源协议。

# 项目地址
GitHub: https://github.com/walkor/webman **不要吝啬你的小星星哦**

码云: https://gitee.com/walkor/webman **不要吝啬你的小星星哦**

# 第三方权威压测数据


[![](../assets/img/benchmark1.png)](https://www.techempower.com/benchmarks/#section=data-r20&hw=ph&test=db&l=zik073-sf)

带数据库查询业务，webman单机吞吐量达到39万QPS，比传统php-fpm架构的laravel框架高出近80倍。


[![](../assets/img/benchmarks-go.png)](https://www.techempower.com/benchmarks/#section=data-r20&hw=ph&test=db&l=zik073-sf)

带数据库查询业务，webman比同类型go语言的web框架性能高一倍左右。


以上数据来自[techempower.com](https://www.techempower.com/benchmarks/#section=data-r20&hw=ph&test=db&l=zik073-sf) 
