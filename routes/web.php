<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeContoller@index')->name('home');

Route::namespace('Backup')->prefix('backup/')->group(function () {
    
    Route::get('database', 'DatabaseController@dbBackup')
        ->name('backup-database');

    Route::get('database/stop', 'DatabaseController@stopDbBackup')
        ->name('stop-backup-database');
});
