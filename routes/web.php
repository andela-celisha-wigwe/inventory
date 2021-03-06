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

Route::get('/', function () {
	return "Welcome to Roy's Inventory API.";
});

Route::group(['prefix' => '/api/v1'], function () {

	Route::get('/', function () {
		return response()->json([
			'message' => "Roy's Inventory API - Home",
		], 200);
	});

	Route::resource('/inventories', API\V1\InventoriesController::class, [
		'only' => ['index', 'show', 'store']
	]);

});
