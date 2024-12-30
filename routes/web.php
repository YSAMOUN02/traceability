<?php

use App\Http\Controllers\itemController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', [itemController::class, 'dashboard']);  //no null view
Route::post('/traceability/search', [itemController::class, 'traceability_search']);

Route::get('/traceability/lot', [itemController::class, 'traceability']);
Route::get('/traceability/item/peroid/page={page}', [itemController::class, 'traceability_item_peroid']);

Route::post('/traceability/item/and/variant', [itemController::class, 'traceability_item_with_variant']);

Route::post('/traceability/item/and/variant/search', [itemController::class, 'traceability_item_with_variant_search']);

Route::post('/traceability/item/detail/data', [itemController::class, 'traceability_raw']);



Route::get('/traceability/search/list/{item}', [itemController::class, 'traceability_back']);

Route::get('/traceability/search/{item}/{variant}/{lot}/{line2}/{line_no}/{no_row}/{no}/{consum}/{output_no}', [itemController::class, 'traceability_search_lot']);


Route::get('/rest', [itemController::class, 'view_rest_po']);
Route::get('/all/item', [itemController::class, 'all_item']);


Route::get('/traceability/search/{item}/{variant}/{lot}/{line2}/{line_no}/{no_row}/{no}/{consum}/{output_no}/export', [itemController::class, 'traceability_search_lot_export']);
Route::get('/excel', [itemController::class, 'export_data']);

