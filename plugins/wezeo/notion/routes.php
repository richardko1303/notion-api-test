<?php
namespace Wezeo\Notion;

use Illuminate\Support\Facades\Route;
use Wezeo\Notion\Http\Controllers\NotionController;

Route::group(['prefix' => 'notion'], function () {
    Route::post('/find', [NotionController::class, 'index']);
    Route::post('/create', [NotionController::class, 'create']);
    Route::post('/delete', [NotionController::class, 'delete']);
    Route::post('/user', [NotionController::class, 'user']);
    Route::post('/update', [NotionController::class, 'update']);

    /* ZAPIER HANDLER */
    Route::post('/api/v1/receive', [NotionController::class, 'receive']);
});
