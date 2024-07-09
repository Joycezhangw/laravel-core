<?php
// +----------------------------------------------------------------------
// | 通用类包
// +----------------------------------------------------------------------
// | Copyright (c) 2020 https://qilindao.github.io/docs/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: joyecZhang <zhangwei762@163.com>
// +----------------------------------------------------------------------

declare (strict_types=1);

namespace LanDao\LaravelCore\Helpers;


use Illuminate\Support\Facades\Route;

/**
 * laravel 一些助手函数
 * Class LaravelHelper
 * @package LanDao\LaravelCore\Helpers
 */
class LaravelHelper
{

    /**
     * 获取当前路由所在的模块、控制器、方法名称
     * @return array
     */
    public static function getTemplatePath(): array
    {
        $action = Route::current()->getActionName();
        list($class, $method) = explode('@', $action);
        // 模块名
        $module = str_replace(
            '\\',
            '.',
            str_replace(
                'App\\Http\\Controllers\\',
                '',
                trim(
                    implode('\\', array_slice(explode('\\', $class), 0, -1)),
                    '\\'
                )
            )
        );
        $controller = substr(strrchr($class, '\\'), 1);
        return ['module' => $module === 'App.Http.Controllers' ? '' : $module, 'controller' => $controller, 'method' => $method];
    }

}
