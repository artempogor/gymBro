<?php

namespace App\Modules\AbstractModule;

use Illuminate\Support\Facades\Route;

class ModuleManager
{
    /** @var array  */
    private $modules = [];

    /** @var array  */
    private $listeners = [];

    /** @var array  */
    private $migrations = [];

    /** @var array  */
    private $commands = [];

    /** @var array  */
    private $tests = [];

    /**
     * Register single module
     * @param string $moduleName
     * @param Module $module
     */
    public function register(string $moduleName, Module $module)
    {
        $this->modules[] = $module;
        $this->registerListeners($module->getListeners());

        $module->register();

        // TODO refactor permission class first
//        foreach ($module->getExcludePermissions() as $permission => $value) {
//            if (is_string($value)) {
//                Permission::registerExcluded($value);
//            } else {
//                Permission::registerExcluded($permission);
//            }
//        }
//
//        foreach ($module->getPermissions() as $permission => $value) {
//            if (is_string($value)) {
//                Permission::register($value);
//            } else {
//                Permission::registerDynamic($permission);
//            }
//        }

        if ($module->hasMigrations())
            $this->registerMigrationPath($module->getPath());

        if ($module->hasTests())
            $this->registerTestPath($module->getPath());

        $this->commands = array_merge($this->commands, $module->getCommands());

        foreach ($module->getMiddlewares() as $name => $middleware) {
            app('router')->aliasMiddleware($name, $middleware);
        }

        foreach ($module->getRoutes() as $route) {
            Route::namespace($module->getNamespace() . '\Http\Controllers')->group($module->getPath('routes') . $route);
        }
    }

    /**
     * @param array $listeners
     */
    public function registerListeners(array $listeners = []): void
    {
        foreach ($listeners as $event => $listens) {
            if (!isset($this->listeners[$event])) {
                $this->listeners[$event] = $listens;
            } else {
                $this->listeners[$event] = array_merge($this->listeners[$event], $listens);
            }
        }
    }

    /**
     * Register all modules from config
     */
    public function registerModules(): void
    {
        $modules = config('modules.registered', []);

        foreach ($modules as $module) {
            $this->register($module, new $module());
        }
    }

    /**
     * @param array $appListeners
     * @return array
     */
    public function getListeners(array $appListeners = []): array
    {
        foreach ($this->listeners as $event => $listeners) {
            $current = $appListeners[$event] ?? [];
            $appListeners[$event] = array_merge($current, $listeners);
        }

        return $appListeners;
    }

    /**
     * @param string $modulePath
     */
    public function registerMigrationPath(string $modulePath): void
    {
        $this->migrations[] = $modulePath . 'database/migrations';
    }

    public function registerTestPath(string $modulePath): void
    {
        $this->tests[] = $modulePath . 'tests';
    }

    /**
     * @return Module[]|array
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @return array
     */
    public function migrationPaths(): array
    {
        return $this->migrations;
    }

    /**
     * @return array
     */
    public function testPaths(): array
    {
        return $this->tests;
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

}
