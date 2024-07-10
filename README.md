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


### Repository 命令生成

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
- 
### Model 命令生成

```shell
php artisan module:make-model Bolg\\Post Tenant
```

运行以上命令生成的文件结构为
- `module\Tenant\app\Models\Bolg\PostEnum.php`

### Enum 命令生成

```shell
php artisan module:make-enum Bolg\\Post Tenant
```

运行以上命令生成的文件结构为
- `module\Tenant\app\Enums\Bolg\PostEnum.php`

### Request 命令生成


```shell
php artisan module:make-request Bolg\\Post Tenant
```

运行以上命令生成的文件结构为
- `module\Tenant\app\Http\Request\Bolg\PostEnum.php`

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

