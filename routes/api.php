<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;



Route::group([
    // 'middleware' => 'CORS'
], function ($router) {

    //Auth Routes
    Route::get('/verify/{hash}', [AuthController::class, 'verify'])->name('user.verify');
    Route::get('/email/verification', [AuthController::class, 'sendverification']);

    Route::post('/register', [AuthController::class, 'register'])->name('register.user');
    Route::post('/login', [AuthController::class, 'login'])->name('login.user');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.user');

    Route::post('/reset', [AuthController::class, 'reset']);
    Route::post('/forgot', [AuthController::class, 'forgot']);
    Route::post('/verifyuser', [AuthController::class, 'verifyuser']);
    Route::post('/get-verified', [AuthController::class, 'verifyStatus']);
});

Route::group([
    'middleware'=>'auth:sanctum',
], function () {
    // Assets
    Route::get('/asset', [AssetController::class, 'index'])->name('asset.index');
    Route::post('/asset', [AssetController::class, 'store'])->name('asset.store');
    Route::get('/asset/{asset}/edit', [AssetController::class, 'edit'])->name('asset.edit');
    Route::post('/asset/{asset}', [AssetController::class, 'update'])->name('asset.update');
    Route::delete('/asset/{asset}', [AssetController::class, 'destroy'])->name('asset.delete');
});

