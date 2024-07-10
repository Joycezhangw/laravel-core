# landao/laravel-core

LaravelCore 是`Laravel`专用个人使用包

[使用文档](https://qilindao.github.io/docs/backend/laravel-lib/index.html)

## 环境需求

- PHP ≥ 8.1
- Laravel ≥ 8.x

## 安装

```shell
composer require landao/laravel-core
```

## 生成配置文件

```shell
php artisan vendor:publish --provider="LanDao\LaravelCore\ServiceProvider"
```

## Artisan 命令生成


>Artisan 命令生成的文件，会根据具体类型更改文件名：`PostEnum`、`PostModel`、`PostRepo`


## Repository 命令生成

> php artisan module:make-repository 文件具体命名和路径 模块名称
> 没有添加模块名称，生成的文件在 app 目录下

```shell
php artisan module:make-repository Bolg\\Post Tenant
```
执行以上命名会生成二个对应的文件如下

- `module\Tenant\app\Models\Bolg\PostModel.php`
- `module\Tenant\app\Repositories\Bolg\PostRepo.php`

```shell
php artisan module:make-repository Bolg\\Post
```
执行以上命名会生成二个对应的文件如下

- `app\Models\Bolg\PostModel.php`
- `app\Repositories\Bolg\PostRepo.php`

## Enum 命令生成

```shell
php artisan module:make-enum Bolg\\Post Tenant
```

运行以上命令生成的文件结构为
- `module\Tenant\app\Enums\Bolg\PostEnum.php`
- 
## Request 命令生成


```shell
php artisan module:make-request Bolg\\Post Tenant
```

运行以上命令生成的文件结构为
- `module\Tenant\app\Http\Request\Bolg\PostEnum.php`

