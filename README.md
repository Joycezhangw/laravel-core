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

### 生成 Repository 

> php artisan landao:make-repository 文件具体命名和路径 模块名称
> 没有添加模块名称，生成的文件在 app 目录下

```shell
php artisan landao:make-repository Bolg\\Post Tenant
```
执行以上命名会生成二个对应的文件如下

- `module\Tenant\app\Models\Bolg\PostModel.php`
- `module\Tenant\app\Repositories\Bolg\PostRepo.php`

```shell
php artisan landao:make-repository Bolg\\Post
```
执行以上命名会生成二个对应的文件如下

- `app\Models\Bolg\PostModel.php`
- `app\Repositories\Bolg\PostRepo.php`

### 生成 Model 

```shell
php artisan landao:make-model Bolg\\Post Tenant
```

运行以上命令生成的文件结构为
- `module\Tenant\app\Models\Bolg\PostEnum.php`

### 生成 Enum 

```shell
php artisan landao:make-enum Bolg\\Post Tenant
```
运行以上命令生成的文件结构为
- `module\Tenant\app\Enums\Bolg\PostEnum.php`

### 生成 Request 

```shell
php artisan landao:make-request Bolg\\Post Tenant
```

运行以上命令生成的文件结构为
- `module\Tenant\app\Http\Request\Bolg\PostEnum.php`

### 生成 Migration


```shell
php artisan landao:make-migration crate_post_table Tenant
```


## PHP8 原生注解，路由注解、注入注解

- 注解注入源自 [think-annotation](https://github.com/top-think/think-annotation)
- 注解路由源自 [spatie/laravel-route-attributes](https://github.com/spatie/laravel-route-attributes)
> 这两个包一起使用会冲突，所以整合在一起

```php
<?php

namespace Module\Tenant\Http\Controllers\V1\User;

use LanDao\LaravelCore\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LanDao\LaravelCore\Helpers\ResultHelper;
use LanDao\LaravelCore\Attributes\Inject;
use LanDao\LaravelCore\Attributes\Router\Delete;
use LanDao\LaravelCore\Attributes\Router\Get;
use LanDao\LaravelCore\Attributes\Router\Middleware;
use LanDao\LaravelCore\Attributes\Router\Post as PostAttributes;
use LanDao\LaravelCore\Attributes\Router\Put;
use LanDao\LaravelCore\Attributes\Router\WhereNumber;
use Module\Tenant\Http\Requests\User\PostRequest;
use Module\Tenant\Repositories\User\PostRepo;

#[Middleware(['auth:sanctum', 'auth.tenant', 'userOperate.log'])]
class Post extends ApiController
{
    #[Inject]
    protected PostRepo $postRepo;

    /**
     * 分页列表
     * @param Request $request
     * @return JsonResponse
     */
    #[Get(uri: '/post', name: 'post.index')]
    public function index(Request $request): JsonResponse
    {
        $ret = $this->postRepo->getLists($request->all());
        return $this->success([
            'pagination' => [
                'total' => $ret['total'],
                'page_size' => $ret['per_page'],
                'current_page' => $ret['current_page'],
            ],
            'list' => $ret['data']
        ]);
    }

    /**
     * 新增
     * @param PostRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \LanDao\LaravelCore\Exceptions\RepositoryException
     */
    #[PostAttributes(uri: '/post/store', name: 'post.store')]
    public function store(PostRequest $request): JsonResponse
    {
        $ret = $this->postRepo->addPost($request->all());
        if ($ret['code'] == ResultHelper::CODE_SUCCESS) {
            return $this->success($ret['message']);
        }
        return $this->badSuccessRequest($ret['message']);
    }

    /**
     * 更新
     * @param int $postId
     * @param PostRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \LanDao\LaravelCore\Exceptions\RepositoryException
     */
    #[Put(uri: '/post/{postId}', name: 'post.update'), WhereNumber('postId')]
    public function update(int $postId, PostRequest $request): JsonResponse
    {
        $ret = $this->postRepo->updatePost($postId, $request->all());
        if ($ret['code'] == ResultHelper::CODE_SUCCESS) {
            return $this->success($ret['message']);
        }
        return $this->badSuccessRequest($ret['message']);
    }

    /**
     * 删除
     * @param int $postId
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \LanDao\LaravelCore\Exceptions\RepositoryException
     */
    #[Delete(uri: '/post/{postId}', name: 'post.destroy'), WhereNumber('postId')]
    public function destroy(int $postId): JsonResponse
    {
        $ret = $this->postRepo->deletePost($postId);
        if ($ret['code'] == ResultHelper::CODE_SUCCESS) {
            return $this->success($ret['message']);
        }
        return $this->badSuccessRequest($ret['message']);
    }

    /**
     * 更新某字段
     * @param PostRequest $request
     * @param int $postId
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \LanDao\LaravelCore\Exceptions\RepositoryException
     */
    #[PostAttributes(uri: '/post/modify/postId', name: 'post.modifyFiled'), WhereNumber('postId')]
    public function modifyFiled(PostRequest $request, int $postId): JsonResponse
    {
        if ($postId <= 0) {
            return $this->badSuccessRequest('缺少必要的参数');
        }
        $fieldName = (string)$request->post('field_name');
        $fieldValue = $request->post('field_value');
        $ret = $this->postRepo->updateSomeField($postId, $fieldName, $fieldValue);
        if ($ret['code'] == ResultHelper::CODE_SUCCESS) {
            return $this->success($ret['message']);
        }
        return $this->badSuccessRequest($ret['message']);
    }
}
```

## PHP8 枚举支持、注解描述

```php
<?php
declare(strict_types=1);

namespace Module\Tenant\Enums\System;


use LanDao\LaravelCore\Attributes\Description;
use LanDao\LaravelCore\Enum\EnumExtend;

enum MenuTypeEnum: int
{
    use EnumExtend;

    /**
     * 目录
     * @var int
     */
    #[Description('目录')]
    case MENU_TYPE_CATALOG = 1;
    
    /**
     * 菜单
     * @var int
     */
    #[Description('菜单')]
    case MENU_TYPE_MENU = 2;
    
    /**
     * 权限
     * @var int
     */
    #[Description('权限')]
    case MENU_TYPE_PERMISSION = 3;
}

```

