<?php
namespace Wezeo\Notion;

use Illuminate\Support\Facades\Route;
use Wezeo\Notion\Http\Controllers\NotionController;

Route::group(['prefix' => 'notion'], function () {
    //TODO: Zmen routy na spravne
    Route::post('/find', [NotionController::class, 'index']);
    Route::post('/create', [NotionController::class, 'create']);
    Route::post('/delete', [NotionController::class, 'delete']);
    Route::post('/user', [NotionController::class, 'user']);
    Route::post('/update', [NotionController::class, 'update']);
});
