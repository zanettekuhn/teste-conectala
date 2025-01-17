<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::get('listAll', 'index');
    Route::get('getSomeColumns', 'getSomeColumns');
    Route::get('getByID/{id}', 'getByID');
    Route::post('create', 'save');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});
