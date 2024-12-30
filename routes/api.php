<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\APIHandlerController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::POST('/traceability/fetch/data', [APIHandlerController::class, 'fetch_data']);




Route::POST('/traceability/fetch/item/variant', [APIHandlerController::class, 'fetch_varaint']);
