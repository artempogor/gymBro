<?php

namespace App\Modules\Auth;

use App\Modules\AbstractModule\Module;

class AuthModule extends Module
{
    protected string $moduleName = 'Auth';
    protected string $modulePath = 'app/Modules/Auth/';

    protected string $namespace = 'App\Modules\Auth';

    public function register(): void {}
}
