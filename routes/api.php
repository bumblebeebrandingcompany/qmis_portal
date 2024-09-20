<?php
use App\Http\Controllers\Pulse\LeadsController;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['web', 'auth:sanctum']], function () {
    // Route::post('/store', [LeadsController::class, 'store']);
});
Route::match(['get', 'post'], '/leads/search', [LeadsController::class, 'searchByPhone']);


