<?php
namespace Wezeo\Notion;

use Illuminate\Support\Facades\Route;
use Wezeo\Notion\Http\Controllers\NotionController;

Route::group(['prefix' => 'notion'], function () {
    //TODO: Zmen routy na spravne
    Route::get('/', [NotionController::class, 'index']);
    Route::get('/create', [NotionController::class, 'update']);
    Route::get('/delete', [NotionController::class, 'delete']);
});
