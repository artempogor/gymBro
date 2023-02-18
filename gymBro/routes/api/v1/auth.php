<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->group(function (){
        Route::post('register-by-phone',[AuthController::class, 'registrationByPhone'])
            ->name('api.v1.auth.register-by-phone');
    });
