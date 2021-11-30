# Hyperf Casbin

[![Test](https://github.com/donjan-deng/hyperf-casbin/actions/workflows/test.yml/badge.svg)](https://github.com/donjan-deng/hyperf-casbin/actions/workflows/test.yml)
[![Latest Stable Version](https://poser.pugx.org/donjan-deng/hyperf-casbin/v/stable)](https://packagist.org/packages/donjan-deng/hyperf-casbin)
[![Total Downloads](https://poser.pugx.org/donjan-deng/hyperf-casbin/downloads)](https://packagist.org/packages/donjan-deng/hyperf-casbin)
[![License](https://poser.pugx.org/donjan-deng/hyperf-casbin/license)](https://github.com/php-casbin/laravel-authz/blob/master/LICENSE)

Casbin是一个强大的、高效的开源访问控制框架，其权限管理机制支持多种访问控制模型。本项目做了Hyperf适配并自带了一个RBAC模型，使用本项目前你需要先学会如何使用Casbin。


### 使用：


##### 1、发布配置

```shell
php bin/hyperf.php vendor:publish voopoo/hyperf-casbin
```
##### 2、配置
去配置 `config/autoload/` 文件 

##### 3、迁移文件

```shell
php bin/hyperf.php migrate
```

##### 4、组件提供了一个默认的权限校验中间件,可直接使用
```shell
use Voopoo\Casbin\Middleware\PermissionMiddleware;
```






## 官方资源

* [官方文档](https://casbin.org/docs/zh-CN/overview)
* [模型编辑器](https://casbin.org/zh-CN/editor)
* [管理API](https://casbin.org/docs/zh-CN/management-api)
* [RBAC API](https://casbin.org/docs/zh-CN/rbac-api)

## 参考库

* [php-casbin](https://github.com/php-casbin/php-casbin)
* [database-adapter](https://github.com/php-casbin/database-adapter)
* [laravel-authz](https://github.com/php-casbin/laravel-authz)

## License

This project is licensed under the [Apache 2.0 license](LICENSE).



