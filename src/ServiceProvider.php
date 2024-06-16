<?php
declare (strict_types=1);

namespace LanDao\LaravelCore;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use LanDao\LaravelCore\Annotation\InteractsWithInject;
use LanDao\LaravelCore\Annotation\InteractsWithRoute;

class ServiceProvider extends LaravelServiceProvider
{
    use InteractsWithInject,InteractsWithRoute;

    public function boot(): void
    {
        //自动注入
        $this->autoInject();
        //路由注解
        $this->registerRoutes();
        $this->registerNamespaces();
    }

    public function register(): void
    {

    }

    protected function registerNamespaces(): void
    {

    }

    protected function setupStubPath(): void
    {
    }
}
