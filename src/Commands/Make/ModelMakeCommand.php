<?php

namespace LanDao\LaravelCore\Commands\Make;

use Illuminate\Support\Str;
use LanDao\LaravelCore\Commands\ModuleCommandTrait;
use LanDao\LaravelCore\Commands\Support\GenerateConfigReader;
use LanDao\LaravelCore\Commands\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    protected $name = 'landao:make-model';

    protected $description = 'Create a new enum class for the specified module.';

    public function getDestinationFilePath(): string
    {
        $moduleName = $this->getModuleName();
        $path = $this->laravel['modules']->getModulePath($moduleName);
        $filePath = GenerateConfigReader::read('models')->getPath() ?? 'app/Models';
        return $path . $filePath . '/' . $this->getModelName() . 'Model.php';
    }

    protected function getTemplateContents(): string
    {
        $module = $this->getModuleName();
        $module = $module !== 'App' ? $this->laravel['modules']->findOrFail($module) : $module;
        return (new Stub($this->getStubName(), [
            'CLASS_NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getClassNameWithoutNamespace(),
        ]))->render();
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'su.'],
        ];
    }

    public function getModelName(): string|array
    {
        return Str::studly($this->argument('name'));
    }

    private function getClassNameWithoutNamespace(): array|string
    {
        return class_basename($this->getModelName());
    }

    public function getDefaultNamespace(): string
    {
        return 'Models';
    }

    protected function getStubName(): string
    {
        return '/model.stub';
    }
}
