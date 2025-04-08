<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/save-colvis', function (Request $request) {
    Cache::forever($request->key, $request->colvis);
    return response()->json([
        'success' => true,
        'key' => $request->key,
        'colvis' => $request->colvis
    ]);
});

Route::post('/get-colvis', function (Request $request) {
    $colvis = Cache::get($request->key, []);
    return response()->json([
        'success' => true,
        'key' => $request->key,
        'colvis' => json_decode($colvis)
    ]);
});
