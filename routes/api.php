<?php

use App\Http\Resources\EstateCollection;
use App\Http\Resources\EstateResource;
use App\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/estates/all', function () {
    return new EstateCollection(Estate::where("contract_type_id", 1)->orderBy('last_modified_on', 'desc')->paginate());
});

Route::get('/estates/most_hits', function () {
    return new EstateCollection(Estate::orderBy('visits_count', 'desc')->paginate());
});

Route::get('/estates/latest', function () {
    return new EstateCollection(Estate::orderBy('created_on', 'desc')->paginate());
});

Route::get('/estates/hot', function () {
    return new EstateCollection(Estate::where('is_hot_offer', true)->orderBy('created_on', 'desc')->paginate());
});

Route::get('/estates/{id}', function ($id) {
    return new EstateResource((Estate::findOrfail($id)));
});
