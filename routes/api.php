<?php

use App\Http\Controllers\Api\EstatesController;
use App\Http\Resources\BlogResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\EstateCollection;
use App\Http\Resources\EstateResource;
use App\Http\Resources\EvaluationBuildingFloorResource;
use App\Http\Resources\EvaluationBuildingProjectResource;
use App\Http\Resources\EvaluationLocationResource;
use App\Models\Article;
use App\Models\CEvaluationBuildingFloor;
use App\Models\CEvaluationBuildingProject;
use App\Models\CEvaluationLocation;
use App\Models\Contact;
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



Route::controller(EstatesController::class)->group(function () {
    Route::get('/estates/map_search', 'filterAnnouncements');
});

Route::get('/estates/sale', function () {
    return new EstateCollection(Estate::where("contract_type_id", 1)->orderBy('created_on', 'desc')->paginate());
});

Route::get('/estates/rent', function () {
    return new EstateCollection(Estate::where("contract_type_id", 2)->orderBy('last_modified_on', 'desc')->paginate());
});

Route::get('/estates/daily', function () {
    return new EstateCollection(Estate::where("contract_type_id", 3)->orderBy('last_modified_on', 'desc')->paginate());
});

Route::get('/estates/all', function () {
    return new EstateCollection(Estate::where("contract_type_id", 1)->orderBy('last_modified_on', 'desc')->paginate());
});

Route::get('/estates/most_hits', function () {
    return new EstateCollection(Estate::where('price_usd', '>', 24600000)->orderBy('visits_count', 'desc')->paginate());
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


Route::get('/evaluation_locations', function () {
    return new EvaluationLocationResource((CEvaluationLocation::all()));
});

Route::get('/evaluation_building_projects', function () {
    return new EvaluationBuildingProjectResource((CEvaluationBuildingProject::all()));
});

Route::get('/evaluation_building_floors', function () {
    return new EvaluationBuildingFloorResource((CEvaluationBuildingFloor::where('evaluation_building_type_id', 1)->get()));
});

Route::get('/brokers/profession/{type}', function ($type) {
    return  ContactResource::collection((Contact::where('contact_type_id', 3)->whereHas('user', function($q) use ($type) {
        $q->whereHas("professions", function($q) use ($type) {
            $q->where("c_profession_type.id", $type);
        });
    })->orderBy('last_modified_on', 'desc')->limit(50)->get()));
});

Route::get('/brokers/best', function () {
    return  ContactResource::collection((Contact::where('contact_type_id', 3)->whereHas('user')->orderBy('last_modified_on', 'desc')->limit(3)->get()));
});

Route::get('/blog/news', function () {
    return  BlogResource::collection(Article::where('article_type_id', 1)->orderBy('last_modified_on', 'desc')->limit(10)->get());
});

Route::get('/blog/articles', function () {
    return  BlogResource::collection(Article::where('article_type_id', 2)->orderBy('last_modified_on', 'desc')->limit(10)->get());
});

Route::get('/blog/{id}', function ($id) {
    return new BlogResource((Article::findOrfail($id)));
});
