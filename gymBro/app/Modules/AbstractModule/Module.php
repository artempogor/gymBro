<?php

namespace App\Modules\AbstractModule;

use Illuminate\Console\Scheduling\Schedule;

abstract class Module
{
    protected array $permissions = [];

    protected array $excludeRootPermissions = [];

    protected string $namespace = '';

    /**
     * @var string
     */
    protected string $modulePath = '';

    /**
     * @var string
     */
    protected string $moduleName = '';

    /**
     * @var array
     */
    protected array $listeners = [];

    /**
     * @var array
     */
    protected array $routes = [];

    /**
     * @var array
     */
    protected array $commands = [];

    /**
     * @var array
     */
    protected array $middlewares = [];

    /**
     *
     */
    public function register(): void
    {
        //
    }

    /**
     *
     */
    public function registerPermission(): void
    {
        //
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->moduleName;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {

        return $this->namespace;
    }

    /**
     * @param string $prefix
     * @return string
     */
    public function getPath(string $prefix = ''): string
    {
        return base_path($this->modulePath . $prefix . '/');
    }

    /**
     * @return bool
     */
    public function hasMigrations(): bool
    {
        return is_dir($this->getPath('database/migrations'));
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getExcludePermissions(): array
    {
        return $this->excludeRootPermissions;
    }

    /**
     * @return array
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @return bool
     */
    public function hasViews(): bool
    {
        return is_dir($this->getPath('views'));
    }

    /**
     * @return bool
     */
    public function hasTests(): bool
    {
        return is_dir($this->getPath('tests'));
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getSchedule(Schedule $schedule): void {}

}
