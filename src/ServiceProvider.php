<?php
declare (strict_types=1);

namespace LanDao\LaravelCore;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use LanDao\LaravelCore\Annotation\InteractsWithInject;

class ServiceProvider extends LaravelServiceProvider
{
    use InteractsWithInject;

    public function boot(): void
    {
        //自动注入
        $this->autoInject();
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