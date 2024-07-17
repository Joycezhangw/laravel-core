<?php

namespace LanDao\LaravelCore\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use LanDao\LaravelCore\Commands;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands(self::defaultCommands()->toArray());
    }

    public function provides(): array
    {
        return self::defaultCommands()->toArray();
    }

    public static function defaultCommands(): Collection
    {
        return collect([
            Commands\Make\EnumMakeCommand::class,
            Commands\Make\RepositoryMakeCommand::class,
            Commands\Make\ModelMakeCommand::class,
            Commands\Make\RequestMakeCommand::class,
            Commands\Make\MigrationMakeCommand::class
        ]);
    }
}
