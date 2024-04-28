<?php
declare (strict_types=1);

namespace LanDao\LaravelCore;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{

    public function boot(): void
    {
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