<?php

namespace LanDao\LaravelCore\Commands\Make;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use LanDao\LaravelCore\Commands\PathNamespace;
use LanDao\LaravelCore\Exceptions\FileAlreadyExistException;
use LanDao\LaravelCore\Generators\FileGenerator;

abstract class GeneratorCommand extends Command
{
    use PathNamespace;

    protected $argumentName = '';

    abstract protected function getTemplateContents();

    abstract protected function getDestinationFilePath();

    public function handle(): int
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            $this->components->task("Generating file {$path}", function () use ($path, $contents) {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();
            });
        } catch (FileAlreadyExistException $e) {
            $this->components->error("File : {$path} already exists.");

            return E_ERROR;
        }

        return 0;
    }

    public function getClass()
    {
        return class_basename($this->argument($this->argumentName));
    }

    public function getDefaultNamespace(): string
    {
        return '';
    }

    public  function getClassNamespace($module){
        $path_namespace = $this->path_namespace(str_replace($this->getClass(), '', $this->argument($this->argumentName)));
        return $this->module_namespace(Str::studly($module), $this->getDefaultNamespace().($path_namespace ? '\\'.$path_namespace : ''));
    }
}
