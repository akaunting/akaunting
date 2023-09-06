<?php

use Illuminate\Support\Facades\Route;
use Spatie\LaravelIgnition\Http\Controllers\ExecuteSolutionController;
use Spatie\LaravelIgnition\Http\Controllers\HealthCheckController;
use Spatie\LaravelIgnition\Http\Controllers\UpdateConfigController;
use Spatie\LaravelIgnition\Http\Middleware\RunnableSolutionsEnabled;

Route::group([
    'as' => 'ignition.',
    'prefix' => config('ignition.housekeeping_endpoint_prefix'),
    'middleware' => [RunnableSolutionsEnabled::class],
], function () {
    Route::get('health-check', HealthCheckController::class)->name('healthCheck');

    Route::post('execute-solution', ExecuteSolutionController::class)
        ->name('executeSolution');

    Route::post('update-config', UpdateConfigController::class)->name('updateConfig');
});
