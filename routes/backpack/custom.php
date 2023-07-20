<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('c-building-type', 'CBuildingTypeCrudController');
    Route::crud('model-has-permission', 'ModelHasPermissionCrudController');
    Route::crud('model-has-role', 'ModelHasRoleCrudController');
    Route::crud('permission', 'PermissionCrudController');
    Route::crud('role', 'RoleCrudController');
    Route::crud('role-has-permission', 'RoleHasPermissionCrudController');
    Route::crud('c-building-project-type', 'CBuildingProjectTypeCrudController');
    Route::crud('estate', 'EstateCrudController');
    Route::crud('house', 'HouseCrudController');
    Route::crud('commercial', 'CommercialCrudController');
    Route::crud('land', 'LandCrudController');
    Route::crud('c-location-country', 'CLocationCountryCrudController');
    Route::crud('c-location-province', 'CLocationProvinceCrudController');
    Route::crud('c-location-street', 'CLocationStreetCrudController');
    Route::crud('c-location-community', 'CLocationCommunityCrudController');
    Route::crud('contact', 'ContactCrudController');
    Route::crud('c-contact-type', 'CContactTypeCrudController');
    Route::crud('c-article-type', 'CArticleTypeCrudController');
    Route::crud('article', 'ArticleCrudController');
    Route::crud('estate-option-type', 'EstateOptionTypeCrudController');
    Route::crud('c-location-city', 'CLocationCityCrudController');
    Route::crud('message', 'MessageCrudController');
    Route::crud('c-year', 'CYearCrudController');
    Route::crud('c-currency', 'CCurrencyCrudController');
    Route::crud('c-commercial-purpose-type', 'CCommercialPurposeTypeCrudController');
    Route::crud('c-land-use-type', 'CLandUseTypeCrudController');
    Route::crud('c-registered-right', 'CRegisteredRightCrudController');
    Route::crud('c-communication-type', 'CCommunicationTypeCrudController');
    Route::crud('c-elevator-type', 'CElevatorTypeCrudController');
    Route::crud('c-entrance-door-position', 'CEntranceDoorPositionCrudController');
    Route::crud('c-entrance-door-type', 'CEntranceDoorTypeCrudController');
    Route::crud('c-entrance-type', 'CEntranceTypeCrudController');
    Route::crud('c-exterior-design-type', 'CExteriorDesignTypeCrudController');
    Route::crud('c-fence-type', 'CFenceTypeCrudController');
    Route::crud('c-front-with-street', 'CFrontWithStreetCrudController');
    Route::crud('c-heating-system-type', 'CHeatingSystemTypeCrudController');
    Route::crud('c-house-building-type', 'CHouseBuildingTypeCrudController');
    Route::crud('c-land-structure-type', 'CLandStructureTypeCrudController');
    Route::crud('c-land-type', 'CLandTypeCrudController');
    Route::crud('c-parking-type', 'CParkingTypeCrudController');
    Route::crud('c-repairing-type', 'CRepairingTypeCrudController');
    Route::crud('c-road-way-type', 'CRoadWayTypeCrudController');
    Route::crud('c-roof-material-type', 'CRoofMaterialTypeCrudController');
    Route::crud('c-roof-type', 'CRoofTypeCrudController');
    Route::crud('c-service-fee-type', 'CServiceFeeTypeCrudController');
    Route::crud('c-windows-view', 'CWindowsViewCrudController');
    Route::crud('c-house-floors-type', 'CHouseFloorsTypeCrudController');
    Route::crud('c-structure-type', 'CStructureTypeCrudController');
    Route::crud('c-ceiling-height-type', 'CCeilingHeightTypeCrudController');
    Route::crud('c-building-structure-type', 'CBuildingStructureTypeCrudController');
    Route::crud('c-building-floor-type', 'CBuildingFloorTypeCrudController');
    Route::crud('c-vitrage-type', 'CVitrageTypeCrudController');
    Route::crud('c-separate-entrance-type', 'CSeparateEntranceTypeCrudController');
}); // this should be the absolute last line of this file
