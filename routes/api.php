<?php

declare(strict_types=1);

use App\Http\Controllers\Api\DemoApiController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DemoApiController::class, 'dashboard']);
Route::get('orders', [DemoApiController::class, 'orders']);
Route::get('customers', [DemoApiController::class, 'customers']);
Route::get('containers', [DemoApiController::class, 'containers']);
Route::get('pipeline', [DemoApiController::class, 'pipeline']);
Route::post('pipeline/{lead}/move', [DemoApiController::class, 'moveLead']);
Route::get('report', [DemoApiController::class, 'report']);
Route::get('report.csv', [DemoApiController::class, 'reportCsv']);
