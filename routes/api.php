<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowsController;
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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */


/**
 * 1. responsible for search handling, using a simple query string as a GET parameter
 */

Route::apiResource('shows', ShowsController::class)->only(["index","show"]);

/**
 * 2. Any other request to the API is invalid and should return the appropriate response.
 */
Route::any('/{any}', function () {
    return response(['error'=>true,'error-msg'=>"404 Not found"],404);
});
