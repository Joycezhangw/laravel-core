<?php

namespace LanDao\LaravelCore\Module;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use LanDao\LaravelCore\Contracts\ModuleRepositoryInterface;
use LanDao\LaravelCore\Exceptions\ModuleNotFoundException;

class Module implements ModuleRepositoryInterface
{

    public function __construct(
        protected Container $app,
        protected ?string   $path = null
    )
    {
    }

    public function getPath(): string
    {
        return $this->path ?: base_path('module');
    }

    /**
     * 获取所有模块
     * @return array
     */
    public function all(): array
    {
        return $this->scanModules();
    }

    public function count(): int
    {
        return count($this->all());
    }

    public function has($name): bool
    {
        return array_key_exists($name, $this->all());
    }

    public function find(string $name): ?string
    {
        foreach ($this->all() as $module) {
            if (strtoupper($name) == strtoupper($module)) {
                return $module;
            }
        }
        return null;
    }

    public function getModulePath($module)
    {

        try {
            return $this->getPath() . DIRECTORY_SEPARATOR . $this->findOrFail($module) . DIRECTORY_SEPARATOR;
        } catch (ModuleNotFoundException $e) {
            return '';//base_path('app') . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @param string $name
     * @return string|null
     * @throws ModuleNotFoundException
     */
    public function findOrFail(string $name): ?string
    {
        $module = $this->find($name);
        if ($module !== null) {
            return $module;
        }
        throw new ModuleNotFoundException("Module [{$name}] does not exist!");
    }


    protected function scanModules(): array
    {
        $directory = base_path('module');
        return array_filter(scandir($directory), fn($item) => is_dir($directory . DIRECTORY_SEPARATOR . $item) && !in_array($item, ['.', '..']));
    }
}
