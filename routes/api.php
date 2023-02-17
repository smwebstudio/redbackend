<?php

use App\Http\Controllers\Api\EstatesController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\OptionsController;
use App\Http\Resources\BlogResource;
use App\Http\Resources\ContactEstatesResource;
use App\Http\Resources\EstateCollection;
use App\Http\Resources\EstateDetailsResource;
use App\Http\Resources\OptionTypeResource;
use App\Http\Resources\EstateResource;
use App\Http\Resources\EvaluationBuildingFloorResource;
use App\Http\Resources\EvaluationBuildingProjectResource;
use App\Http\Resources\EvaluationLocationResource;
use App\Http\Resources\FilterResource;
use App\Http\Resources\LocationResource;
use App\Models\Article;
use App\Models\CEvaluationBuildingFloor;
use App\Models\CEvaluationBuildingProject;
use App\Models\CEvaluationLocation;
use App\Models\CLocationProvince;
use App\Models\Contact;
use App\Models\Estate;
use App\Models\EstateOptionType;
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

Route::middleware(['setLocale'])->group(function () {

    Route::controller(EstatesController::class)->group(function () {
        Route::get('/estates/map_search', 'filterAnnouncements');
        Route::get('/estates/filter/estates', 'filterEstates');
        Route::get('/estates/all', 'filterEstates');
    });

    Route::get('/estates/rent', function () {
        return new EstateCollection(Estate::where("contract_type_id", 2)->orderBy('last_modified_on', 'desc')->paginate());
    });

    Route::get('/estates/daily', function () {
        return new EstateCollection(Estate::where("contract_type_id", 3)->orderBy('last_modified_on', 'desc')->paginate());
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
        return new EstateDetailsResource((Estate::findOrfail($id)));
    });

    Route::get('/estates/professional/{id}', function ($id) {
        return EstateResource::collection((Estate::where('agent_id', $id)->paginate(7)));
    });


    Route::post('/filters', function () {
        return new FilterResource(null);
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
        return ContactEstatesResource::collection((Contact::where('contact_type_id', 3)->whereHas('user', function ($q) use ($type) {
            $q->whereHas("professions", function ($q) use ($type) {
                $q->where("c_profession_type.id", $type);
            });
        })->orderBy('last_modified_on', 'desc')->limit(20)->get()));
    });

    Route::get('/brokers/best', function () {
        return ContactEstatesResource::collection((Contact::where('contact_type_id', 3)->whereHas('user')->orderBy('last_modified_on', 'desc')->limit(3)->get()));
    });

    Route::get('/professionals/{id}', function ($id) {
        return new ContactEstatesResource((Contact::findOrfail($id)));
    });

    Route::get('/blog/news', function () {
        return BlogResource::collection(Article::where('article_type_id', 1)->orderBy('last_modified_on', 'desc')->limit(10)->get());
    });

    Route::get('/blog/articles', function () {
        return BlogResource::collection(Article::where('article_type_id', 2)->orderBy('last_modified_on', 'desc')->limit(10)->get());
    });

    Route::get('/blog/statistics', function () {
        return BlogResource::collection(Article::where('article_type_id', 3)->orderBy('last_modified_on', 'desc')->limit(10)->get());
    });

    Route::get('/blog/{id}', function ($id) {
        return new BlogResource((Article::findOrfail($id)));
    });

    Route::get('/address_data', function () {
        return LocationResource::collection((CLocationProvince::all()));
    });

    Route::get('/estate_options', function () {
        return OptionTypeResource::collection((EstateOptionType::all()));
    });


    Route::controller(OptionsController::class)->group(function () {
        Route::get('/options', 'getOptions');
    });

    Route::controller(EvaluationController::class)->group(function () {
        Route::post('/evaluationOptions', 'getEvaluationOptions');
        Route::post('/evaluate', 'evaluate');
    });


    Route::controller(EvaluationController::class)->group(function () {
        Route::post('/evaluationOptions', 'getEvaluationOptions');
        Route::post('/evaluate', 'evaluate');
    });

    Route::post('/estates/sale', function () {
        return EstateResource::collection(Estate::where("contract_type_id", 1)->orderBy('created_on', 'desc')->paginate());
    });
});
