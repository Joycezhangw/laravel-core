<?php

namespace LanDao\LaravelCore\Annotation;

use LanDao\LaravelCore\Attributes\Inject;
use ReflectionObject;

/**
 * 依赖注入
 * @author https://github.com/top-think/think-annotation
 * @package LanDao\LaravelCore\Annotation
 * @property App $app
 */
trait InteractsWithInject
{
    protected function autoInject(): void
    {
        $isEnable = config('landao.annotation.inject.enable', true);
        if ($isEnable) {
            $this->app->resolving(function ($object, $app) {
                //由于 spatie/laravel-route-attributes 冲突，所以，此处要判断是否是object
                //本来以为是 spatie/laravel-route-attributes 的冲突，所以将 spatie/laravel-route-attributes copy 一份到本扩展中
                if(!is_object($object)) return;
                if ($this->isInjectClass(get_class($object))) {
                    $refObject = new ReflectionObject($object);
                    foreach ($refObject->getProperties() as $refProperty) {
                        if ($refProperty->isDefault() && !$refProperty->isStatic()) {
                            $attrs = $refProperty->getAttributes(Inject::class);
                            if (!empty($attrs)) {
                                if (!empty($attrs[0]->getArguments()[0])) {
                                    $type = $attrs[0]->getArguments()[0];
                                } elseif ($refProperty->getType() && !$refProperty->getType()->isBuiltin()) {
                                    $type = $refProperty->getType()->getName();
                                }

                                if (isset($type)) {
                                    $value = $app->make($type);
                                    if (!$refProperty->isPublic()) {
                                        $refProperty->setAccessible(true);
                                    }
                                    $refProperty->setValue($object, $value);
                                }
                            }
                        }
                    }
                    if ($refObject->hasMethod('__injected')) {
                        $app->invokeMethod([$object, '__injected']);
                    }
                }
            });
        }
    }

    protected function isInjectClass($className): bool
    {
        $namespaces = config('landao.annotation.inject.namespaces', []);
        foreach ($namespaces as $namespace) {
            $namespace = rtrim($namespace, '\\') . '\\';
            if (0 === stripos(rtrim($className, '\\') . '\\', $namespace)) {
                return true;
            }
        }
        return false;
    }
}
