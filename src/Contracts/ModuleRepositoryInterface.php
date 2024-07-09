<?php

namespace LanDao\LaravelCore\Contracts;

interface ModuleRepositoryInterface
{

    public function all();

    public function find(string $name): ?string;

    public function findOrFail(string $name);

    public function getModulePath($moduleName);

    public function getPath(): string;

}
