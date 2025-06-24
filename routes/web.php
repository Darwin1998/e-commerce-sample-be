<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/db-check', function () {
    return [
        'connection' => config('database.default'),
        'database' => DB::connection()->getDatabaseName(),
        'tables' => DB::select('SHOW TABLES'),
    ];
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
