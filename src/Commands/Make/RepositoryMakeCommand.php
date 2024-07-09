<?php

namespace LanDao\LaravelCore\Commands\Make;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use LanDao\LaravelCore\Commands\ModuleCommandTrait;
use LanDao\LaravelCore\Commands\Support\GenerateConfigReader;
use LanDao\LaravelCore\Commands\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RepositoryMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    protected $name = 'module:make-repository';

    protected $description = 'Create a new Repositories class for the specified module.';

    public function getDestinationFilePath(): string
    {
        $moduleName = $this->getModuleName();
        $path = $this->laravel['modules']->getModulePath($moduleName);
        $filePath = GenerateConfigReader::read('repositories')->getPath() ?? 'app/Repositories';
        return $path . $filePath . '/' . $this->getEnumName() . 'Repo.php';
    }

    protected function getTemplateContents(): string
    {
        $module = $this->getModuleName();
        $module = $module !== 'App' ? $this->laravel['modules']->findOrFail($module) : $module;
        $namespace = $this->getClassNamespace($module);
        $className = $this->getClassNameWithoutNamespace();
        //生成 Eloquent ORM 命令
        Artisan::call('module:make-model', ['name' => $this->argument('name'), 'module' => $this->argument('module')]);
        return (new Stub($this->getStubName(), [
            'CLASS_NAMESPACE' => $namespace,
            'CLASS' => $className,
            'MODEL_CLASS_NAMESPACE' => Str::replace('Repositories', 'Models', $namespace) . '\\' . $className . 'Model',
        ]))->render();
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the repository class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'su.'],
        ];
    }

    public function getEnumName(): string|array
    {
        return Str::studly($this->argument('name'));
    }

    private function getClassNameWithoutNamespace(): array|string
    {
        return class_basename($this->getEnumName());
    }

    public function getDefaultNamespace(): string
    {
        return 'Repositories';
    }

    protected function getStubName(): string
    {
        return '/repository.stub';
    }
}
