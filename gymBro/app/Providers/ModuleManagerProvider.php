<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ModuleManagerProvider extends ServiceProvider
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function register(): void
    {
        // Register modules
        $this->app->get('moduleManager')->registerModules();

        // Load migrations
        $this->loadMigrationsFrom($this->app->get('moduleManager')->migrationPaths());

        // Load views
        foreach ($this->app->get('moduleManager')->getModules() as $module) {
            if ($module->hasViews()) {
                $this->loadViewsFrom($module->getPath('views'), $module->getName());
            }
        }

//        // Register doctrine
//        if (isset($this->app->config['doctrine'])) {
//            foreach ($this->app->get('moduleManager')->getModules() as $module) {
//                if (is_dir($module->getPath('Domain/Entities'))) {
//                    $config = $this->app->config['doctrine'];
//                    $config['managers'][$module->getDoctrineManager()]['paths'][] = $module->getPath('Domain/Entities');
//
//                    $this->app->config['doctrine'] = $config;
//                }
//            }
//        }

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands($this->app->get('moduleManager')->getCommands());
        }
    }
}
