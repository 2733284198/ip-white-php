## ip 白名单处理

使用PHP+redis快速处理白名单，可动态管理白名单列表。

dbherlper.php 管理IP白名单页面，默认渲染所有白名单内ip，可删除，可添加，可批量操作。

index.php 获取当前用户的IP，并根据白名单内IP查找是否存在，不存在则返回403。

升级版中引入了panel方便大家快速感知该程序的功能。

![QQ20170428](/Users/wangyong/www/ip-white-php/QQ20170428.png)



