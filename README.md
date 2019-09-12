#该聊天系统主要基于swoft+layim实现

``由于layim版权问题，只用于开发测试，勿用于商业``

#使用方法

```
1、https://github.com/tiemeng/swoftIm.git
2、执行composer install，安装相关依赖
3、修改数据库和Redis相关配置
4、运行相关的数据迁移文件，建立相对应的数据表以及初始的用户
    php bin/swoft migrate:up Frineds
    php bin/swoft migrate:up Group
    php bin/swoft migrate:up Msg
    php bin/swoft migrate:up SystemMessage
    php bin/swoft migrate:up Users
    php bin/swoft migrate:up UserGroup
    php bin/swoft migrate:up InitData
    
6、配置nginx
    server {
      listen 80;
      server_name io.yourhost.com;
    
      location / {
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Host $host;
        proxy_http_version 1.1;
        proxy_pass http://127.0.0.1:18307;
      }
    }
7、运行服务器 php bin/swoft ws:start
8、通过配置的域名访问 io.yourhost.com，使用初始化账号密码登录即可
默认用户：admin 密码：123456
```

#其他
``增加了通过sami包来生成接口文档管理，相关配置详见项目根目录下的config.php``