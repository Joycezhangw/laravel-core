<?php

namespace LanDao\LaravelCore\Annotation;

use Illuminate\Support\Arr;

/**
 * 该路由注解来源于 spatie/laravel-route-attributes
 */
trait InteractsWithRoute
{

    protected function registerRoutes()
    {
        if ($this->shouldRegisterRoutes()) {
            $routeRegistrar = (new RouteRegistrar(app()->router))->useMiddleware(config('landao.annotation.route.middleware') ?? []);
            collect($this->getRouteDirectories())->each(function (string|array $directory, string|int $namespace) use ($routeRegistrar) {
                if (is_array($directory)) {
                    $options = Arr::except($directory, ['namespace', 'base_path', 'patterns', 'not_patterns']);
                    $routeRegistrar
                        ->useRootNamespace($directory['namespace'] ?? app()->getNamespace())
                        ->useBasePath($directory['base_path'] ?? (isset($directory['namespace']) ? $namespace : app()->path()))
                        ->group($options, fn() => $routeRegistrar->registerDirectory($namespace, $directory['patterns'] ?? [], $directory['not_patterns'] ?? []));
                }
            });

        }
    }

    private function shouldRegisterRoutes(): bool
    {
        if (!config('landao.annotation.route.enable')) {
            return false;
        }

        if ($this->app->routesAreCached()) {
            return false;
        }

        return true;
    }

    private function getRouteDirectories(): array
    {
        return config('landao.annotation.route.directories');
    }

}
