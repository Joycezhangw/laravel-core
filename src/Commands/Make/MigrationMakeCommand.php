<?php

namespace LanDao\LaravelCore\Commands\Make;

use Illuminate\Support\Str;
use LanDao\LaravelCore\Commands\ModuleCommandTrait;
use LanDao\LaravelCore\Commands\Support\GenerateConfigReader;
use LanDao\LaravelCore\Commands\Support\Migrations\NameParser;
use LanDao\LaravelCore\Commands\Support\Migrations\SchemaParser;
use LanDao\LaravelCore\Commands\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrationMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    protected $name = 'landao:make-migration';

    protected $description = 'Create a new migration for the specified module.';

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The migration name will be created.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be created.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['fields', null, InputOption::VALUE_OPTIONAL, 'The specified fields table.', null],
            ['plain', null, InputOption::VALUE_NONE, 'Create plain migration.'],
        ];
    }

    public function getSchemaParser()
    {
        return new SchemaParser($this->option('fields'));
    }

    public function getTemplateContents()
    {
        $parser = new NameParser($this->argument('name'));
        if ($parser->isCreate()) {
            return Stub::create('/migration/create.stub', [
                'class' => $this->getClass(),
                'table' => $parser->getTableName(),
                'fields' => $this->getSchemaParser()->render()
            ]);
        } elseif ($parser->isAdd()) {
            return Stub::create('/migration/add.stub', [
                'class' => $this->getClass(),
                'table' => $parser->getTableName(),
                'fields_up' => $this->getSchemaParser()->up(),
                'fields_down' => $this->getSchemaParser()->down(),
            ]);
        } elseif ($parser->isDelete()) {
            return Stub::create('/migration/delete.stub', [
                'class' => $this->getClass(),
                'table' => $parser->getTableName(),
                'fields_down' => $this->getSchemaParser()->up(),
                'fields_up' => $this->getSchemaParser()->down(),
            ]);
        } elseif ($parser->isDrop()) {
            return Stub::create('/migration/drop.stub', [
                'class' => $this->getClass(),
                'table' => $parser->getTableName(),
                'fields' => $this->getSchemaParser()->render(),
            ]);
        }
        return Stub::create('/migration/plain.stub', [
            'class' => $this->getClass(),
        ]);
    }

    protected function getDestinationFilePath()
    {
        $moduleName = $this->getModuleName();
        $path = $this->laravel['landaoModules']->getModulePath($moduleName);
        $generatorPath = GenerateConfigReader::read('migration')->getPath() ?? 'database/migrations';
        return $path . $generatorPath . '/' . $this->getFileName() . '.php';
    }

    private function getFileName()
    {
        return date('Y_m_d_His_') . $this->argument('name');
    }

    private function getClassName()
    {
        return Str::studly($this->argument('name'));
    }

    public function getClass()
    {
        return $this->getClassName();
    }

    public function handle(): int
    {
        $this->components->info('Created a migration for ' . $this->getClassName());

        if (parent::handle() === E_ERROR) {
            return E_ERROR;
        }
        if (app()->environment() === 'testing') {
            return 0;
        }
        return 0;
    }

}
